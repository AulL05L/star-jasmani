<?php

namespace App\Imports;

use App\Models\Athlete;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class AthletesImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public array $results = [];
    public array $errors  = [];
    public int   $success = 0;
    public int   $skipped = 0;

    public function collection(Collection $rows)
    {
        foreach ($rows as $i => $row) {
            $rowNum = $i + 2; // +2 karena baris 1 = header

            try {
                // Validasi field wajib
                $name   = trim($row['nama'] ?? $row['name'] ?? '');
                $email  = trim(strtolower($row['email'] ?? ''));
                $gender = strtolower(trim($row['gender'] ?? ''));

                if (!$name || !$email || !$gender) {
                    $this->errors[] = "Baris {$rowNum}: Nama, email, dan gender wajib diisi.";
                    $this->skipped++;
                    continue;
                }

                if (!in_array($gender, ['pria', 'wanita', 'putra', 'putri', 'laki', 'perempuan'])) {
                    $this->errors[] = "Baris {$rowNum}: Gender tidak valid ({$gender}). Gunakan: pria/wanita.";
                    $this->skipped++;
                    continue;
                }

                // Normalize gender
                $gender = in_array($gender, ['pria', 'putra', 'laki']) ? 'pria' : 'wanita';

                // Skip jika email sudah ada
                if (User::where('email', $email)->exists()) {
                    $this->errors[] = "Baris {$rowNum}: Email {$email} sudah terdaftar, dilewati.";
                    $this->skipped++;
                    continue;
                }

                // Ambil institution
                $instCode    = strtoupper(trim($row['institusi'] ?? $row['institution'] ?? 'POLRI'));
                $institution = Institution::where('code', $instCode)->first()
                            ?? Institution::where('code', 'POLRI')->first();

                $password = trim($row['password'] ?? '') ?: 'member12345';

                DB::transaction(function () use ($row, $name, $email, $gender, $password, $institution, $instCode) {
                    $user = User::create([
                        'name'      => $name,
                        'email'     => $email,
                        'password'  => Hash::make($password),
                        'role'      => 'member',
                        'is_active' => true,
                    ]);

                    Athlete::create([
                        'user_id'            => $user->id,
                        'gender'             => $gender,
                        'nik'                => trim($row['nik'] ?? '') ?: null,
                        'phone'              => trim($row['telepon'] ?? $row['phone'] ?? '') ?: null,
                        'birth_date'         => $this->parseDate($row['tanggal_lahir'] ?? $row['birth_date'] ?? null),
                        'height_cm'          => is_numeric($row['tinggi'] ?? null) ? (float)$row['tinggi'] : null,
                        'weight_kg'          => is_numeric($row['berat'] ?? null) ? (float)$row['berat'] : null,
                        'target_institution' => $instCode,
                        'batch'              => trim($row['batch'] ?? '') ?: null,
                        'institution_id'     => $institution?->id,
                    ]);
                });

                $this->results[] = "Baris {$rowNum}: {$name} ({$email}) berhasil ditambahkan.";
                $this->success++;

            } catch (\Exception $e) {
                $this->errors[] = "Baris {$rowNum}: Error — " . $e->getMessage();
                $this->skipped++;
            }
        }
    }

    private function parseDate($value): ?string
    {
        if (!$value) return null;
        try {
            if (is_numeric($value)) {
                // Excel date serial number
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
            }
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}