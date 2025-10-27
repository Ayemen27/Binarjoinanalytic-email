<?php

namespace App\Http\Controllers\Frontend;

use Auth;
use Carbon\Carbon;
use App\Models\Domain;
use App\Models\Message;
use App\Models\TrashMail;
use Illuminate\Http\Request;
use App\Services\TrashMailService;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\Cookie;

class ViewController extends Controller
{

    protected $trashMailService;

    public function __construct(TrashMailService $trashMailService)
    {
        $this->trashMailService = $trashMailService;
    }



    public function index($hash_id)
    {

        $id_saved = false;

        if (Auth::check()) {
            $check = Message::where('message_id', $hash_id)->where('user_id', Auth::user()->id)->count();
            if ($check > 0) {
                $id_saved = true;
            }
        }


        $message = Cache::remember($hash_id,  100000, function () use ($hash_id, $id_saved) {

            if ($id_saved) {
                return $this->trashMailService->parseEmlFile($hash_id);
            }

            return $this->trashMailService->getMessage($hash_id);
        });



        if ($message === null || $message == false) {
            abort(404);
        }


        setMetaTags($message['subject']);
        SEOMeta::addMeta('robots', 'noindex, nofollow');
        return view()->theme('view')->with('message', $message)->with('id_saved', $id_saved);
    }

    public function message($hash_id)
    {
        $message = Cache::remember($hash_id, $this->trashMailService->cacheExpired(), function () use ($hash_id) {
            return $this->trashMailService->getMessage($hash_id);
        });

        return $message['content'];
    }


    public function is_seen(Request $request)
    {
        if ($request->is_seen) {
            return true;
        }
        $data =  $this->trashMailService->getMessage($request->message_id);


        if ($data !== null || $data != false) {
            Cache::forget($request->message_id);

            return  Cache::remember($request->message_id, $this->trashMailService->cacheExpired(), function () use ($data) {
                return $data;
            });
        }

        return response()->json(['message' => translate('please try again', 'alerts')], 404);
    }



    public function download($hash_id, $file)
    {
        if (canUseFeature('attachments')) {

            $message = Cache::remember($hash_id, $this->trashMailService->cacheExpired(), function () use ($hash_id) {
                return $this->trashMailService->getMessage($hash_id);
            });

            $email = $message['to'];

            $extractEmail = $this->trashMailService->extractEmail($email);

            $domainPrefix = $extractEmail['domainPrefix'];
            $prefix = $extractEmail['prefix'];

            $path = 'temp/attachments/' . $domainPrefix . '/' . $prefix . '/' . $hash_id . '/' . $file;

            if (file_exists($path)) {
                return response()->download($path);
            } else {
                abort(404);
            }
        } else {
            abort(403);
        }
    }


    public function save($hash_id)
    {
        if (canUseFeature('messages')) {

            $message = Cache::remember($hash_id, $this->trashMailService->cacheExpired(), function () use ($hash_id) {
                return $this->trashMailService->getMessage($hash_id);
            });

            $email = $message['to'];

            $extractEmail = $this->trashMailService->extractEmail($email);

            $domainPrefix = $extractEmail['domainPrefix'];
            $prefix = $extractEmail['prefix'];

            $path = 'temp/attachments/' . $domainPrefix . '/' . $prefix . '/' . $hash_id . '/' . $file;

            if (file_exists($path)) {
                return response()->download($path);
            } else {
                abort(404);
            }
        } else {
            abort(403);
        }
    }


    public function delete($hash_id)
    {
        if ($hash_id) {
            $delete = $this->trashMailService->deleteMessage($hash_id);

            if ($delete != false) {

                $extractEmail = $this->trashMailService->extractEmail($delete);

                $domainPrefix = $extractEmail['domainPrefix'];
                $prefix = $extractEmail['prefix'];

                $path = 'temp/attachments/' . $domainPrefix . '/' . $prefix . '/' . $hash_id;

                if (file_exists($path)) {
                    File::deleteDirectory($path);
                }

                if (Cache::has($hash_id)) {
                    Cache::forget($hash_id);
                }
            }
            return redirect(route('index'));
        } else {
            abort(404);
        }
    }
}
