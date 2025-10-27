<?php

use App\Models\TranslationJob;
use Illuminate\Support\Facades\Route;




Route::namespace('Admin')
    ->prefix(config('lobage.admin'))
    ->name('admin.')
    ->group(function () {

        Route::namespace('Auth')->middleware(['guest:admin'])->group(function () {
            Route::get('/login', 'LoginController@showLoginForm')->name('login');
            Route::post('/login', 'LoginController@login');
            Route::post('logout', 'LoginController@logout')->name('logout')->withoutMiddleware('guest:admin');
            // Admin Password Reset
            Route::post('/password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
            Route::get('/password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
            Route::post('/password/reset', 'ResetPasswordController@reset')->name('password.update');;
            Route::get('/password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
        });


        Route::middleware(['demo', 'auth:admin', 'admin'])->group(function () {
            Route::get('/', 'DashboardController@index')->name('dashboard');
            Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
            Route::get('/get-data', 'DashboardController@getData');



            // Blog
            Route::prefix('blog')->name('blog.')->group(function () {
                Route::get('/categories/all', 'BlogPostController@getCategoriesDatatable')->name('category.all');
                Route::resource('categories', 'BlogCategoryController');
                Route::get('/posts/getcategory/{lang}', 'BlogPostController@getCategory')->name('posts.getCategory');
                Route::resource('posts', 'BlogPostController');
            });

            // Pages
            Route::resource('pages', 'PageController');

            //Settings
            Route::prefix('settings')->namespace('Settings')->name('settings.')->group(function () {

                Route::get('/', 'SettingController@index')->name('index');





                Route::get('/general', 'GeneralController@index')->name('general');
                Route::post('/general', 'GeneralController@update')->name('general.update');

                Route::get('/advanced', 'AdvancedController@index')->name('advanced');
                Route::post('/advanced', 'AdvancedController@update')->name('advanced.update');
                Route::post('/check/imap', 'AdvancedController@check_imap')->name('check.imap');
                Route::post('/check/imap2', 'AdvancedController@check_imap2')->name('check.imap2');

                Route::get('/appearance', 'AppearanceController@index')->name('appearance');
                Route::post('/appearance', 'AppearanceController@update')->name('appearance.update');

                Route::get('/smtp', 'SMTPController@index')->name('smtp');
                Route::post('/smtp', 'SMTPController@update')->name('smtp.update');
                Route::post('/smtp/send', 'SMTPController@send')->name('smtp.send');


                Route::resource('/emails', 'EmailController');

                Route::get('/languages/instant-translation', 'AutoTranslateController@index')->name('languages.instant');
                Route::post('/languages/instant-translation', 'AutoTranslateController@store')->name('languages.instant.store');
                Route::post('/languages/stop/{translationJob}', 'AutoTranslateController@stop')->name('languages.instant.stop');
                Route::get('/job-results/{translationJob}', 'AutoTranslateController@results')->name('languages.instant.results');


                Route::redirect('/languages/translate/', '/admin/settings/languages');
                Route::resource('/languages', 'LanguageController');
                Route::get('/languages/translate/{code}', 'LanguageController@translate')->name('languages.translate');
                Route::post('/languages/translate/{code}', 'LanguageController@update_translate')->name('languages.update_translate');


                Route::get('/profile', 'ProfileController@index')->name('profile');
                Route::post('/profile/update', 'ProfileController@update')->name('profile.update');
                Route::post('/profile/password/update', 'ProfileController@changePassword')->name('password.update');


                Route::get('/api', 'ApiController@index')->name('api');
                Route::post('/api', 'ApiController@update')->name('api.update');



                Route::get('/license', 'LicenseController@index')->name('license');
                Route::post('/license', 'LicenseController@update')->name('license.update');


                Route::get('/captcha', 'CaptchaController@index')->name('captcha');
                Route::post('/captcha', 'CaptchaController@update')->name('captcha.update');


                Route::get('/cron-job', 'CronJobController@index')->name('cronjob');
                Route::post('/cron-job', 'CronJobController@update')->name('cronjob.update');

                Route::get('/cache', 'CacheController@index')->name('cache');
                Route::get('/cache/clear', 'CacheController@clear')->name('cache.clear');

                Route::get('/system-info', 'SystemInfoController@show')->name('system');


                Route::get('/blog', 'BlogController@index')->name('blog');
                Route::post('/blog', 'BlogController@update')->name('blog.update');

                Route::resource('/seo', 'SeoController');
                Route::resource('/ads', 'AdController')->only([
                    'index',
                    'edit',
                    'update'
                ]);

                Route::get('/plans', 'PlanController@index')->name('plans');
                Route::post('/plans', 'PlanController@update')->name('plans.update');


                Route::get('/maintenance', 'MaintenanceController@index')->name('maintenance');
                Route::post('/maintenance', 'MaintenanceController@update')->name('maintenance.update');



                //Upload Image To server
                Route::post('ckeditor/image_upload', 'SettingController@upload')->name('ckeditor');
                //Check slug For posts , categories and pages
                Route::get('/{model}/checkslug', 'SettingController@checkSlug')->name('checkslug');
            });



            // Domains
            Route::resource('domains', 'DomainController');

            // Users
            Route::resource('users', 'UserController');

            //Admins
            Route::resource('admins', 'AdminController');

            // Themes
            Route::get('/themes', 'ThemeController@index')->name('themes.index');
            Route::get('/themes/create', 'ThemeController@create')->name('themes.create');
            Route::get('/themes/active/{theme}', 'ThemeController@active')->name('themes.active');
            Route::post('themes/store', 'ThemeController@store')->name('themes.store');

            Route::get('/themes/appearance', 'ThemeController@appearance')->name('themes.appearance');
            Route::post('/themes/appearance', 'ThemeController@update_appearance')->name('themes.appearance.update');

            Route::get('/themes/advanced', 'ThemeController@advanced')->name('themes.advanced');
            Route::post('/themes/advanced', 'ThemeController@update_advanced')->name('themes.advanced.update');

            Route::get('/themes/about', 'ThemeController@about')->name('themes.about');


            //Sections
            Route::resource('/features', 'FeatureController');

            Route::post('faqs/position', 'FaqController@update_position')->name('faqs.update_position');
            Route::resource('/faqs', 'FaqController');

            Route::post('sections/position', 'SectionController@update_position')->name('sections.update_position');
            Route::resource('/sections', 'SectionController');

            Route::redirect('/menus', '/admin/menus/header');


            Route::post('menus/position', 'MenuController@update_position')->name('menus.update_position');
            Route::get('menus/header', 'MenuController@header')->name('menus.header');
            Route::get('menus/header/create', 'MenuController@create_header')->name('menus.header.create');

            Route::get('menus/footer', 'MenuController@footer')->name('menus.footer');
            Route::get('menus/footer/create', 'MenuController@create_footer')->name('menus.footer.create');

            Route::get('menus/sidebar', 'MenuController@sidebar')->name('menus.sidebar');
            Route::get('menus/sidebar/create', 'MenuController@create_sidebar')->name('menus.sidebar.create');

            Route::post('menus', 'MenuController@store')->name('menus.store');
            Route::get('menus/footer/{menu}/edit', 'MenuController@edit')->name('menus.footer.edit');
            Route::get('menus/header/{menu}/edit', 'MenuController@edit')->name('menus.header.edit');
            Route::get('menus/sidebar/{menu}/edit', 'MenuController@edit')->name('menus.sidebar.edit');

            Route::put('menus/{menu}', 'MenuController@update')->name('menus.update');
            Route::delete('menus/{menu}', 'MenuController@destroy')->name('menus.destroy');

            // plugins
            Route::resource('plugins', 'PluginController');

            Route::post('plugins/{plugin}', 'PluginController@uninstall')->name('plugins.uninstall');
            Route::get('plugins/{plugin}', 'PluginController@edit')->name('plugins.settings');
            Route::post('plugins/sitemap/update', 'PluginController@sitemap')->name('plugins.sitemap');


            Route::get('/notifications', 'NotificationController@index')->name('notifications.index');
            Route::get('/notifications/mark-all-read', 'NotificationController@markAllAsRead')->name('notifications.markAllRead');
            Route::get('/notifications/{notification}', 'NotificationController@show')->name('notifications.show');
            Route::delete('/notifications', 'NotificationController@delete')->name('notifications.delete');

            Route::post('broadcasts', 'ReleaseController@get_data');
            //Route::get('ads', 'ReleaseController@index');

            Route::namespace('Settings')->group(function () {
                Route::post('/image/upload', 'SettingController@upload')->name('image.upload');
                Route::get('/update', 'SettingController@update');
            });
        });
    });
