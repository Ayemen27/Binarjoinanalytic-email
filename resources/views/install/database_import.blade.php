@extends('install.layout')
@section('title', 'Database Import')
@section('content')
    <div class="steps-content">
        <div class="steps-body">
            <div class="col-lg-9 col-xl-8 col-xxl-6 mx-auto">
                <div class="mb-4">
                    <h2 class="fw-light mb-4">{{ __('Database Import') }}</h2>
                    <p class="fw-light text-muted mx-auto mb-0">
                        {{ __('Click the button below to import the database.') }}
                    </p>
                </div>
                <div class="text-start">
                    <form action="{{ route('install.databaseImport.post') }}" method="POST">
                        @csrf
                        <div class="row row-cols-1 g-3">
                            <div class="col">
                                <label for="db_type" class="form-label">{{ __('Database Type') }}</label>
                                <select name="db_type" id="db_type" class="form-select" required>
                                    <option value="pgsql" {{ env('DB_CONNECTION') == 'pgsql' ? 'selected' : '' }}>PostgreSQL</option>
                                    <option value="mysql" {{ env('DB_CONNECTION') == 'mysql' ? 'selected' : '' }}>MySQL</option>
                                </select>
                                <small class="text-muted">{{ __('Select the database type that matches your configuration') }}</small>
                            </div>
                            <div class="col">
                                <button class="btn btn-primary btn-md w-100">{{ __('Import Database') }} <i
                                        class="fas fa-arrow-right"></i></button>
                            </div>
                            @error('error')
                                <div class="col">
                                    <div class="alert alert-danger">
                                        {{ $errors->first('error') }}
                                    </div>
                                </div>
                            @enderror

                            @error('skip')
                                <div class="col">
                                    <div class="alert alert-danger">
                                        {{ $errors->first('skip') }}
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="alert alert-important alert-warning alert-dismissible br-dash-2" role="alert">
                                        {{ __('You can download the SQL file and import it manually to your server. After that, click skip.') }}
                                    </div>
                                </div>

                                <iframe width="560" height="315"
                                    src="https://www.youtube.com/embed/jW5lrS6EUPM?si=XLOwCHVafDypSawb"
                                    title="YouTube video player" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>


                                <div class="col">
                                    <a href="{{ route('install.download') }}"
                                        class="btn btn-secondary btn-md w-100">{{ __('Download SQL File') }} </a>
                                </div>

                                <div class="col">
                                    <a href="{{ route('install.skip') }}"
                                        class="btn btn-success btn-md w-100">{{ __('Skip') }} </a>
                                </div>
                            @enderror

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
