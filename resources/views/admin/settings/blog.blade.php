@extends('admin.layouts.admin')
@section('title', 'Blog Settings')

@section('content')
    <!-- Settings -->
    <div class="settings">
        @include('admin.partials.settings')
        <!-- Settings Content -->
        <div class="settings-content w-100">
            <div class="box">
                <h5 class="mb-4">{{ __('Blog Settings') }}</h5>


                <form action="{{ route('admin.settings.blog.update') }}" method="POST">
                    @csrf
                    <div class="row row-cols-1 g-3 mt-3">

                        <div class="col">
                            <x-label name="Enable Blog" for="enable_blog" />
                            <select class="select-input" hidden name="enable_blog" id="enable_blog">
                                <option {{ getSetting('enable_blog') == 1 ? 'selected' : '' }} value="1">
                                    {{ __('Enabled') }}
                                </option>
                                <option {{ getSetting('enable_blog') == 0 ? 'selected' : '' }} value="0">
                                    {{ __('Disabled') }}
                                </option>
                            </select>
                            <x-error name="enable_blog" />
                        </div>

                        <div class="col col-sm-6">
                            <x-input type="number" min="0" name='total_posts_per_page' label="Total Posts Per Page"
                                value="{{ getSetting('total_posts_per_page') }}" />
                        </div>

                        <div class="col col-sm-6">
                            <x-input type="number" min="0" name='total_popular_posts_home'
                                label="Total Popular Posts on Home Page"
                                value="{{ getSetting('total_popular_posts_home') }}" />
                        </div>

                        <div class="col">
                            <x-label name="Order Popular Posts By" for="popular_post_order_by" />
                            <select class="select-input" hidden name="popular_post_order_by" id="popular_post_order_by">
                                <option {{ getSetting('popular_post_order_by') == 'views' ? 'selected' : '' }}
                                    value="views">
                                    {{ __('Views') }}
                                </option>
                                <option {{ getSetting('popular_post_order_by') == 'title' ? 'selected' : '' }}
                                    value="title">
                                    {{ __('Title') }}
                                </option>
                                <option {{ getSetting('popular_post_order_by') == 'created_at' ? 'selected' : '' }}
                                    value="created_at">
                                    {{ __('Post Date') }}
                                </option>
                            </select>
                            <x-error name="popular_post_order_by" />
                        </div>
                        <div class="col">
                            <x-button class="btn-md w-100" />
                        </div>
                    </div>
                </form>
            </div>
            <!-- /Settings Content -->
        </div>
    </div>
    <!-- /Settings -->
@endsection
