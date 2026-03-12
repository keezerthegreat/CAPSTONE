<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database';

    protected $description = 'Back up the SQLite database to storage/backups, keeping the last 30 daily copies.';

    public function handle(): int
    {
        $source = database_path('database.sqlite');

        if (! file_exists($source)) {
            $this->error('SQLite database file not found.');

            return Command::FAILURE;
        }

        $backupDir = storage_path('backups');

        if (! is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $filename = 'database_'.now()->format('Y-m-d_H-i-s').'.sqlite';
        $destination = $backupDir.DIRECTORY_SEPARATOR.$filename;

        if (! copy($source, $destination)) {
            $this->error('Failed to copy database file.');

            return Command::FAILURE;
        }

        $this->info("Backup created: {$filename}");

        // Keep only the 30 most recent backups
        $backups = glob($backupDir.DIRECTORY_SEPARATOR.'database_*.sqlite');

        if ($backups !== false) {
            rsort($backups);
            foreach (array_slice($backups, 30) as $old) {
                unlink($old);
                $this->line('Removed old backup: '.basename($old));
            }
        }

        return Command::SUCCESS;
    }
}
