@extends('frontend.themes.basic.layouts.app')

@section('content')
    @include('frontend.themes.basic.partials.header', ['title' => $post->title])

    <!-- Start Section -->
    <section class="section">
        <div class="container">
            <div class="section-inner">
                <div class="row g-4">
                    <div class="col-12 col-xl-8">
                        <div class="card card-blog mb-4">
                            <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" />
                            <div class="card-body">
                                <h3 class="card-title mb-3">
                                    {{ $post->title }}
                                </h3>
                                <div class="post-meta">
                                    <span class="meta-item">
                                        <i class="fa fa-calendar"></i>
                                        {{ translate('Published on', 'general') }}
                                        {{ ToDate($post->created_at, 'M d, Y') }}
                                    </span>
                                    <span class="meta-item">
                                        <i class="fa fa-folder"></i>
                                        {{ translate('Category', 'general') }}: <a
                                            href="{{ route('category', $post->category->slug) }}">
                                            {{ $post->category->name }}</a>
                                    </span>
                                    <span class="meta-item">
                                        <i class="fa fa-eye"></i>
                                        {{ $post->views }} {{ translate('views', 'general') }}
                                    </span>
                                </div>

                                {!! ad('post_top') !!}

                                <p class="card-text lead">{!! $post->content !!}</p>

                                @if (!empty($post->tags))
                                    <div class="mb-2">
                                        <h5 class="card-title mb-3">
                                            {{ translate('Tags:', 'general') }}
                                        </h5>
                                        @foreach (explode(',', $post->tags) as $tag)
                                            <span class="meta-item">#{{ $tag }} </span>
                                        @endforeach
                                    </div>
                                @endif

                                {!! ad('post_bottom') !!}
                            </div>
                        </div>
                        @if (isPluginEnabled('facebook_comments'))
                            <div class="card mb-4 p-5">
                                <h5 class="card-title mb-3">
                                    {{ translate('Comments:', 'general') }}
                                </h5>

                                <div class="fb-comments" data-href="{{ route('posts', $post->slug) }}" data-width=""
                                    data-numposts="{{ plugin('facebook_comments')->number_of_comment->value }}"></div>

                                <div id="fb-root"></div>
                                <script async defer crossorigin="anonymous"
                                    src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v20.0&appId={{ plugin('facebook_comments')->app_id->value }}"
                                    nonce="zPZ8jOeh"></script>

                                <noscript>{{ translate('Please enable JavaScript to view the comments', 'general') }}</noscript>
                            </div>
                        @endif


                        @if (isPluginEnabled('disqus'))
                            <div class="card mb-4 p-5">
                                <h5 class="card-title mb-3">
                                    {{ translate('Comments:', 'general') }}
                                </h5>
                                <div id="disqus_thread"></div>
                                <script>
                                    var disqus_config = function() {
                                        this.page.url =
                                            "{{ route('posts', $post->slug) }}"; // Replace PAGE_URL with your page's canonical URL variable
                                        this.page.identifier =
                                            "{{ $post->slug }}"; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
                                    };

                                    (function() { // DON'T EDIT BELOW THIS LINE
                                        var d = document,
                                            s = d.createElement('script');
                                        s.src = 'https://{{ plugin('disqus')->shortname->value }}.disqus.com/embed.js';
                                        s.setAttribute('data-timestamp', +new Date());
                                        (d.head || d.body).appendChild(s);
                                    })();
                                </script>
                                <noscript>{{ translate('Please enable JavaScript to view the comments', 'general') }}</noscript>
                            </div>
                        @endif


                        @if (isPluginEnabled('graphcomment'))
                            <div class="card mb-4 p-5">
                                <h5 class="card-title mb-3">
                                    {{ translate('Comments:', 'general') }}
                                </h5>
                                <div id="graphcomment"></div>
                                <script type="text/javascript">
                                    /* - - - CONFIGURATION VARIABLES - - - */

                                    var __semio__params = {
                                        graphcommentId: "{{ plugin('graphcomment')->unique_id->value }}", // make sure the id is yours

                                        behaviour: {
                                            // HIGHLY RECOMMENDED
                                            uid: "{{ $post->slug }}", // uniq identifer for the comments thread on your page (ex: your page id)
                                        },

                                    }

                                    function __semio__onload() {
                                        __semio__gc_graphlogin(__semio__params)
                                    }

                                    (function() {
                                        var gc = document.createElement('script');
                                        gc.type = 'text/javascript';
                                        gc.async = true;
                                        gc.onload = __semio__onload;
                                        gc.defer = true;
                                        gc.src = 'https://integration.graphcomment.com/gc_graphlogin.js?' + Date.now();
                                        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(gc);
                                    })();
                                </script>
                                <noscript>{{ translate('Please enable JavaScript to view the comments', 'general') }}</noscript>
                            </div>
                        @endif
                    </div>
                    @include('frontend.themes.basic.blog.sidebar')
                </div>
            </div>
        </div>
    </section>
    <!-- End Section -->
@endsection
