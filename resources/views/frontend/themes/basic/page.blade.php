@extends('frontend.themes.basic.layouts.app')

@section('content')
    @include('frontend.themes.basic.partials.header', ['title' => $page->title])
    <!-- Start Section -->
    <section class="section">
        <div class="container">
            <div class="section-inner">
                @if ($ad = ad('mailbox_top'))
                    <div class="ad ad-h mx-auto mb-4">
                        {!! $ad !!}
                    </div>
                @endif
                <div class="viewbox-container">
                    @if ($ad = ad('mailbox_left'))
                        <div class="ad ad-v me-lg-4 mb-4 mb-xl-0">
                            {!! $ad !!}
                        </div>
                    @endif
                    <div class="box-content">
                        <div class="viewbox">
                            <!-- Start Viewbox Header -->
                            <div class="viewbox-header p-3">
                                <h5 class="m-0">{{ $page->title }}</h5>
                            </div>
                            <!-- End Viewbox Header -->
                            <!-- Start Viewbox Body -->
                            <div class="viewbox-body v2 p-4">
                                {!! $page->content !!}
                                <br>
                                <p class="card-text"><small class="text-muted">{{ translate('Last updated', 'general') }}
                                        {{ ToDiffForHumans($page->updated_at) }}</small></p>
                            </div>
                        </div>
                        @if ($ad = ad('mailbox_bottom'))
                            <div class="ad ad-h mx-auto mt-3">
                                {!! $ad !!}
                            </div>
                        @endif
                    </div>
                    @if ($ad = ad('mailbox_right'))
                        <div class="ad ad-v ms-lg-4 mt-4 mt-xl-0">
                            {!! $ad !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- End Section -->
@endsection
