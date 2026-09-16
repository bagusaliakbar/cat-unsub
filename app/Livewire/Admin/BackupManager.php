<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Ifsnop\Mysqldump as IMysqldump;
use ZipArchive;
use Exception;

class BackupManager extends Component
{
    use WithFileUploads;

    public $backupFile;
    public $isRestoring = false;
    public $isBackingUp = false;

    public function render()
    {
        return view('livewire.admin.backup-manager')->layout('layouts.app');
    }

    public function downloadBackup()
    {
        $this->isBackingUp = true;
        
        try {
            $databaseName = env('DB_DATABASE');
            $userName = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $host = env('DB_HOST', '127.0.0.1');
            $port = env('DB_PORT', 3306);

            $tempDir = storage_path('app/temp_backup');
            if (!File::exists($tempDir)) {
                File::makeDirectory($tempDir, 0755, true);
            }

            // 1. Dump database using pure PHP (no mysqldump binary required)
            $sqlFile = $tempDir . '/database_backup.sql';
            
            $dump = new IMysqldump\Mysqldump(
                "mysql:host={$host};port={$port};dbname={$databaseName}",
                $userName,
                $password,
                ['add-drop-table' => true]
            );
            $dump->start($sqlFile);

            // 2. Create Zip
            $zipFile = $tempDir . '/backup_cat_' . date('Y_m_d_His') . '.zip';
            $zip = new ZipArchive();
            
            if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                // Add SQL file
                $zip->addFile($sqlFile, 'database_backup.sql');
                
                // Add public storage files (photos, etc)
                $publicStoragePath = storage_path('app/public');
                if (File::exists($publicStoragePath)) {
                    $files = File::allFiles($publicStoragePath);
                    foreach ($files as $file) {
                        $relativePath = 'public_files/' . $file->getRelativePathname();
                        $zip->addFile($file->getRealPath(), $relativePath);
                    }
                }
                
                $zip->close();
            } else {
                throw new Exception("Gagal membuat file ZIP backup.");
            }

            // Cleanup SQL file
            File::delete($sqlFile);

            $this->isBackingUp = false;
            
            return response()->download($zipFile)->deleteFileAfterSend(true);
            
        } catch (Exception $e) {
            $this->isBackingUp = false;
            session()->flash('error', 'Gagal membuat backup: ' . $e->getMessage());
        }
    }

    public function restoreBackup()
    {
        $this->validate([
            'backupFile' => 'required|file|mimes:zip|max:512000', // 500MB max
        ]);

        $this->isRestoring = true;

        try {
            $zipFile = $this->backupFile->getRealPath();
            $tempExtractDir = storage_path('app/temp_restore_' . time());
            
            if (!File::exists($tempExtractDir)) {
                File::makeDirectory($tempExtractDir, 0755, true);
            }

            $zip = new ZipArchive();
            if ($zip->open($zipFile) === TRUE) {
                $zip->extractTo($tempExtractDir);
                $zip->close();
                
                // 1. Restore Database
                $sqlFile = $tempExtractDir . '/database_backup.sql';
                if (File::exists($sqlFile)) {
                    // Turn off checks temporally if needed, though spatie generated sql handles some
                    DB::unprepared(file_get_contents($sqlFile));
                } else {
                    throw new Exception("File database_backup.sql tidak ditemukan di dalam ZIP.");
                }

                // 2. Restore Files
                $publicFilesDir = $tempExtractDir . '/public_files';
                if (File::exists($publicFilesDir)) {
                    $publicStoragePath = storage_path('app/public');
                    if (!File::exists($publicStoragePath)) {
                        File::makeDirectory($publicStoragePath, 0755, true);
                    }
                    File::copyDirectory($publicFilesDir, $publicStoragePath);
                }

                // Cleanup
                File::deleteDirectory($tempExtractDir);
                
                session()->flash('message', 'Restore berhasil! Database dan file telah dikembalikan.');
                $this->reset('backupFile');
            } else {
                throw new Exception("Gagal membuka file ZIP.");
            }
            
        } catch (Exception $e) {
            session()->flash('error', 'Restore gagal: ' . $e->getMessage());
        }

        $this->isRestoring = false;
    }
}
