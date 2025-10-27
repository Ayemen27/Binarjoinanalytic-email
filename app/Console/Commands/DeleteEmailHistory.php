<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\TrashMail;
use Illuminate\Console\Command;

class DeleteEmailHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-email-history';
    protected $description = 'Delete all email history';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Fetch email retention period in days from settings (assuming it is stored as 'email_retention_days')
        $retentionPeriod = (int) getSetting('history_retention_days'); // Ensure it's an integer

        // Get the current date and time
        $now = Carbon::now();

        // Delete emails where expire_at + retention period is older than the current date
        $deletedRows = TrashMail::whereRaw("DATE_ADD(expire_at, INTERVAL ? DAY) <= ?", [$retentionPeriod, $now])->delete();

        // Output the result to the console
        $this->info("Email history deleted successfully. Total records deleted: {$deletedRows}");
    }
}