<?php

namespace App\Http\Controllers\Frontend;

use Auth;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Services\TrashMailService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class MessageController extends Controller
{

    public function index()
    {
        setMetaTags(translate('Messages', 'seo'));

        $user = Auth::user();
        $messages = Message::where('user_id', $user->id)->paginate(15);
        $count_messages = Message::where('user_id', $user->id)->count();
        $get_message_feature = getFeatureValue('messages');
        return view('frontend.user.messages.index', compact('messages', 'count_messages', 'get_message_feature'));
    }

    public function store(Request $request, TrashMailService $trashMailService)
    {

        if (is_demo()) {
            return response()->json(['message' => "Demo version some features are disabled"], 404);
        }


        if (auth()->check()) {

            if ($request->has('message_id')) {

                $hash_id = $request->message_id;

                $user = Auth::user();

                $check = Message::where('message_id', $hash_id)->where('user_id', $user->id)->first();

                if ($check) {

                    removeFileOrFolder($check->source);
                    $check->delete();
                    $user->subscription('main')->reduceFeatureUsage('messages', 1);
                    return response()->json(['favorited' => false, 'message' => translate('The Message removed successfully from Favorite', 'alerts')], 200);
                }

                if (!canUseFeature('messages')) {
                    return response()->json(['message' => translate('You have reached the limit for favoriting messages', 'alerts')], 404);
                }

                $save = $trashMailService->saveMessage($hash_id);
                if ($save) {
                    $message = Cache::remember($request->message_id, $trashMailService->cacheExpired(), function () use ($hash_id, $trashMailService) {
                        return $trashMailService->getMessage($hash_id);
                    });

                    $domain = Message::create([
                        "message_id" => $hash_id,
                        "from_email" => htmlspecialchars(strip_tags($message['from_email'])),
                        "subject" =>  htmlspecialchars(strip_tags($message['subject'])),
                        "from" =>  htmlspecialchars(strip_tags($message['from'])),
                        "to" =>  htmlspecialchars(strip_tags($message['to'])),
                        "receivedAt" =>  strip_tags($message['receivedAt']),
                        "source" =>  config('lobage.messages_eml_path') . $hash_id . ".eml",
                        'user_id' => $user->id
                    ]);

                    $user->subscription('main')->recordFeatureUsage('messages', 1);

                    return response()->json(['favorited' => true, 'message' => translate('The Message added successfully to Favorite.', 'alerts')], 200);
                }
            }

            return response()->json(['message' => translate('An error occurred. Please reload the page and try again', 'alerts')], 404);
        }
        return response()->json(['message' => translate('Please log in to continue', 'alerts')], 404);
    }



    public function destroy(Message $message)
    {
        $user = Auth::user();
        removeFileOrFolder($message->source);
        $message->delete();
        $user->subscription('main')->reduceFeatureUsage('messages', 1);
        showToastr(translate('The message has been deleted successfully', 'alerts'));
        return redirect(route('messages.index'));
    }
}
