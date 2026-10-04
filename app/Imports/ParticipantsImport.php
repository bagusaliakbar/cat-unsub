<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Wave;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ParticipantsImport implements ToCollection, WithHeadingRow
{
    public int $importedCount = 0;
    public int $skippedCount = 0;
    public array $messages = [];

    public function collection(Collection $rows): void
    {
        $rowIndex = 1; // header is row 1, data starts at 2

        foreach ($rows as $row) {
            $rowIndex++;

            // Extract Name (supports common variations)
            $name = trim(
                $row['nama_lengkap'] ?? 
                $row['nama_peserta'] ?? 
                $row['nama'] ?? 
                $row['name'] ?? 
                $row['peserta'] ?? 
                $row['fullname'] ?? 
                $row['full_name'] ?? 
                $row['nama_siswa'] ?? ''
            );
            if (empty($name)) {
                continue; // Skip empty row
            }

            // Extract NIK
            $rawNik = trim($row['nik'] ?? $row['no_ktp'] ?? $row['nomor_ktp'] ?? $row['no_identitas'] ?? '');
            $nik = ltrim($rawNik, "'");
            $nik = trim($nik);
            // Convert dashes, placeholders, and empty strings to NULL
            if (in_array(strtolower($nik), ['', '-', '--', '---', '0', 'null', 'n/a', 'none', 'tidak ada', 'tdk ada', 'kosong'], true)) {
                $nik = null;
            }

            // Extract Email
            $rawEmail = trim($row['email'] ?? $row['e_mail'] ?? $row['surel'] ?? '');
            $email = strtolower($rawEmail);
            if (in_array($email, ['', '-', '--', '---', 'null', 'n/a', 'none', 'tidak ada', 'tdk ada', 'kosong'], true) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $email = null;
            }

            // Extract Participant Number
            $rawId = trim($row['id_peserta'] ?? $row['nomor_peserta'] ?? $row['no_peserta'] ?? $row['participant_number'] ?? $row['nopes'] ?? '');
            if (in_array(strtolower($rawId), ['', '-', '--', '---', '0', 'null', 'n/a', 'none'], true)) {
                $participantNumber = null;
            } else {
                $participantNumber = $rawId;
            }

            // Validate duplicate NIK (only for real, non-null NIK)
            if ($nik !== null && User::where('nik', $nik)->exists()) {
                $this->skippedCount++;
                $this->messages[] = "Baris {$rowIndex} ({$name}): NIK '{$nik}' sudah terdaftar";
                continue;
            }

            // Validate duplicate Email (only for real, non-null Email)
            if ($email !== null && User::where('email', $email)->exists()) {
                $this->skippedCount++;
                $this->messages[] = "Baris {$rowIndex} ({$name}): Email '{$email}' sudah terdaftar";
                continue;
            }

            // Validate duplicate Participant Number (only if provided in file)
            if ($participantNumber !== null && User::where('participant_number', $participantNumber)->exists()) {
                $this->skippedCount++;
                $this->messages[] = "Baris {$rowIndex} ({$name}): ID Peserta '{$participantNumber}' sudah terdaftar";
                continue;
            }

            // Always ensure a unique participant number is assigned
            if (empty($participantNumber)) {
                do {
                    $participantNumber = 'PST-' . date('ymd') . rand(1000, 9999);
                } while (User::where('participant_number', $participantNumber)->exists());
            }

            // Password
            $rawPassword = trim($row['password'] ?? '');
            $password = !empty($rawPassword) ? Hash::make($rawPassword) : Hash::make('123456');

            // Wave
            $waveInput = trim($row['gelombang'] ?? $row['wave'] ?? '');
            $waveId = null;
            if (!empty($waveInput)) {
                $wave = Wave::where('name', 'like', $waveInput)->orWhere('id', $waveInput)->first();
                $waveId = $wave ? $wave->id : null;
            }

            // Birth Date
            $rawBirthDate = $row['tanggal_lahir'] ?? $row['birth_date'] ?? null;
            $birthDate = null;
            if (!empty($rawBirthDate)) {
                try {
                    if (is_numeric($rawBirthDate)) {
                        $birthDate = ExcelDate::excelToDateTimeObject($rawBirthDate)->format('Y-m-d');
                    } else {
                        $birthDate = Carbon::parse($rawBirthDate)->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    $birthDate = null;
                }
            }

            // Other fields
            $desa = trim($row['desa'] ?? $row['kelurahan'] ?? $row['instansi'] ?? $row['sekolah'] ?? $row['institution'] ?? '') ?: null;
            $kecamatan = trim($row['kecamatan'] ?? $row['kec'] ?? '') ?: null;
            $noMeja = trim($row['no_meja'] ?? $row['nomor_meja'] ?? $row['meja'] ?? '') ?: null;
            $birthPlace = trim($row['tempat_lahir'] ?? $row['birth_place'] ?? '') ?: null;
            $latestEducation = trim($row['pendidikan_terakhir'] ?? $row['pendidikan'] ?? $row['latest_education'] ?? '') ?: null;
            $address = trim($row['alamat'] ?? $row['address'] ?? '') ?: null;

            User::create([
                'name' => $name,
                'nik' => $nik,
                'participant_number' => $participantNumber,
                'email' => $email,
                'password' => $password,
                'desa' => $desa,
                'kecamatan' => $kecamatan,
                'no_meja' => $noMeja,
                'wave_id' => $waveId,
                'birth_place' => $birthPlace,
                'birth_date' => $birthDate,
                'latest_education' => $latestEducation,
                'address' => $address,
                'role' => 'participant',
            ]);

            $this->importedCount++;
        }
    }
}
