<?php

namespace App\Jobs;

use Log;
use App\Models\Section;
use App\Models\Language;
use App\Models\Translate;
use App\Models\TranslationJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TranslateText implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $chunk;
    protected $targetLang;
    protected $jobId;

    public function __construct($chunk, $targetLang, $jobId)
    {
        $this->chunk = $chunk;
        $this->targetLang = $targetLang;
        $this->jobId = $jobId;
    }


    public function handle()
    {
        try {
            //MyLog('Processing translation for language: ' . $this->targetLang, ['chunk_size' => count($this->chunk)]);

            // Check if the language exists, if not create it
            $lang = Language::firstOrCreate(
                ['code' => $this->targetLang],
                [
                    'name' => config('languages')[$this->targetLang]['name'] ?? $this->targetLang,
                    'direction' => config('languages')[$this->targetLang]['direction'] == "ltr" ? 0 : 1,
                ]
            );

            if ($lang->wasRecentlyCreated) {
                // Copy translations and sections from default language
                $this->copyTranslationsAndSections();
            }

            // Collect data for translation
            $data = $this->prepareDataForTranslation();
            $translationJob = TranslationJob::where('job_id', $this->jobId)->first();

            if (empty($data)) {
                $this->translationJob($translationJob);
                $this->delete();
                return;
            }

            // Send data to translation API
            $response = Http::timeout(200)->post(config('lobage.translate') . 'translate', [
                'data' => $data,
                'key' => env('TRANSLATE_KEY'),
                'source_lang' => "en",
                'to_lang' => $this->targetLang,
                'type' => 'text',
                'site' => route('index'),
                'job_id' => $this->jobId,
            ]);

            if ($response->failed()) {
                $errorMessage = $response->body();
                $this->translationJob($translationJob);
                if (str_contains($errorMessage, 'Invalid or expired API key')) {
                    //Log::error('Translation API failed: Invalid or expired API key');
                    $this->fail(new \Exception('Invalid or expired API key'));
                    $this->delete();
                    $this->clearQueue($translationJob);
                    //dispatch(new ProcessFinalResultsJob($translationJob));
                    $translationJob->update(['message' => 'Invalid or expired API key']);
                    return;
                }

                $translationJob->update(['message' => 'Translation API failed']);
                $this->fail(new \Exception('No more credits available.'));
                $this->delete();
                ////\Log::error('Translation API failed: ' . $errorMessage);
                return;
            }

            ////\Log::info('response->successful()' . $response->body());


            if ($response->successful()) {

                $this->saveTranslations($response->json()['results']);
                $this->storeJobResults($response->json());
            }
        } catch (\Exception $e) {
            $translationJob = TranslationJob::where('job_id', $this->jobId)->first();
            $this->clearQueue($translationJob);
            $translationJob->update(['message' => 'Translation job failed , Please Try Again']);
            ////\Log::error('Translation job failed: ' . $e->getMessage());
            $this->fail($e);
        }
    }



    public function storeJobResults($response)
    {

        $translationJob = TranslationJob::where('job_id', $this->jobId)->first();

        $allResults = $translationJob->results ?? [];
        $allResults = array_merge($allResults, $response['results']);


        $total_characters = $translationJob->total_characters;
        $total_characters += $response['total_characters'];

        $translationJob->update(['results' => $allResults]);
        $translationJob->update(['total_characters' => $total_characters]);

        $this->translationJob($translationJob);
    }

    function translationJob($translationJob)
    {
        $translationJob->increment('processed_chunks');
        if ($translationJob->processed_chunks >= $translationJob->total_chunks) {
            $translationJob->update(['status' => 'completed']);
            $msg = "Translation process has been completed successfully. Total characters processed: {$translationJob->total_characters}.";
            sendNotification($msg, 'translate', true, null, route('admin.settings.languages.instant'));
        }
    }



    protected function copyTranslationsAndSections()
    {
        Translate::where('lang', getSetting('default_language'))->chunk(2, function ($translates) {
            foreach ($translates as $translate) {
                Translate::create([
                    'lang' => $this->targetLang,
                    'key' => $translate->key,
                    'collection' => $translate->collection,
                    'type' => $translate->type,
                    'value' => "",
                ]);
            }
        });

        Section::where('lang', getSetting('default_language'))->chunk(2, function ($sections) {
            foreach ($sections as $section) {
                Section::create([
                    'title' => $section->title,
                    'status' => $section->status,
                    'position' => $section->position,
                    'name' => $section->name,
                    'lang' => $this->targetLang,
                    'type' => $section->type,
                ]);
            }
        });
    }

    protected function prepareDataForTranslation()
    {
        $data = collect(); // Initialize an empty collection

        foreach ($this->chunk as $chunk) {
            $translate = Translate::where('lang', $this->targetLang)
                ->where('key', $chunk->key)
                ->where('collection', $chunk->collection)
                ->first();

            // Check if the translation exists and has an empty value
            if ($translate && empty($translate->value)) {
                //MyLog($translate->key);

                // Add the translation to the collection with the chunk value
                $data->push([
                    'collection' => $translate->collection,
                    'key' => $translate->key,
                    'value' => $chunk->value, // Use chunk value here
                    'lang' => $translate->lang,
                ]);
            }
        }

        ////\Log::error('prepareDataForTranslation : ', $data->toArray());

        return $data->toArray();
    }

    protected function saveTranslations($translations)
    {
        foreach ($translations as $translation) {
            Translate::updateOrCreate(
                [
                    'collection' => $translation['collection'],
                    'key' => $translation['key'],
                    'lang' => $this->targetLang,
                ],
                [
                    'value' => $translation['value'],
                    'type' => 1,
                ]
            );
        }
    }

    protected function clearQueue($translationJob)
    {
        // Define the queue name
        $queue = 'default'; // Change this if your queue name is different

        // Get all jobs in the queue
        $jobs = DB::table('jobs')->where('queue', $queue)->get();

        foreach ($jobs as $job) {
            // Decode the job payload to check its class
            $payload = json_decode($job->payload, true);

            if (isset($payload['data']['command']) && str_contains($payload['data']['command'], 'TranslateText')) {
                // Delete the job from the database
                DB::table('jobs')->where('id', $job->id)->delete();
                $this->translationJob($translationJob);
            }
        }
    }
}
