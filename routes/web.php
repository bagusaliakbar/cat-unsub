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

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
    Route::get('/exams', \App\Livewire\Admin\ExamManager::class)->name('exams');
    Route::get('/exams/{examId}/monitor', \App\Livewire\Admin\ExamMonitoring::class)->name('exams.monitor');
    Route::get('/exams/{examId}/monitor/print', function ($examId) {
        $exam = \App\Models\Exam::findOrFail($examId);
        $sessions = \App\Models\ExamSession::with(['user'])
            ->where('exam_id', $examId)
            ->whereNotNull('started_at')
            ->orderByDesc('score')
            ->get();
        $report = \App\Models\ExamReport::where('exam_id', $examId)->first();
        return view('print.exam-results', compact('exam', 'sessions', 'report'));
    })->name('exams.monitor.print');
    
    Route::get('/exams/session/{sessionId}/print', function ($sessionId) {
        $session = \App\Models\ExamSession::with(['user', 'exam', 'answers.question', 'answers.option'])->findOrFail($sessionId);
        $report = \App\Models\ExamReport::where('exam_id', $session->exam_id)->first();
        $violationLogs = \App\Models\SystemLog::where('user_id', $session->user_id)
            ->where('action', 'violation')
            ->where('created_at', '>=', $session->started_at)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('print.session-result', compact('session', 'report', 'violationLogs'));
    })->name('exams.session.print');
    Route::get('/exams/{examId}/preview', \App\Livewire\Admin\ExamPreview::class)->name('exams.preview');
    Route::get('/exams/{examId}/report', \App\Livewire\Admin\ExamReportForm::class)->name('exams.report');
    Route::get('/exams/{examId}/report/print', function ($examId) {
        $exam = \App\Models\Exam::findOrFail($examId);
        $report = \App\Models\ExamReport::where('exam_id', $examId)->firstOrFail();
        $sessions = \App\Models\ExamSession::with('user')
            ->where('exam_id', $examId)
            ->whereNotNull('started_at')
            ->orderByDesc('score')
            ->get();
        return view('print.exam-report', compact('exam', 'report', 'sessions'));
    })->name('exams.report.print');
    
    Route::get('/exams/{examId}/incident-report', function ($examId) {
        $exam = \App\Models\Exam::findOrFail($examId);
        $report = \App\Models\ExamReport::where('exam_id', $examId)->first();
        return view('print.incident-report', compact('exam', 'report'));
    })->name('exams.incident-report');

    Route::get('/exams/{examId}/attendance', function ($examId) {
        $exam = \App\Models\Exam::with(['participants' => function($q) {
            $q->orderBy('name');
        }])->findOrFail($examId);
        // We might need report to get village/district details if they want it on the header
        $report = \App\Models\ExamReport::where('exam_id', $examId)->first();
        return view('print.attendance', compact('exam', 'report'));
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
        
        if ($request->has('wave_id') && $request->wave_id != '') {
            $query->where('wave_id', $request->wave_id);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('nik', 'like', '%' . $search . '%')
                  ->orWhere('participant_number', 'like', '%' . $search . '%');
            });
        }
        
        $participants = $query->orderBy('created_at', 'desc')->get();
        return view('print.participant-cards-all', compact('participants'));
    })->name('participants.print_all');
    Route::get('/waves', \App\Livewire\Admin\WaveManager::class)->name('waves');
    Route::get('/activity-log', \App\Livewire\Admin\ActivityLog::class)->name('activity-log');
    Route::get('/backup-restore', \App\Livewire\Admin\BackupManager::class)->name('backup');
    Route::get('/monitor', \App\Livewire\Admin\MonitorIndex::class)->name('monitor');
    Route::get('/pengawas', \App\Livewire\Admin\PengawasManager::class)->name('pengawas');
});

// Participant Routes
Route::middleware(['auth'])->prefix('participant')->name('participant.')->group(function () {
    Route::get('/dashboard', function() {
        return redirect()->route('home');
    })->name('dashboard');
    Route::get('/exam/{examId}', \App\Livewire\Participant\ExamExecution::class)->name('exam.execute');
    Route::get('/exam/{examId}/result', \App\Livewire\Participant\ExamResult::class)->name('exam.result');
    Route::get('/history', \App\Livewire\Participant\History::class)->name('history');
});

// Pengawas Routes
Route::middleware(['auth'])->prefix('pengawas')->name('pengawas.')->group(function () {
    Route::get('/dashboard', \App\Livewire\Pengawas\Dashboard::class)->name('dashboard');
    Route::get('/exams/{examId}/monitor', \App\Livewire\Pengawas\ExamMonitoring::class)->name('exams.monitor');
    
    // Allow pengawas to print monitoring results too
    Route::get('/exams/{examId}/monitor/print', function ($examId) {
        if (auth()->user()->role !== 'pengawas' && auth()->user()->role !== 'admin') abort(403);
        $exam = \App\Models\Exam::findOrFail($examId);
        $sessions = \App\Models\ExamSession::with(['user'])
            ->where('exam_id', $examId)
            ->whereNotNull('started_at')
            ->orderByDesc('score')
            ->get();
        $report = \App\Models\ExamReport::where('exam_id', $examId)->first();
        return view('print.exam-results', compact('exam', 'sessions', 'report'));
    })->name('exams.monitor.print');
});
