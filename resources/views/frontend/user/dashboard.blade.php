@extends('frontend.user.layouts.app')
@section('content')
    <div>
        <div class="mb-3">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">
                <div class="col">
                    <div class="box h-100">
                        <div class="counter">
                            <div class="counter-info">
                                <p class="counter-amount">{{ $emails }}</p>
                                <h6 class="counter-title">{{ translate('Emails Created') }}</h6>
                            </div>
                            <div class="counter-icon">
                                <i class="fa-solid fa-at"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="box h-100">
                        <div class="counter">
                            <div class="counter-info">
                                <p class="counter-amount">{{ $history->count() }}</p>
                                <h6 class="counter-title">{{ translate('Email History') }}</h6>
                            </div>
                            <div class="counter-icon">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="box h-100">
                        <div class="counter">
                            <div class="counter-info">
                                <p class="counter-amount">{{ $messages }}</p>
                                <h6 class="counter-title">{{ translate('Favorite Messages') }}</h6>
                            </div>
                            <div class="counter-icon">
                                <i class="fa-solid fa-heart"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="box h-100">
                        <div class="counter">
                            <div class="counter-info">
                                <p class="counter-amount">{{ $domains }}</p>
                                <h6 class="counter-title">{{ translate('Domains') }}</h6>
                            </div>
                            <div class="counter-icon">
                                <i class="fa-solid fa-globe"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <div class="row g-3">
                <div class="col-12 col-xxl-8">
                    <div class="box h-100">
                        <div class="box-header mb-4">
                            <h5 class="mb-0">{{ translate('Emails Created By You') }}</h5>
                        </div>
                        <div class="box-body">
                            <div class="dashboard-chart">
                                <canvas id="email-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xxl-4">
                    <div class="box h-100 box-custom">
                        <div class="box-header">
                            <h5 class="mb-0">{{ translate('Last Email History') }}</h5>
                        </div>
                        <div class="box-body">
                            @foreach ($history->take(7) as $item)
                                <div class="items">
                                    <!-- List Item -->
                                    <div class="item d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">

                                            <div>
                                                <a href="#" class="item-title d-block fw-500 mb-1">
                                                    {{ $item->email }}
                                                </a>
                                                <p class="item-text text-muted mb-0">
                                                    {{ toDiffForHumans($item->created_at) }}</p>
                                            </div>
                                        </div>
                                        <div class="ms-3">
                                            <form action="{{ route('choose') }}" method="POST">
                                                @csrf
                                                @php
                                                    $name = explode('@', $item->email)[0];
                                                    $domain = explode('@', $item->email)[1];
                                                @endphp
                                                <input type="hidden" name="domain" value="{{ $domain }}" />
                                                <input type="hidden" name="name" value="{{ $name }}" />
                                                <button type="submit" class="btn btn-primary cp-x-2">
                                                    <i class="fa-solid fa-envelope-circle-check"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- /List Item -->

                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/vendor/chart.min.js') }}"></script>
    <script src="{{ asset('assets/js/chart.users.js') }}"></script>
@endpush
