<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    // Nama dan signature untuk command
    protected $signature = 'db:backup';

    // Deskripsi command
    protected $description = 'Backup MySQL database using mysqldump';

    // Jalankan perintah ini saat command dipanggil
    public function handle()
    {
        // Nama database yang akan dibackup
        $databaseName = env('DB_DATABASE'); // Ambil dari .env
        // Nama file backup (gunakan timestamp agar tidak tertimpa)
        $backupFile = storage_path('app/backups/presensigps_backup_' . date('Y-m-d_H-i-s') . '.sql');

        // Menyusun perintah mysqldump
        $command = "mysqldump -u " . env('DB_USERNAME') . " -p" . env('DB_PASSWORD') . " " . $databaseName . " > " . $backupFile;

        // Jalankan perintah backup
        $output = null;
        $resultCode = null;
        exec($command, $output, $resultCode);

        // Periksa apakah backup berhasil
        if ($resultCode === 0) {
            $this->info('Backup database berhasil disimpan di: ' . $backupFile);
        } else {
            $this->error('Terjadi kesalahan saat melakukan backup database.');
        }
    }
}
