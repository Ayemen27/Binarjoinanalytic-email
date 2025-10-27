@extends('frontend.themes.basic.layouts.app')

@section('content')
    @include('frontend.themes.basic.partials.fullheader')

    @include('frontend.themes.basic.sections.mailbox')

    @foreach (getSections() as $section)
        @if ($section->type == 'theme')
            @include('frontend.themes.basic.sections.' . $section->name)
        @else
            <!-- Start Section -->
            <section class="section">
                <div class="container">
                    <!-- Start Section Inner -->
                    <div class="section-inner">
                        {!! $section->content !!}
                    </div>
                </div>
            </section>
        @endif
    @endforeach
@endsection
