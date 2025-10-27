@extends('frontend.themes.basic.layouts.app')

@section('content')
    @include('frontend.themes.basic.partials.header', ['title' => $message['subject']])

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
                            <div class="viewbox-header">
                                <input type="hidden" id="message_id" value=" {{ $message['id'] }}">
                                <input type="hidden" id="is_seen" value=" {{ $message['is_seen'] }}">
                                <div class="row g-2 align-items-center">
                                    <div class="col-auto">
                                        <a href="{{ route('index') }}" class="btn-icon" data-toggle="tooltip"
                                            data-placement="top" title="{{ translate('Back To Home', 'general') }}">
                                            <i class="fa-solid fa-left-long"></i>
                                            <i class="fa-solid fa-right-long d-none"></i>
                                        </a>
                                    </div>
                                    <div class="col">
                                        {{ $message['from'] }}
                                        <div class="text-muted">
                                            {{ $message['from_email'] }}
                                        </div>
                                    </div>
                                    <div class="col-auto text-muted">
                                        {{ ToDate($message['receivedAt'], 'M d, Y, h:i A') }}
                                        ({{ ToDiffForHumans($message['receivedAt']) }})
                                    </div>
                                    <div class="col-auto">

                                        @if ($id_saved)
                                            <button class="btn-icon" id="save_message" type="button" data-toggle="tooltip"
                                                data-placement="top"
                                                title="{{ translate('Remove from favorites', 'general') }}">
                                                <i class="fa-solid fa-heart star-icon-color "></i>
                                            </button>
                                        @else
                                            <button class="btn-icon" id="save_message" type="button" data-toggle="tooltip"
                                                data-placement="top" title="{{ translate('Add to favorites', 'general') }}">
                                                <i class="fa-regular fa-heart"></i>
                                            </button>
                                        @endif

                                    </div>
                                    <div class="col-auto">
                                        <form id="delete-message-form-{{ $message['id'] }}"
                                            action="{{ route('message.delete', $message['id']) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                        </form>

                                        <button class="btn-icon" data-toggle="tooltip" data-placement="top"
                                            title="{{ translate('Delete Message', 'general') }}"
                                            onclick="event.preventDefault(); document.getElementById('delete-message-form-{{ $message['id'] }}').submit();">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>

                                </div>
                            </div>
                            <!-- End Viewbox Header -->
                            <!-- Start Viewbox Body -->
                            <div class="viewbox-body">
                                @if ($message['attachments'])
                                    <div class="viewbox-attachments">
                                        @if (!canUseFeature('attachments'))
                                            <div class="can_see_attachments">
                                                <div>
                                                    <h4>{{ translate('Sign up to download attachments', 'general') }}</h4>
                                                    <a href="{{ route('register') }}"
                                                        class="btn btn-secondary">{{ translate('Register', 'general') }}</a>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="row row-cards">
                                            @foreach ($message['attachments'] as $attachment)
                                                <div class="col-sm-12 col-md-6 col-lg-4">
                                                    <div class="card attachment-file">
                                                        <div class="card-body">
                                                            <div class="row align-items-center">
                                                                <div class="col-auto">
                                                                    <img src="{{ asset('assets/img/file.svg') }}">
                                                                </div>
                                                                <div class="col overflow-hidden p-0">
                                                                    <div class="file-name">
                                                                        {{ $attachment['name'] }}
                                                                    </div>
                                                                    <div class="text-muted">
                                                                        {{ formatSizeUnits($attachment['size']) }}
                                                                    </div>
                                                                </div>
                                                                <div class="col-auto">
                                                                    <a href="{{ $attachment['url'] }}" target="_blank"
                                                                        data-toggle="tooltip" data-placement="top"
                                                                        title="{{ translate('Download', 'general') }}">
                                                                        <i class="fa-solid fa-download"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                <div class="pt-0">
                                    <iframe frameborder="0" scrolling="yes" width="100%"
                                        src="{{ route('message', $message['id']) }}" id="myContent"></iframe>
                                </div>
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
