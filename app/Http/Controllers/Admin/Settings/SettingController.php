<?php

namespace App\Http\Controllers\Admin\Settings;

use Carbon\Carbon;
use App\Models\Imap;
use Illuminate\Http\Request;
use App\Services\ImageService;
use App\Services\TrashMailService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Cviebrock\EloquentSluggable\Services\SlugService;


class SettingController extends Controller
{

    protected $trashMailService;

    public function __construct(TrashMailService $trashMailService)
    {
        $this->trashMailService = $trashMailService;
    }

    public function checkSlug(Request $request, $model)
    {
        // Determine the model class based on the $model parameter
        switch ($model) {
            case 'categories':
                $modelClass = \App\Models\BlogCategory::class;
                break;
            case 'posts':
                $modelClass = \App\Models\BlogPost::class;
                break;
            case 'pages':
                $modelClass = \App\Models\Page::class;
                break;
            default:
                return response()->json(['error' => 'Invalid model type']);
        }

        // Use the SlugService to create the slug
        $slug = SlugService::createSlug($modelClass, 'slug', $request->title);

        return response()->json(['slug' => $slug]);
    }



    public function upload(Request $request, ImageService $imageService)
    {

        if (!$request->hasFile('image')) {
            return response()->json([
                'uploaded' => false,
                'error' => ['message' => 'No file uploaded.']
            ], 400);
        }

        $file = $request->file('image');

        $validator = Validator::make($request->all(), [
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'uploaded' => false,
                'error' => ['message' => $validator->errors()->first()]
            ], 403);
        }


        try {
            $file = $imageService->storeImage($request->file('image'));  // Use the FileService
            return response()->json([
                'uploaded' => true,
                'url' => asset($file['filename']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'uploaded' => false,
                'error' => ['message' => 'No file uploaded.']
            ], 400);
        }
    }


    public function update()
    {

        if (version_compare(env('SITE_VERSION'), '2.0') == 0) {

            setSetting('enable_maintenance', 0);
            setSetting('maintenance_title', 'Site Under Maintenance');
            setSetting('maintenance_message', 'We are currently performing scheduled maintenance to improve our services. We apologize for any inconvenience this may cause and thank you for your understanding. Please check back shortly!');

            removeFileOrFolder(database_path('tempmail (8) - Copy.sql'));

            updateEnvFile('SITE_VERSION', '2.1');


            showToastr(__('Site updated successfully!'));
            return redirect(route('admin.dashboard'));
        }

        showToastr(__('No updates found.'), 'error');
        return redirect(route('admin.dashboard'));
    }
}
