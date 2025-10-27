<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Imap;
use Illuminate\Console\Command;
use App\Services\TrashMailService;

class DeleteMessages extends Command
{
    protected $trashMailService;

    public function __construct(TrashMailService $trashMailService)
    {
        // Call the parent constructor to initialize the command
        parent::__construct();

        // Initialize the TrashMailService
        $this->trashMailService = $trashMailService;
    }



    protected $signature = 'app:delete-messages';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Old Messages';

    /**
     * Execute the console command.
     */


    public function handle()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '256000M');


        $imap = Imap::where('tag', 'main')->first();

        if ($imap === null) {
            $msg = "IMAP server not configured. Please review your email server settings and ensure an IMAP server is properly set up.";
            sendNotification($msg, 'error', true, null, route('admin.settings.advanced'));
            $this->error($msg);
            return 1; // Make sure to return if no IMAP server is found
        }

        try {
            $client = $this->trashMailService->connection(true, $imap);

            $folder = $client->getFolderByName('INBOX');
            $date = Carbon::now()->subDays(getSetting('imap_retention_days'))->format('d-M-Y');

            $messages = $folder->query()->before($date)->limit(20)->get();
            try {
                foreach ($messages as $message) {
                    $email = $message->getAttributes()['to'][0]->mail;
                    $extractEmail = $this->trashMailService->extractEmail($email);
                    $path = config('lobage.attachment_path') .  $extractEmail['domainPrefix'] . '/' . $extractEmail['prefix'];
                    removeFileOrFolder($path);
                    $message->delete(true);
                }
                $client->disconnect();
            } catch (\Exception $e) {
                $msg = "no more messages";
                $this->error($msg);
            }
            $this->info('The command executed successfully!');
        } catch (\Exception $e) {
            $msg = "Failed to connect to the IMAP server. Please verify your email server settings and try again. If the issue persists, consider clearing the cache.";
            sendNotification($msg, 'error', true, null, route('admin.settings.advanced'));
            $this->error($msg);
        }
    }
}
