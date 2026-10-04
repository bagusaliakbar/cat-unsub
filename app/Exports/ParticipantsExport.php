<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class ParticipantsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $waveId;
    protected $search;
    private $rowNumber = 0;

    public function __construct($waveId = null, $search = null)
    {
        $this->waveId = $waveId;
        $this->search = $search;
    }

    public function collection(): \Illuminate\Support\Collection
    {
        $query = User::with('wave')->whereIn('role', ['peserta', 'participant']);

        if ($this->waveId) {
            $query->where('wave_id', $this->waveId);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('nik', 'like', '%' . $this->search . '%')
                  ->orWhere('participant_number', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        return $query->orderBy('name', 'asc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Lengkap',
            'NIK',
            'ID Peserta',
            'Email',
            'Desa / Kelurahan',
            'Kecamatan',
            'No. Meja',
            'Gelombang',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Pendidikan Terakhir',
            'Alamat',
            'Tanggal Terdaftar',
        ];
    }

    public function map($participant): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $participant->name,
            $participant->nik ? "'" . $participant->nik : '-',
            $participant->participant_number ?? '-',
            $participant->email ?? '-',
            $participant->desa ?: ($participant->institution ?: '-'),
            $participant->kecamatan ?? '-',
            $participant->no_meja ?? '-',
            $participant->wave->name ?? '-',
            $participant->birth_place ?? '-',
            $participant->birth_date ? Carbon::parse($participant->birth_date)->format('Y-m-d') : '-',
            $participant->latest_education ?? '-',
            $participant->address ?? '-',
            $participant->created_at ? $participant->created_at->format('d/m/Y H:i') : '-',
        ];
    }
}
