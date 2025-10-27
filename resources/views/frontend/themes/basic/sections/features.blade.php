@if ($features->count() > 0)
    <!-- Start Section -->
    <section class="section">
        <div class="container">
            <!-- Start Section Inner -->
            <div class="section-inner">
                <!-- Start Section Header -->
                <div class="section-header">
                    <div class="section-title-sm">
                        <span>{{ translate('Features', 'general') }}</span>
                    </div>
                    <h2 class="section-title">{{ translate('Features Title', 'general') }}</h2>
                    <p class="section-text lead mb-0">{{ translate('Features Description', 'general') }}</p>
                </div>
                <!-- End Section Header -->
                <!-- Start Section Body -->
                <div class="section-body">
                    <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3 justify-content-center g-4">
                        @foreach ($features as $feature)
                            <div class="col " data-aos="fade-up">
                                <!-- Start Feature -->
                                <div class="feat h-100">
                                    <!-- Start Feature Icon -->
                                    <div class="feat-icon">
                                        {!! $feature->icon !!}
                                    </div>
                                    <!-- End Feature Icon -->
                                    <!-- Start Feature Title -->
                                    <h3 class="feat-title">{{ $feature->title }}</h3>
                                    <!-- End Feature Title -->
                                    <!-- Start Feature Text -->
                                    <p class="feat-text lead">
                                        {{ $feature->content }}
                                    </p>
                                    <!-- End Feature Text -->
                                </div>
                                <!-- End Feature -->
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
