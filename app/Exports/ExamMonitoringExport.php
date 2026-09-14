<?php

namespace App\Exports;

use App\Models\ExamSession;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class ExamMonitoringExport implements FromCollection, WithHeadings, WithMapping
{
    protected $examId;

    public function __construct($examId)
    {
        $this->examId = $examId;
    }

    public function collection(): \Illuminate\Support\Collection
    {
        return ExamSession::with('user')
            ->where('exam_id', $this->examId)
            ->orderBy('score', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'NIK',
            'Nama Peserta',
            'Status',
            'Waktu Mulai',
            'Waktu Selesai',
            'Nilai Akhir',
        ];
    }

    public function map($session): array
    {
        return [
            $session->user->nik ?? '-',
            $session->user->name ?? '-',
            $session->status === 'completed' ? 'Selesai' : ($session->status === 'started' ? 'Mengerjakan' : $session->status),
            $session->started_at ? Carbon::parse($session->started_at)->translatedFormat('d F Y H:i:s') : '-',
            $session->completed_at ? Carbon::parse($session->completed_at)->translatedFormat('d F Y H:i:s') : '-',
            $session->score ?? '0',
        ];
    }
}
