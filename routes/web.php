<?php

use Illuminate\Support\Facades\Route;

// Halaman utama sekarang adalah Entry Token Peserta
Route::get('/', \App\Livewire\Participant\Entry::class)->name('home');

Route::get('dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    if (auth()->user()->role === 'pengawas') {
        return redirect()->route('pengawas.dashboard');
    }
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rute Verifikasi QR Code Peserta
Route::get('/verify/{token}', function ($token) {
    $participant = App\Models\User::with(['assignedExams', 'wave'])
        ->where('participant_number', $token)
        ->firstOrFail();

    return view('verification', compact('participant'));
})->name('verify');

// Route to bypass symlink issues on shared hosting
Route::get('/storage-file/{path}', function ($path) {
    $absolutePath = storage_path('app/public/' . $path);
    if (!file_exists($absolutePath)) {
        abort(404);
    }
    return response()->file($absolutePath);
})->where('path', '.*')->name('storage.file');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

$renderExamResults = function ($examId, \Illuminate\Http\Request $request) {
    $exam = \App\Models\Exam::with(['wave', 'participants'])->findOrFail($examId);
    $report = \App\Models\ExamReport::where('exam_id', $examId)->first();
    $institution = $request->query('institution', 'all');
    $sort = $request->query('sort', 'score');

    $institutions = $exam->participants()
        ->whereNotNull('desa')
        ->where('desa', '!=', '')
        ->distinct()
        ->orderBy('desa')
        ->pluck('desa')
        ->toArray();

    if (empty($institutions)) {
        $institutions = \App\Models\User::whereHas('examSessions', fn($q) => $q->where('exam_id', $examId))
            ->whereNotNull('desa')
            ->where('desa', '!=', '')
            ->distinct()
            ->orderBy('desa')
            ->pluck('desa')
            ->toArray();
    }

    $baseSessionsQuery = \App\Models\ExamSession::with(['user.wave', 'exam'])
        ->where('exam_id', $examId)
        ->whereNotNull('started_at');

    $sortCollection = function($collection) use ($sort) {
        return $collection->sort(function ($a, $b) use ($sort) {
            if ($sort === 'participant_number') {
                $numA = $a->user->participant_number ?? '';
                $numB = $b->user->participant_number ?? '';
                if (!empty($numA) && !empty($numB)) {
                    $cmp = strnatcasecmp($numA, $numB);
                    if ($cmp !== 0) return $cmp;
                }
                return strcasecmp($a->user->name ?? '', $b->user->name ?? '');
            }

            if ($sort === 'desa') {
                $desaA = $a->user->desa ?? $a->user->institution ?? '';
                $desaB = $b->user->desa ?? $b->user->institution ?? '';
                $cmpDesa = strcasecmp($desaA, $desaB);
                if ($cmpDesa !== 0) {
                    return $cmpDesa;
                }
                if ($a->score != $b->score) {
                    return $b->score <=> $a->score;
                }
                return strnatcasecmp($a->user->participant_number ?? '', $b->user->participant_number ?? '');
            }

            if ($sort === 'no_meja') {
                $mejaA = (int)($a->user->no_meja ?? 999999);
                $mejaB = (int)($b->user->no_meja ?? 999999);
                if ($mejaA !== $mejaB) {
                    return $mejaA <=> $mejaB;
                }
                return strnatcasecmp($a->user->participant_number ?? '', $b->user->participant_number ?? '');
            }

            // Default: score DESC (Ranking)
            if ($a->score != $b->score) {
                return $b->score <=> $a->score;
            }
            $numA = $a->user->participant_number ?? '';
            $numB = $b->user->participant_number ?? '';
            if (!empty($numA) && !empty($numB)) {
                $cmp = strnatcasecmp($numA, $numB);
                if ($cmp !== 0) return $cmp;
            }
            return strcasecmp($a->user->name ?? '', $b->user->name ?? '');
        })->values();
    };

    if ($institution === 'all_separated' && !empty($institutions)) {
        $resultsData = [];
        foreach ($institutions as $inst) {
            $sessQuery = clone $baseSessionsQuery;
            $sessQuery->whereHas('user', function($q) use ($inst) {
                $q->where('desa', $inst);
            });
            $instSessions = $sortCollection($sessQuery->get());

            $firstUser = \App\Models\User::where('desa', $inst)->whereNotNull('kecamatan')->where('kecamatan', '!=', '')->first();
            $instDistrict = $firstUser ? $firstUser->kecamatan : ($report->district ?? null);

            $resultsData[] = [
                'institution' => $inst,
                'village' => $inst,
                'district' => $instDistrict,
                'sessions' => $instSessions,
            ];
        }
        return view('print.exam-results', compact('exam', 'report', 'institutions', 'institution', 'resultsData', 'sort'));
    }

    if ($institution && $institution !== 'all') {
        $sessQuery = clone $baseSessionsQuery;
        $sessQuery->whereHas('user', function($q) use ($institution) {
            $q->where('desa', $institution);
        });
        $sessions = $sortCollection($sessQuery->get());
        $targetVillage = $institution;

        $firstUser = \App\Models\User::where('desa', $institution)->whereNotNull('kecamatan')->where('kecamatan', '!=', '')->first();
        $targetDistrict = ($firstUser && !empty($firstUser->kecamatan)) ? $firstUser->kecamatan : ($report->district ?? null);

        return view('print.exam-results', compact('exam', 'report', 'sessions', 'institution', 'institutions', 'targetVillage', 'targetDistrict', 'sort'));
    }

    $sessions = $sortCollection($baseSessionsQuery->get());
    $targetVillage = 'all';
    $allUsersDistricts = $sessions->pluck('user.kecamatan')->filter()->map(fn($k) => trim($k))->filter()->unique()->values();
    $targetDistrict = $allUsersDistricts->count() === 1 ? $allUsersDistricts->first() : ($allUsersDistricts->isEmpty() ? ($report->district ?? null) : null);

    return view('print.exam-results', compact('exam', 'report', 'sessions', 'institution', 'institutions', 'targetVillage', 'targetDistrict', 'sort'));
};

