@if (getSetting('enable_blog'))
    @if ($posts->count() > 0)
        <!-- Start Section -->
        <section class="section">
            <div class="container">
                <!-- Start Section Inner -->
                <div class="section-inner">
                    <!-- Start Section Header -->
                    <div class="section-header">
                        <div class="section-title-sm">
                            <span>{{ translate('Popular Posts', 'general') }}</span>
                        </div>
                        <h2 class="section-title">{{ translate('Popular Posts Text', 'general') }}</h2>
                        <p class="section-text lead mb-0">
                            {{ translate('Popular Posts Description ', 'general') }}
                        </p>
                    </div>
                    <!-- End Section Header -->
                    <!-- Start Section Body -->
                    <div class="section-body">
                        <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3 g-4 justify-content-center">
                            @foreach ($posts as $post)
                                <div class="col" data-aos="zoom-in-down">
                                    <a href="{{ route('posts', $post->slug) }}" class="card">

                                        <img loading="lazy" src="{{ asset($post->small_image) }}"
                                            alt="{{ $post->title }}" />
                                        <div class="card-body">
                                            <h3 class="card-title mb-3">
                                                {{ $post->title }}
                                            </h3>
                                            <p class="card-text lead">{{ $post->description }}</p>
                                            <p class="card-text">
                                                <small
                                                    class="text-muted">{{ ToDate($post->created_at, 'M d, Y') }}</small>
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- End Section Body -->
                </div>
                <!-- End Section Inner -->
            </div>
        </section>
        <!-- End Section -->
    @endif
@endif
