<?php

namespace App\Http\Controllers\Admin\Settings;

use Carbon\Carbon;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\Section;
use App\Models\Language;
use App\Models\Translate;
use App\Jobs\TranslateFaqs;
use App\Jobs\TranslateText;
use Illuminate\Http\Request;
use App\Jobs\TranslateFeature;
use App\Models\TranslationJob;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Services\TranslateFilterService;
use App\Http\Requests\Admin\AutoSendRequest;
use App\Http\Requests\Filter\TranslateFilterRequest;


class AutoTranslateController extends Controller
{
    public function index()
    {

        if (!isPluginEnabled('translate')) {
            showToastr(__('Please Active the Plugins'), 'info');
            return redirect(route('admin.plugins.settings', "translate"));
        }

        $response = Http::get(config('lobage.translate') . 'getKeyInfo', [
            'key' =>  env('TRANSLATE_KEY')
        ]);

        if ($response->successful()) {


            $isExpired = Carbon::now()->gt(Carbon::parse($response['data']['valid_until']));
            $languages = Language::with('translations')->get(); // Eager load translations
            $unique_groups = Translate::pluck('collection')->unique()->values();

            $translation_jobs = TranslationJob::orderBy('id', 'desc')->paginate(10);

            return view('admin.settings.languages.instant', compact('languages', 'unique_groups', 'response', "isExpired", 'translation_jobs'));
        } else {
            showToastr("Invalid key ", 'error');
            return redirect(route('admin.plugins.settings', "translate"));
        }
    }

    public function store(AutoSendRequest $request)
    {

        //$lang = "en";
        //dd(config('languages')[$lang]['direction'] == "ltr" ?  1 : 0);



        $totalChunks = 0;
        $chunksList = [];

        if ($request->has('options')) {


            foreach ($request->options as $option) {
                if ($option == "faqs") {
                    $faqs = Faq::where('lang', "en")->get();
                    $chunks = $faqs->chunk(5);
                    $chunksList[] = ['chunks' => $chunks, 'type' => 'faqs'];

                    $totalChunks += count($chunks) * (in_array('en', $request->lang) ? count($request->lang) - 1 : count($request->lang));
                }

                if ($option == "features") {
                    $features = Feature::where('lang', "en")->get();
                    $chunks = $features->chunk(5);
                    $chunksList[] = ['chunks' => $chunks, 'type' => 'features'];

                    $totalChunks += count($chunks) * (in_array('en', $request->lang) ? count($request->lang) - 1 : count($request->lang));
                }
            }
        }

        // Process collections
        if ($request->has('collections')) {
            $translations = Translate::whereIn('collection', $request->collections)->where('lang', "en")->get();
            $chunks = $translations->chunk(25);
            $chunksList[] = ['chunks' => $chunks, 'type' => 'collections'];

            $totalChunks += count($chunks) * (in_array('en', $request->lang) ? count($request->lang) - 1 : count($request->lang));
        }

        // Create a single translation job instance
        if ($totalChunks > 0) {
            $translationJob = TranslationJob::create([
                'job_id' => uniqid(),
                'total_chunks' => $totalChunks,
                'status' => 'processing',
            ]);

            // Dispatch jobs for each chunk type
            foreach ($request->lang as $lang) {
                if ($lang == "en") continue;

                foreach ($chunksList as $chunkData) {
                    foreach ($chunkData['chunks'] as $chunk) {
                        if ($chunkData['type'] == 'collections') {
                            TranslateText::dispatch($chunk, $lang, $translationJob->job_id);
                        } elseif ($chunkData['type'] == 'features') {
                            TranslateFeature::dispatch($chunk, $lang, $translationJob->job_id);
                        } elseif ($chunkData['type'] == 'faqs') {
                            TranslateFaqs::dispatch($chunk, $lang, $translationJob->job_id);
                        }
                    }
                }
            }
        }


        showToastr('Instant translation started, and we will inform you once the task is done.');

        return back();
    }


    public function results(Request $request, $translationJob)
    {
        $translationJob = TranslationJob::where('job_id', $translationJob)->firstOrFail();

        return response()->json([
            'start_date' => $translationJob->created_at,
            'end_date' => $translationJob->updated_at,
            'results' => $translationJob->results,
        ]);
    }



    public function stop(Request $request, TranslationJob $translationJob)
    {
        // Define the queue name
        $queue = 'default'; // Change this if your queue name is different

        // Get all jobs in the queue
        $jobs = DB::table('jobs')->where('queue', $queue)->get();

        foreach ($jobs as $job) {
            // Decode the job payload to check its class
            $payload = json_decode($job->payload, true);

            if (isset($payload['data']['command']) && str_contains($payload['data']['command'], $translationJob->job_id)) {
                // Delete the job from the database
                DB::table('jobs')->where('id', $job->id)->delete();
            }
        }

        $translationJob->update(['status' => 'canceling']);

        showToastr(__('Translation is being canceled.'));
        return back();
    }
}
