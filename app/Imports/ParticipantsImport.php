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

            // Extract Name
            $name = trim($row['nama_lengkap'] ?? $row['nama'] ?? $row['name'] ?? '');
            if (empty($name)) {
                continue; // Skip empty row
            }

            // Extract NIK
            $rawNik = trim($row['nik'] ?? '');
            $nik = ltrim($rawNik, "'");
            $nik = !empty($nik) ? $nik : null;

            // Extract Email
            $email = trim($row['email'] ?? '');
            $email = !empty($email) ? strtolower($email) : null;

            // Extract Participant Number
            $participantNumber = trim($row['id_peserta'] ?? $row['nomor_peserta'] ?? $row['participant_number'] ?? '');
            $participantNumber = !empty($participantNumber) ? $participantNumber : null;

            // Validate duplicate NIK
            if ($nik && User::where('nik', $nik)->exists()) {
                $this->skippedCount++;
                $this->messages[] = "Baris {$rowIndex}: NIK '{$nik}' sudah digunakan peserta lain (dilewati).";
                continue;
            }

            // Validate duplicate Email
            if ($email) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $email = null;
                } elseif (User::where('email', $email)->exists()) {
                    $this->skippedCount++;
                    $this->messages[] = "Baris {$rowIndex}: Email '{$email}' sudah digunakan peserta lain (dilewati).";
                    continue;
                }
            }

            // Validate duplicate Participant Number
            if ($participantNumber && User::where('participant_number', $participantNumber)->exists()) {
                $this->skippedCount++;
                $this->messages[] = "Baris {$rowIndex}: ID Peserta '{$participantNumber}' sudah ada (dilewati).";
                continue;
            }

            // Fallback unique participant number if neither NIK, Email, nor ID was specified
            if (empty($nik) && empty($email) && empty($participantNumber)) {
                $participantNumber = 'PST-' . date('ymd') . rand(100, 999);
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
            $institution = trim($row['instansi'] ?? $row['sekolah'] ?? $row['institution'] ?? '') ?: null;
            $birthPlace = trim($row['tempat_lahir'] ?? $row['birth_place'] ?? '') ?: null;
            $latestEducation = trim($row['pendidikan_terakhir'] ?? $row['pendidikan'] ?? $row['latest_education'] ?? '') ?: null;
            $address = trim($row['alamat'] ?? $row['address'] ?? '') ?: null;

            User::create([
                'name' => $name,
                'nik' => $nik,
                'participant_number' => $participantNumber,
                'email' => $email,
                'password' => $password,
                'institution' => $institution,
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
