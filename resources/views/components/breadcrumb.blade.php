@section('title', $title)

<div class="px-3 mb-4 justify-content-center align-items-center row ">
    <div class="{{ $col }} row row-cols-auto g-2 justify-content-between align-items-center">
        <div class="col">
            <h3>{!! $title !!}</h3>
            @if ($nav)
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <?php $link = '';
                        $count = 1; ?>
                        @foreach (request()->segments() as $segment)
                            @if ($count != $exclude)
                                @if (!is_numeric($segment))
                                    <?php $link .= '/' . $segment; ?>
                                    <li class="breadcrumb-item  @if (request()->segment(count(request()->segments())) == $segment) active @endif">
                                        @if (request()->segment(count(request()->segments())) != $segment)
                                            <a
                                                href="{{ url($link) }}">{{ $segment == env('ADMIN_PATH') ? 'Dashboard' : $segment }}</a>
                                        @else
                                            {{ $segment }}
                                        @endif
                                    </li>
                                @endif
                            @endif
                            <?php $count++; ?>
                        @endforeach
                    </ol>
                </nav>
            @endif
        </div>
        <div class="col">
            <div class="row g-2">
                {{ $slot }}
                @if ($goTo !== null || $backTo !== null)
                    <div class="col-auto">
                        @if ($goTo !== null)
                            <a href="{{ $goTo }}" class="btn btn-primary  h-100">
                                @if ($icon == null)
                                    <i class="fa-solid fa-plus mx-1"></i>
                                @else
                                    <i class="{{ $icon }}"></i>
                                @endif
                                {{ translate('New') }}
                            </a>
                        @else
                            <a href="{{ $backTo }}" class="btn btn-secondary h-100">
                                @if ($icon == null)
                                    <i class="fa-solid fa-arrow-left mx-1"></i>
                                @else
                                    <i class="{{ $icon }}"></i>
                                @endif
                                {{ translate('Back') }}
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
