<?php

namespace App\Providers;

use Log as MyLog;
use App\Models\Faq;
use App\Models\Log;
use App\Models\Menu;
use App\Models\Theme;
use App\Models\Domain;
use App\Models\Feature;
use App\Models\BlogPost;
use App\Models\Language;
use App\Models\Statistic;
use App\Models\TrashMail;
use App\Models\BlogCategory;
use App\Models\Notification;
use App\Listeners\StoreNewEmail;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Listeners\ClearEmailCookies;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Config;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (env('HTTPS_FORCE')) {
            //dd("HTTPS_FORCE");
            $this->app['request']->server->set('HTTPS', true);
        }

        //::forceRootUrl(config('app.url'));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        try {

            if (env('SYSTEM_INSTALLED') == 1) {


                Event::listen(
                    Login::class,
                    StoreNewEmail::class,
                );


                Event::listen(
                    Logout::class,
                    ClearEmailCookies::class,
                );



                Paginator::useBootstrap();
                Paginator::defaultView('pagination::bootstrap-4');
                Paginator::defaultSimpleView('pagination::simple-bootstrap-4');


                Config::set('laravellocalization.supportedLocales', getSupportedLanguages());

                $currentTheme = 'frontend.themes.' . config('lobage.current_theme') . '.';


                view()->composer($currentTheme . 'sections.change', function ($view) {

                    $free_domains = Domain::where('type', 0)->where('status', 1)->pluck('domain')->toArray();
                    $premium_domains = [];
                    $custom_domains = [];
                    $can_use_premium_domains = 0;


                    if (canUseFeature('premium_domains')) {
                        $premium_domains = Domain::where('type', 1)->where('status', 1)->pluck('domain')->toArray();
                        $can_use_premium_domains = 1;
                    } else {
                        $domains = Domain::where('type', 1)->where('status', 1)->pluck('domain')->toArray();
                        $premium_domains = array_map(function ($domain) {
                            return obfuscateDomain($domain);
                        }, $domains);
                    }

                    if (canUseFeature('domains', false) && auth()->check()) {
                        $user = Auth::user();
                        $custom_domains = Domain::where('user_id', $user->id)->where('status', 1)->pluck('domain')->toArray();
                    }


                    $view->with(compact('free_domains', 'premium_domains', 'custom_domains', 'can_use_premium_domains'));
                });


                view()->composer($currentTheme . 'sections.mail', function ($view) {

                    $emails =  TrashMail::withTrashed()->count();

                    $emails_count = $emails + getSetting('fake_emails');

                    $messages = Statistic::where("key", "messages")->count();

                    $messages_count = $messages + getSetting('fake_messages');

                    $view->with("emails_created", number_format($emails_count, 0, '.', ','))
                        ->with("messages_created", number_format($messages_count, 0, '.', ','));
                });


                view()->composer($currentTheme . 'partials.navbar', function ($view) {

                    $menus = Menu::where("lang", getCurrentLang())->where('type', 0)->where('parent', 0)->orderBy('position', 'ASC')->get();

                    $view->with("menus", $menus);
                });




                view()->composer([$currentTheme . 'layouts.app', 'frontend.user.layouts.*'], function ($view) {

                    $theme = Theme::where('status', '1')
                        ->select('custom_css', 'custom_js')
                        ->first();

                    $custom_css = $theme->custom_css;
                    $custom_js = $theme->custom_js;

                    $view->with("custom_css", $custom_css)->with("custom_js", $custom_js);
                });


                view()->composer($currentTheme . 'partials.footer', function ($view) {

                    $menus = Menu::where("lang", getCurrentLang())->where('type', 1)->where('parent', 0)->orderBy('position', 'ASC')->get();

                    $view->with("menus", $menus);
                });


                view()->composer($currentTheme . 'sections.features', function ($view) {

                    $features = Feature::where("lang", getCurrentLang())->get();

                    $view->with("features", $features);
                });


                view()->composer($currentTheme . 'sections.faqs', function ($view) {


                    $faqs = Faq::where("lang", getCurrentLang())->get();

                    $view->with("faqs", $faqs);
                });


                view()->composer($currentTheme . 'sections.posts', function ($view) {

                    $limit = getSetting('total_popular_posts_home');
                    $posts = BlogPost::where("lang", getCurrentLang())
                        ->where('status', 1)->orderBy(getSetting('popular_post_order_by'), 'desc')->limit($limit)->get();
                    $view->with("posts", $posts);
                });


                view()->composer($currentTheme . 'blog.sidebar', function ($view) {

                    $posts =  BlogPost::popular()->get();

                    $categories = BlogCategory::where("lang", getCurrentLang())->get();

                    $view->with("popular_posts", $posts)->with("all_categories", $categories);
                });



                // User panel

                view()->composer('frontend.user.partials.sidebar', function ($view) {

                    $menus = Menu::where("lang", getCurrentLang())->where('type', 2)->where('parent', 0)->orderBy('position', 'ASC')->get();

                    $view->with("menus", $menus);
                });

                // Admin

                view()->composer('admin.layouts.admin', function ($view) {

                    $notifications = Notification::where("to_admin", 1)->orderBy('created_at', 'DESC')->take(10)->get();

                    $notifications_count = Notification::where("to_admin", 1)->where('is_read', 0)->count();

                    $notifications_count_all = Notification::where("to_admin", 1)->count();

                    $view->with("notifications", $notifications)->with("notifications_count", $notifications_count)->with("notifications_count_all", $notifications_count_all);
                });



                view()->composer(['admin.partials.whats-new', 'admin.partials.nav'], function ($view) {


                    $cacheKey = 'broadcasts_data';

                    // Attempt to get data from the cache
                    $newData = Cache::remember($cacheKey, now()->addDay(), function () {
                        // Fetch data from external API
                        $response = Http::get(config('lobage.api') . 'get-broadcasts?id=' . config('lobage.id'));

                        // If the primary response is unsuccessful or null, try the backup
                        if (!$response->successful() || is_null($response->json())) {
                            // Use the backup API if the primary request fails
                            $response = Http::get(config('lobage.api_v2') . 'get-broadcasts?id=' . config('lobage.id'));
                        }

                        // Return the final response data (either primary or backup)
                        return $response->successful() ? $response->json() : null;
                    });

                    // If the data is not available or failed to fetch, return early
                    if (!$newData || $newData['success'] !== true) {
                        return null;
                    }

                    $existingSlugs = Cache::remember('existing_slugs_release', now()->addHours(4), function () {
                        // Fetch existing slugs from the database and cache them
                        return Log::where('key', 'release')->pluck('value')->toArray();
                    });

                    // Find new slugs that don't exist in the logs
                    $newSlugs = array_diff(array_column($newData['data'], 'slug'), $existingSlugs);

                    // Count the number of new slugs
                    $newSlugsCount = count($newSlugs);

                    #dd($newData['data']);

                    $view->with("newSlugsCount", $newSlugsCount)->with('broadcasts', $newData['data'])
                        ->with('broadcastsCount', count($newData['data']));
                });
            }
        } catch (\Exception $e) {
        }
    }
}
