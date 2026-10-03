<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ParticipantTemplateExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'nama_lengkap',
            'nik',
            'id_peserta',
            'email',
            'password',
            'instansi',
            'gelombang',
            'tempat_lahir',
            'tanggal_lahir',
            'pendidikan_terakhir',
            'alamat',
        ];
    }

    public function array(): array
    {
        return [
            [
                'Ahmad Pratama',
                "'3201012304950001",
                'PST-2026-001',
                'ahmad@example.com',
                'password123',
                'SMA Negeri 1 Subang',
                'Gelombang 1',
                'Subang',
                '2002-05-14',
                'SMA/SMK/Sederajat',
                'Jl. RA Kartini No. 12, Subang',
            ],
            [
                'Siti Rahmawati',
                '',
                'PST-2026-002',
                '',
                '',
                'SMA Negeri 2 Subang',
                'Gelombang 1',
                'Bandung',
                '2003-08-20',
                'SMA/SMK/Sederajat',
                'Jl. Otista No. 45, Subang',
            ],
            [
                'Budi Santoso',
                '',
                '',
                'budi@example.com',
                '',
                'Universitas Subang',
                '',
                'Jakarta',
                '2001-11-05',
                'S1',
                'Jl. Pejuang No. 8',
            ],
        ];
    }
}
