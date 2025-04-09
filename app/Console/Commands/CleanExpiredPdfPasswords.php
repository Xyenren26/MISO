<?php

namespace App\Console\Commands;

use App\Models\PdfPassword;
use Illuminate\Console\Command;

class CleanExpiredPdfPasswords extends Command
{
    protected $signature = 'app:clean-expired-pdf-passwords 
                           {--dry-run : Check which records would be deleted without actually deleting them}';

    protected $description = 'Clean up expired PDF password records from the database';

    public function handle()
    {
        if ($this->option('dry-run')) {
            $count = PdfPassword::expired()->count();
            $this->info("[DRY RUN] Found {$count} expired PDF password records that would be deleted");
            return 0;
        }

        $startTime = microtime(true);
        $count = PdfPassword::deleteExpired();
        $executionTime = PdfPassword::formatDuration(microtime(true) - $startTime);

        if ($count > 0) {
            $this->info("Successfully cleaned up {$count} expired PDF password records in {$executionTime}");
        } else {
            $this->info("No expired PDF password records found to clean up");
        }

        return 0;
    }
}