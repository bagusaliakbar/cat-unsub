<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class ExamReportForm extends Component
{
    public $exam;
    public $proctor_name;
    public $supervisor_name;
    public $notes;
    public $present_count = 0;
    public $absent_count = 0;

    // Institution selection for report scope
    public $selected_institution = 'all';
    public $institutions = [];

    // Formal Fields
    public $village = '';
    public $district = '';
    public $reference_number = '';
    public $exam_materials = 'Kebangsaan, Pancasila, UUD 1945, Pemerintahan Desa, dan Perundang-undangan Desa';
    public $committee_name = 'Dr. Ujang Charda S., S.H., M.H., M.I.P., M.A.P.';
    public $witness_1 = 'Dr. Drs. H. Komir Bastaman, S.H., M.Si.';
    public $witness_2 = 'Dr. Ujang Charda S., S.H., M.H., M.I.P., M.A.P.';
    public $witness_3 = 'Dr. Moh. Asep Suharna, S.H., S.Pd., M.H.';
    public $witness_4 = 'Dr. Hj. Silvy Sondari Ghadzali, S.Psi., M.M.';
    public $witness_5 = 'Kasda, S.T., M.T.';
    public $witness_6 = '';
    public $witness_7 = '';

    public function mount($examId)
    {
        $this->exam = \App\Models\Exam::with('participants')->findOrFail($examId);
        
        // Distinct institutions from assigned participants
        $this->institutions = $this->exam->participants()
            ->whereNotNull('institution')
            ->where('institution', '!=', '')
            ->distinct()
            ->orderBy('institution')
            ->pluck('institution')
            ->toArray();

        $this->recalculateAttendance();

        // Default supervisor
        $this->supervisor_name = 'Dr. Drs. H. Komir Bastaman, S.H., M.Si.';

        $report = \App\Models\ExamReport::where('exam_id', $examId)->first();
        if ($report) {
            $this->proctor_name = $report->proctor_name ?? $this->proctor_name;
            $this->supervisor_name = $report->supervisor_name ?? $this->supervisor_name;
            $this->notes = $report->notes;
            
            // Formal Fields
            $this->village = $report->village ?? $this->village;
            $this->district = $report->district ?? $this->district;
            $this->reference_number = $report->reference_number ?? $this->reference_number;
            $this->exam_materials = $report->exam_materials ?? $this->exam_materials;
            $this->committee_name = $report->committee_name ?? $this->committee_name;
            $this->witness_1 = $report->witness_1 ?? $this->witness_1;
            $this->witness_2 = $report->witness_2 ?? $this->witness_2;
            $this->witness_3 = $report->witness_3 ?? $this->witness_3;
            $this->witness_4 = $report->witness_4 ?? $this->witness_4;
            $this->witness_5 = $report->witness_5 ?? $this->witness_5;
            $this->witness_6 = $report->witness_6 ?? $this->witness_6;
            $this->witness_7 = $report->witness_7 ?? $this->witness_7;
        }

        if (empty($this->village) && count($this->institutions) === 1) {
            $this->village = $this->institutions[0];
        }
    }

    public function updatedSelectedInstitution($value)
    {
        $this->recalculateAttendance();
        if ($value && $value !== 'all' && $value !== 'all_separated') {
            if (empty($this->village) || in_array($this->village, $this->institutions)) {
                $this->village = $value;
            }
        }
    }

    public function recalculateAttendance()
    {
        $participantQuery = $this->exam->participants();
        $sessionQuery = \App\Models\ExamSession::where('exam_id', $this->exam->id)->whereNotNull('started_at');

        if ($this->selected_institution && $this->selected_institution !== 'all' && $this->selected_institution !== 'all_separated') {
            $inst = $this->selected_institution;
            $participantQuery->where('institution', $inst);
            $sessionQuery->whereHas('user', function($q) use ($inst) {
                $q->where('institution', $inst);
            });
        }

        $totalParticipants = $participantQuery->count();
        $this->present_count = $sessionQuery->count();
        $this->absent_count = max(0, $totalParticipants - $this->present_count);
    }

    public function saveAndPrint()
    {
        $this->validate([
            'supervisor_name' => 'required|string|max:255',
            'village' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'reference_number' => 'nullable|string|max:255',
            'exam_materials' => 'required|string|max:500',
            'committee_name' => 'required|string|max:255',
        ]);

        $overallTotal = $this->exam->participants()->count();
        $overallPresent = \App\Models\ExamSession::where('exam_id', $this->exam->id)->whereNotNull('started_at')->count();
        $overallAbsent = max(0, $overallTotal - $overallPresent);

        \App\Models\ExamReport::updateOrCreate(
            ['exam_id' => $this->exam->id],
            [
                'proctor_name' => $this->proctor_name ?? '-',
                'supervisor_name' => $this->supervisor_name,
                'present_count' => $overallPresent,
                'absent_count' => $overallAbsent,
                'notes' => $this->notes,
                'village' => $this->village,
                'district' => $this->district,
                'reference_number' => $this->reference_number,
                'exam_materials' => $this->exam_materials,
                'committee_name' => $this->committee_name,
                'witness_1' => $this->witness_1,
                'witness_2' => $this->witness_2,
                'witness_3' => $this->witness_3,
                'witness_4' => $this->witness_4,
                'witness_5' => $this->witness_5,
                'witness_6' => $this->witness_6,
                'witness_7' => $this->witness_7,
            ]
        );

        \App\Services\LogService::record('cetak_berita_acara', 'Mencetak berita acara untuk ujian: ' . $this->exam->title . ' (Lingkup: ' . $this->selected_institution . ')');

        return redirect()->route('admin.exams.report.print', [
            'examId' => $this->exam->id,
            'institution' => $this->selected_institution,
        ]);
    }


    public function render()
    {
        return view('livewire.admin.exam-report-form')->layout('layouts.app');
    }
}
