<div class="offcanvas offcanvas-end " data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling">
    <div class="offcanvas-header">
        <h4 class="offcanvas-title" id="offcanvasScrollingLabel">{{ __("What's new?") }}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <div class="d-flex flex-column gap-3">
            @if (!empty($broadcasts) && is_array($broadcasts))
                @foreach ($broadcasts as $broadcast)
                    <div class="announcement">
                        <div class="announcement-header d-flex justify-content-between align-items-center gap-2 mb-3">
                            <div class="badge  {{ $broadcast['badge'] }}">{{ $broadcast['tags'] }}</div>
                            <time
                                class="announcement-time text-muted small">{{ toDiffForHumans($broadcast['created_at']) }}</time>
                        </div>
                        <div class="announcement-body">
                            <h5 class="announcement-title mb-3">{{ $broadcast['title'] }}</h5>
                            <div class="announcement-text">
                                @if (!empty($broadcast['image']))
                                    <img height="150" alt="{{ $broadcast['title'] }}" src="{{ $broadcast['image'] }}">
                                @endif
                                {!! $broadcast['content'] !!}
                                <div class="announcement-more">
                                    <button class="btn">
                                        {{ __('Read More') }} <i class="fa fa-chevron-down fa-sm ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

        </div>
    </div>
</div>
