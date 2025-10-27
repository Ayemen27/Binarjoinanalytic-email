@if ($faqs->count() > 0)
    <!-- Start Section -->
    <section class="section">
        <div class="container">
            <!-- Start Section Inner -->
            <div class="section-inner">
                <!-- Start Section Header -->
                <div class="section-header">
                    <div class="section-title-sm">
                        <span>{{ translate('Faqs', 'general') }}</span>
                    </div>
                    <h2 class="section-title">{{ translate('Faqs Title', 'general') }}</h2>
                    <p class="section-text lead mb-0">
                        {{ translate('Faqs Description', 'general') }}
                    </p>
                </div>
                <!-- End Section Header -->
                <!-- Start Section Body -->
                <div class="section-body">
                    <div class="faqs">
                        <div class="accordion" id="accordionExample">
                            @foreach ($faqs as $faq)
                                <div class="accordion-item" data-aos="fade-down">
                                    <h2 class="accordion-header" id="heading{{ $faq->id }}">
                                        <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $faq->id }}"
                                            aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                            aria-controls="collapse{{ $faq->id }}">
                                            <span>{{ $faq->title }}</span>
                                            <div class="accordion-button-icon">
                                                <i class="fa fa-chevron-down"></i>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $faq->id }}"
                                        class="accordion-collapse collapse @if ($loop->first) show @endif "
                                        aria-labelledby="heading{{ $faq->id }}"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            {{ $faq->content }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Section Inner -->
        </div>

    </section>
    <!-- End Section -->
@endif
