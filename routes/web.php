<?php

use Illuminate\Support\Facades\Route;


require __DIR__ . '/install.php';

Route::middleware('not-installed')->group(function () {
    // Include admin and authentication routes
    require __DIR__ . '/admin.php';
    require __DIR__ . '/auth.php';

    if (env('MAINTENANCE_MODE')) {
        Route::get('/maintenance', function () {
            return view('errors.maintenance');  // This will show your maintenance page
        })->name('maintenance');
    }


    // Frontend routes with verified middleware
    Route::namespace('Frontend')->middleware(['maintenance', 'verified'])->group(function () {
        // Cronjob route
        Route::get('cronjob', 'CronJobController@execute')->name('cronjob');

        // Localized routes with middleware
        Route::group([
            'prefix' => LaravelLocalization::setLocale(),
            'middleware' => ['mcamara', 'checkBan']
        ], function () {
            Route::get('/', 'HomeController@index')->name('index');

            // Blog routes
            Route::group(['middleware' => ['blog.enabled']], function () {
                Route::get('/blog', 'BlogController@index')->name('blog');
                Route::get('/post/{slug}', 'BlogController@posts')->name('posts');
                Route::get('/category/{slug}', 'BlogController@category')->name('category');
            });

            // Page and contact routes
            Route::get('/page/{slug}', 'PageController@index')->name('page');
            Route::get('/contact', "ContactController@index")->name('contact.index');
            Route::post('/contact', "ContactController@store")->name('contact.store')->middleware('demo');

            Route::get('/view/{hash_id}', "ViewController@index")->name('view');
        });

        // Authenticated user routes
        Route::group([
            'prefix' => LaravelLocalization::setLocale(),
            'middleware' => ['mcamara', 'checkBan', 'auth:web']
        ], function () {
            Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
            Route::resource('domains', 'DomainController')->only(['index', 'destroy', 'create', 'store'])->middleware('demo');
            Route::resource('messages', 'MessageController')->only(['index', 'destroy'])->middleware('demo');
            Route::get('/settings', "ProfileController@index")->name('settings');
            Route::get('/get-data', 'DashboardController@getData');
            Route::post('/choose', "TrashmailController@choose")->name('choose');
        });


        Route::post('messages', 'MessageController@store')->name('messages.store');

        // Trashmail routes
        Route::post('/get_messages', "TrashmailController@get_messages");
        Route::post('/delete_emails', "TrashmailController@delete_emails");
        Route::post('/delete', "TrashmailController@delete");
        Route::post('/change', "TrashmailController@change");
        Route::post('/is-seen', "ViewController@is_seen");
        Route::post('/change_email', "TrashmailController@change_email");

        // View and download routes

        Route::get('/msg/{hash_id}', 'ViewController@message')->name("message");
        Route::post('/delete/message/{hash_id}', 'ViewController@delete')->name("message.delete");
        Route::get('/d/{hash_id}/{file?}', 'ViewController@download');
        Route::get('/go-to-email/{token}', 'TrashmailController@getEmailFromToken');
    });

    // System clear route
});