// Admin Routes
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class.':admin'])->prefix('admin')->name('admin.')->group(function () use ($renderExamResults) {
    Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
    Route::get('/exams', \App\Livewire\Admin\ExamManager::class)->name('exams');
    Route::get('/exams/{examId}/monitor', \App\Livewire\Admin\ExamMonitoring::class)->name('exams.monitor');
    Route::get('/exams/{examId}/monitor/print', function ($examId, \Illuminate\Http\Request $request) use ($renderExamResults) {
        return $renderExamResults($examId, $request);
    })->name('exams.monitor.print');
    
    Route::get('/exams/session/{sessionId}/print', function ($sessionId) {
        $session = \App\Models\ExamSession::with(['user.wave', 'exam.wave', 'answers.question.options', 'answers.option'])->findOrFail($sessionId);
        $report = \App\Models\ExamReport::where('exam_id', $session->exam_id)->first();
        $violationLogs = \App\Models\SystemLog::where('user_id', $session->user_id)
            ->where('action', 'violation')
            ->where('created_at', '>=', $session->started_at)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('print.session-result', compact('session', 'report', 'violationLogs'));
    })->name('exams.session.print');

    Route::get('/exams/session/{sessionId}/print-violations', function ($sessionId) {
        $session = \App\Models\ExamSession::with(['user.wave', 'exam.wave'])->findOrFail($sessionId);
        $report = \App\Models\ExamReport::where('exam_id', $session->exam_id)->first();
        $violationLogs = \App\Models\SystemLog::where('user_id', $session->user_id)
            ->where('action', 'violation')
            ->where('created_at', '>=', $session->started_at)
            ->orderBy('created_at', 'asc')
            ->get();
        return view('print.violation-report', compact('session', 'report', 'violationLogs'));
    })->name('exams.session.print-violations');

    Route::get('/exams/{examId}/preview', \App\Livewire\Admin\ExamPreview::class)->name('exams.preview');
    Route::get('/exams/{examId}/report', \App\Livewire\Admin\ExamReportForm::class)->name('exams.report');
    Route::get('/exams/{examId}/report/print', function ($examId, \Illuminate\Http\Request $request) {
        $exam = \App\Models\Exam::with(['participants', 'wave'])->findOrFail($examId);
        $report = \App\Models\ExamReport::where('exam_id', $examId)->firstOrFail();
        $institution = $request->query('institution', 'all');
        $scope = $request->query('scope', 'single');
        $sort = $request->query('sort', 'participant_number'); // Default: 'participant_number'

        $isCombinedSession = false;
        $combinedLocations = $exam->location ?? 'Lab Komputer';
        $targetExamIds = [$exam->id];

        if ($scope === 'combined_session') {
            if ($exam->wave_id) {
                $sessionExams = \App\Models\Exam::where('wave_id', $exam->wave_id)->get();
            } elseif ($exam->start_time) {
                $sessionExams = \App\Models\Exam::where('start_time', $exam->start_time)
                    ->where('title', $exam->title)
                    ->get();
            } else {
                $sessionExams = collect([$exam]);
            }

            if ($sessionExams->count() > 1) {
                $isCombinedSession = true;
                $targetExamIds = $sessionExams->pluck('id')->toArray();
                $combinedLocations = $sessionExams->pluck('location')->filter()->unique()->implode(' & ');
            }
        }

        if ($isCombinedSession) {
            $institutions = \App\Models\User::whereHas('exams', fn($q) => $q->whereIn('exams.id', $targetExamIds))
                ->whereNotNull('desa')
                ->where('desa', '!=', '')
                ->distinct()
                ->orderBy('desa')
                ->pluck('desa')
                ->toArray();
        } else {
            $institutions = $exam->participants()
                ->whereNotNull('desa')
                ->where('desa', '!=', '')
                ->distinct()
                ->orderBy('desa')
                ->pluck('desa')
                ->toArray();
        }

        $baseSessionsQuery = \App\Models\ExamSession::with(['user', 'exam'])
            ->whereIn('exam_id', $targetExamIds)
            ->whereNotNull('started_at');

        $sortCollection = function($collection) use ($sort) {
            return $collection->sort(function ($a, $b) use ($sort) {
                if ($sort === 'score') {
                    if ($a->score != $b->score) {
                        return $b->score <=> $a->score;
                    }
                    return strnatcasecmp($a->user->participant_number ?? '', $b->user->participant_number ?? '');
                }

                if ($sort === 'desa') {
                    $desaA = $a->user->desa ?? $a->user->institution ?? '';
                    $desaB = $b->user->desa ?? $b->user->institution ?? '';
                    $cmpDesa = strcasecmp($desaA, $desaB);
                    if ($cmpDesa !== 0) {
                        return $cmpDesa;
                    }
                    return strnatcasecmp($a->user->participant_number ?? '', $b->user->participant_number ?? '');
                }

                if ($sort === 'no_meja') {
                    $mejaA = (int)($a->user->no_meja ?? 999999);
                    $mejaB = (int)($b->user->no_meja ?? 999999);
                    if ($mejaA !== $mejaB) {
                        return $mejaA <=> $mejaB;
                    }
                    return strnatcasecmp($a->user->participant_number ?? '', $b->user->participant_number ?? '');
                }

                // Default: participant_number ASC (CAT2026001, CAT2026002, ...)
                $numA = $a->user->participant_number ?? '';
                $numB = $b->user->participant_number ?? '';
                if (!empty($numA) && !empty($numB)) {
                    $cmp = strnatcasecmp($numA, $numB);
                    if ($cmp !== 0) return $cmp;
                }
                return strcasecmp($a->user->name ?? '', $b->user->name ?? '');
            })->values();
        };

        $getParticipantsCount = function($inst = null) use ($isCombinedSession, $targetExamIds, $exam) {
            if ($isCombinedSession) {
                $q = \App\Models\User::whereHas('exams', fn($sq) => $sq->whereIn('exams.id', $targetExamIds));
            } else {
                $q = $exam->participants();
            }
            if ($inst) {
                $q->where('desa', $inst);
            }
            return $q->count();
        };

        // Mode: 'all_separated' -> Batch Print / Multi-Page Per Institution
        if ($institution === 'all_separated' && !empty($institutions)) {
            $reportsData = [];
            foreach ($institutions as $inst) {
                $sessQuery = clone $baseSessionsQuery;
                $sessQuery->whereHas('user', function($q) use ($inst) {
                    $q->where('desa', $inst);
                });
                $sessions = $sortCollection($sessQuery->get());
                $partCount = $getParticipantsCount($inst);
                $presentCount = $sessions->count();
                $absentCount = max(0, $partCount - $presentCount);

                $firstUser = \App\Models\User::where('desa', $inst)->whereNotNull('kecamatan')->where('kecamatan', '!=', '')->first();
                $instDistrict = $firstUser ? $firstUser->kecamatan : ($report->district ?? null);

                $reportsData[] = [
                    'institution' => $inst,
                    'village' => $inst,
                    'district' => $instDistrict,
                    'present_count' => $presentCount,
                    'absent_count' => $absentCount,
                    'total_count' => $partCount,
                    'sessions' => $sessions,
                ];
            }
            return view('print.exam-report-batch', compact('exam', 'report', 'reportsData', 'institutions', 'institution', 'scope', 'sort', 'isCombinedSession', 'combinedLocations'));
        }

        // Mode: Specific single institution
        if ($institution && $institution !== 'all') {
            $sessQuery = clone $baseSessionsQuery;
            $sessQuery->whereHas('user', function($q) use ($institution) {
                $q->where('desa', $institution);
            });
            $sessions = $sortCollection($sessQuery->get());
            $partCount = $getParticipantsCount($institution);
            $presentCount = $sessions->count();
            $absentCount = max(0, $partCount - $presentCount);
            $targetVillage = $institution;

            $firstUser = \App\Models\User::where('desa', $institution)->whereNotNull('kecamatan')->where('kecamatan', '!=', '')->first();
            $targetDistrict = ($firstUser && !empty($firstUser->kecamatan)) ? $firstUser->kecamatan : ($report->district ?? null);

            return view('print.exam-report', compact('exam', 'report', 'sessions', 'institution', 'institutions', 'presentCount', 'absentCount', 'targetVillage', 'targetDistrict', 'scope', 'sort', 'isCombinedSession', 'combinedLocations'));
        }

        // Mode: All combined
        $sessions = $sortCollection($baseSessionsQuery->get());
        $totalRegistered = $getParticipantsCount();
        $presentCount = $sessions->count();
        $absentCount = max(0, $totalRegistered - $presentCount);
        $targetVillage = 'all';
        $allUsersDistricts = $sessions->pluck('user.kecamatan')->filter()->map(fn($k) => trim($k))->filter()->unique()->values();
        $targetDistrict = $allUsersDistricts->count() === 1 ? $allUsersDistricts->first() : ($allUsersDistricts->isEmpty() ? ($report->district ?? null) : null);

        return view('print.exam-report', compact('exam', 'report', 'sessions', 'institution', 'institutions', 'presentCount', 'absentCount', 'targetVillage', 'targetDistrict', 'scope', 'sort', 'isCombinedSession', 'combinedLocations'));
    })->name('exams.report.print');
    
    Route::get('/exams/{examId}/incident-report', function ($examId) {
        $exam = \App\Models\Exam::findOrFail($examId);
        $report = \App\Models\ExamReport::where('exam_id', $examId)->first();
        return view('print.incident-report', compact('exam', 'report'));
    })->name('exams.incident-report');

    Route::get('/exams/{examId}/attendance', function ($examId, \Illuminate\Http\Request $request) {
        $exam = \App\Models\Exam::with(['wave', 'participants' => function($q) {
            $q->orderByRaw('CASE WHEN no_meja IS NULL OR no_meja = "" THEN 1 ELSE 0 END, CAST(no_meja AS UNSIGNED) ASC, no_meja ASC, name ASC');
        }])->findOrFail($examId);
        $report = \App\Models\ExamReport::where('exam_id', $examId)->first();
        $institution = $request->query('institution', 'all');

        $institutions = $exam->participants()
            ->whereNotNull('desa')
            ->where('desa', '!=', '')
            ->distinct()
            ->orderBy('desa')
            ->pluck('desa')
            ->toArray();

        if ($institution === 'all_separated' && !empty($institutions)) {
            $attendanceData = [];
            foreach ($institutions as $inst) {
                $parts = $exam->participants()
                    ->where('desa', $inst)
                    ->orderByRaw('CASE WHEN no_meja IS NULL OR no_meja = "" THEN 1 ELSE 0 END, CAST(no_meja AS UNSIGNED) ASC, no_meja ASC, name ASC')
                    ->get();
                $firstUser = $parts->first(fn($u) => !empty($u->kecamatan));
                $instDistrict = $firstUser ? $firstUser->kecamatan : ($report->district ?? null);

                $attendanceData[] = [
                    'institution' => $inst,
                    'village' => $inst,
                    'district' => $instDistrict,
                    'participants' => $parts,
                ];
            }
            return view('print.attendance', compact('exam', 'report', 'institutions', 'institution', 'attendanceData'));
        }

        $participantsQuery = $exam->participants()
            ->orderByRaw('CASE WHEN no_meja IS NULL OR no_meja = "" THEN 1 ELSE 0 END, CAST(no_meja AS UNSIGNED) ASC, no_meja ASC, name ASC');
        if ($institution && $institution !== 'all') {
            $participantsQuery->where('desa', $institution);
        }
        $participants = $participantsQuery->get();
        $targetVillage = ($institution && $institution !== 'all') ? $institution : 'all';
        $allPartsDistricts = $participants->pluck('kecamatan')->filter()->map(fn($k) => trim($k))->filter()->unique()->values();
        $targetDistrict = ($institution && $institution !== 'all')
            ? ($participants->first(fn($u) => !empty($u->kecamatan))?->kecamatan ?: ($report->district ?? null))
            : ($allPartsDistricts->count() === 1 ? $allPartsDistricts->first() : ($allPartsDistricts->isEmpty() ? ($report->district ?? null) : null));

        return view('print.attendance', compact('exam', 'report', 'participants', 'institution', 'institutions', 'targetVillage', 'targetDistrict'));
    })->name('exams.attendance');

    Route::get('/questions', \App\Livewire\Admin\QuestionManager::class)->name('questions');
    Route::get('/categories', \App\Livewire\Admin\CategoryManager::class)->name('categories');
    Route::get('/participants', \App\Livewire\Admin\ParticipantManager::class)->name('participants');
    Route::get('/participants/{participantId}/print', function ($participantId) {
        $participant = \App\Models\User::findOrFail($participantId);
        return view('print.participant-card', compact('participant'));
    })->name('participants.print');

    Route::get('/participants/print-all', function (Illuminate\Http\Request $request) {
        $query = \App\Models\User::with('wave')->whereIn('role', ['peserta', 'participant']);
        
        if ($request->has('ids') && !empty($request->ids)) {
            $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
            $query->whereIn('id', $ids);
        }

        if ($request->has('wave_id') && $request->wave_id != '') {
            $query->where('wave_id', $request->wave_id);
        }

        if ($request->has('desa') && $request->desa != '') {
            $query->where('desa', $request->desa);
        }

        if ($request->has('kecamatan') && $request->kecamatan != '') {
            $query->where('kecamatan', $request->kecamatan);
        }

        $examStatus = $request->query('exam_status', 'belum_ujian');
        if ($examStatus === '' || $examStatus === 'belum_ujian') {
            $query->whereDoesntHave('examSessions', function ($q) {
                $q->where('status', 'completed')->orWhereNotNull('completed_at');
            });
        } elseif ($examStatus === 'sudah_ujian') {
            $query->whereHas('examSessions', function ($q) {
                $q->where('status', 'completed')->orWhereNotNull('completed_at');
            });
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('nik', 'like', '%' . $search . '%')
                  ->orWhere('participant_number', 'like', '%' . $search . '%')
                  ->orWhere('desa', 'like', '%' . $search . '%')
                  ->orWhere('kecamatan', 'like', '%' . $search . '%')
                  ->orWhere('no_meja', 'like', '%' . $search . '%');
            });
        }
        
        $participants = $query->orderByRaw('CASE WHEN no_meja IS NULL OR no_meja = "" THEN 1 ELSE 0 END, CAST(no_meja AS UNSIGNED) ASC, no_meja ASC, name ASC')->get();
        return view('print.participant-cards-all', compact('participants'));
    })->name('participants.print_all');

    Route::get('/participants/{participantId}/print-desk-number', function ($participantId, Illuminate\Http\Request $request) {
        $participant = \App\Models\User::with(['wave', 'assignedExams'])->findOrFail($participantId);
        $participants = collect([$participant]);
        $forcedTheme = $request->query('theme', 'auto');
        $lab = $request->query('lab');
        $session = $request->query('session');
        $time = $request->query('time');
        return view('print.desk-numbers-all', compact('participants', 'forcedTheme', 'lab', 'session', 'time'));
    })->name('participants.print_desk_number');

    Route::get('/participants/print-desk-numbers', function (Illuminate\Http\Request $request) {
        $query = \App\Models\User::with(['wave', 'assignedExams'])->whereIn('role', ['peserta', 'participant']);
        
        if ($request->has('ids') && !empty($request->ids)) {
            $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
            $query->whereIn('id', $ids);
        }

        if ($request->has('wave_id') && $request->wave_id != '') {
            $query->where('wave_id', $request->wave_id);
        }

        if ($request->has('desa') && $request->desa != '') {
            $query->where('desa', $request->desa);
        }

        if ($request->has('kecamatan') && $request->kecamatan != '') {
            $query->where('kecamatan', $request->kecamatan);
        }

        $examStatus = $request->query('exam_status', 'belum_ujian');
        if ($examStatus === '' || $examStatus === 'belum_ujian') {
            $query->whereDoesntHave('examSessions', function ($q) {
                $q->where('status', 'completed')->orWhereNotNull('completed_at');
            });
        } elseif ($examStatus === 'sudah_ujian') {
            $query->whereHas('examSessions', function ($q) {
                $q->where('status', 'completed')->orWhereNotNull('completed_at');
            });
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('nik', 'like', '%' . $search . '%')
                  ->orWhere('participant_number', 'like', '%' . $search . '%')
                  ->orWhere('desa', 'like', '%' . $search . '%')
                  ->orWhere('kecamatan', 'like', '%' . $search . '%')
                  ->orWhere('no_meja', 'like', '%' . $search . '%');
            });
        }
        
        $participants = $query->orderByRaw('CASE WHEN no_meja IS NULL OR no_meja = "" THEN 1 ELSE 0 END, CAST(no_meja AS UNSIGNED) ASC, no_meja ASC, name ASC')->get();
        $forcedTheme = $request->query('theme', 'auto');
        $lab = $request->query('lab');
        $session = $request->query('session');
        $time = $request->query('time');
        return view('print.desk-numbers-all', compact('participants', 'forcedTheme', 'lab', 'session', 'time'));
    })->name('participants.print_all_desk_numbers');

    Route::get('/exams/{examId}/print-desk-numbers', function ($examId, Illuminate\Http\Request $request) {
        $exam = \App\Models\Exam::with('wave')->findOrFail($examId);
        $query = $exam->participants()->with(['wave', 'assignedExams']);

        if ($request->has('ids') && !empty($request->ids)) {
            $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
            $query->whereIn('users.id', $ids);
        }

        $examStatus = $request->query('exam_status', 'belum_ujian');
        if ($examStatus === '' || $examStatus === 'belum_ujian') {
            $query->whereDoesntHave('examSessions', function ($q) {
                $q->where('status', 'completed')->orWhereNotNull('completed_at');
            });
        } elseif ($examStatus === 'sudah_ujian') {
            $query->whereHas('examSessions', function ($q) {
                $q->where('status', 'completed')->orWhereNotNull('completed_at');
            });
        }

        $participants = $query->orderByRaw('CASE WHEN no_meja IS NULL OR no_meja = "" THEN 1 ELSE 0 END, CAST(no_meja AS UNSIGNED) ASC, no_meja ASC, name ASC')->get();
        $forcedTheme = $request->query('theme', 'auto');
        $lab = $request->query('lab', $exam->location);
        $session = $request->query('session');
        $time = $request->query('time');
        return view('print.desk-numbers-all', compact('participants', 'exam', 'forcedTheme', 'lab', 'session', 'time'));
    })->name('exams.print_desk_numbers');
    Route::get('/waves', \App\Livewire\Admin\WaveManager::class)->name('waves');
    Route::get('/activity-log', \App\Livewire\Admin\ActivityLog::class)->name('activity-log');
    Route::get('/backup-restore', \App\Livewire\Admin\BackupManager::class)->name('backup');
    Route::get('/monitor', \App\Livewire\Admin\MonitorIndex::class)->name('monitor');
    Route::get('/pengawas', \App\Livewire\Admin\PengawasManager::class)->name('pengawas');
});

// Participant Routes
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class.':participant'])->prefix('participant')->name('participant.')->group(function () {
    Route::get('/dashboard', function() {
        return redirect()->route('home');
    })->name('dashboard');
    Route::get('/exam/{examId}', \App\Livewire\Participant\ExamExecution::class)->name('exam.execute');
    Route::get('/exam/{examId}/result', \App\Livewire\Participant\ExamResult::class)->name('exam.result');
    Route::get('/history', \App\Livewire\Participant\History::class)->name('history');
});

// Pengawas Routes
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class.':pengawas'])->prefix('pengawas')->name('pengawas.')->group(function () use ($renderExamResults) {
    Route::get('/dashboard', \App\Livewire\Pengawas\Dashboard::class)->name('dashboard');
    Route::get('/exams/{examId}/monitor', \App\Livewire\Pengawas\ExamMonitoring::class)->name('exams.monitor');
    
    // Allow pengawas to print monitoring results too
    Route::get('/exams/{examId}/monitor/print', function ($examId, \Illuminate\Http\Request $request) use ($renderExamResults) {
        if (auth()->user()->role !== 'pengawas' && auth()->user()->role !== 'admin') abort(403);
        return $renderExamResults($examId, $request);
    })->name('exams.monitor.print');
});
