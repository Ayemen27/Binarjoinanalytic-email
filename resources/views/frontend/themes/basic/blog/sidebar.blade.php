<div class="col-12 col-xl-4">

    @if ($ad = ad('post_sidebar'))
        <div class="card v2 mb-4">
            <div class="card-body p-4">
                {!! $ad !!}
            </div>
        </div>
    @endif
    <div class="card v2 mb-4">
        <div class="card-body p-4">
            <h5 class="mb-4">{{ translate('Popular Posts', 'general') }}</h5>
            <div class="posts">
                @foreach ($popular_posts as $post)
                    <!-- Start Post -->
                    <div class="post">
                        <a href="{{ route('posts', $post->slug) }}" class="link link-primary">
                            <!-- Start Post IMG -->
                            <img class="post-img" src="{{ asset($post->small_image) }}" alt="{{ $post->title }}">
                            <!-- End Post IMG -->
                            <div class="post-info">
                                <!-- Start Post Title -->
                                <h6 class="post-title">
                                    <a href="{{ route('posts', $post->slug) }}" class="link link-primary">
                                        {{ $post->title }}
                                    </a>
                                </h6>
                                <!-- End Post Title -->
                                <!-- Start Post Meta -->
                                <div class="post-meta">
                                    <!-- Start Post Meta Item -->
                                    <div class="post-meta-item">
                                        <time>{{ ToDate($post->created_at, 'M d, Y') }}</time>
                                    </div>
                                    <!-- End Post Meta Item -->
                                </div>
                                <!-- End Post Meta -->
                            </div>
                        </a>
                    </div>
                    <!-- End Post -->
                @endforeach
            </div>
        </div>
    </div>
    <div class="card v2 mb-4">
        <div class="card-body p-4">
            <h5 class="mb-3">{{ translate('Categories', 'general') }}</h5>
            <div class="tag-cloud">
                @foreach ($all_categories as $category)
                    <a href="{{ route('category', $category->slug) }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
