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
                    <form action="{{ route('install.databaseImport.post') }}" method="POST" id="importForm">
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
                            
                            <!-- Progress Bar Section -->
                            <div class="col" id="progressSection" style="display: none;">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="text-center mb-3">
                                            <div class="spinner-border text-primary mb-2" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <h5 class="mb-1">{{ __('Importing database...') }}</h5>
                                            <p class="text-muted small mb-0">{{ __('Please wait, this process may take a few minutes...') }}</p>
                                        </div>
                                        <div class="progress" style="height: 25px;">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                                 role="progressbar" 
                                                 id="progressBar"
                                                 style="width: 0%;" 
                                                 aria-valuenow="0" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                                <span class="fw-bold" id="progressText">0%</span>
                                            </div>
                                        </div>
                                        <div class="mt-2 text-center">
                                            <small class="text-muted" id="progressStatus">{{ __('Starting import...') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col" id="submitButton">
                                <button type="submit" class="btn btn-primary btn-md w-100">{{ __('Import Database') }} <i
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('importForm');
    const progressSection = document.getElementById('progressSection');
    const submitButton = document.getElementById('submitButton');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const progressStatus = document.getElementById('progressStatus');
    
    const statusMessages = [
        "{{ __('Verifying database connection...') }}",
        "{{ __('Reading SQL file...') }}",
        "{{ __('Creating tables...') }}",
        "{{ __('Importing data...') }}",
        "{{ __('Adding primary keys...') }}",
        "{{ __('Adding indexes...') }}",
        "{{ __('Updating sequences...') }}",
        "{{ __('Finalizing import...') }}"
    ];
    
    form.addEventListener('submit', function(e) {
        // إخفاء زر الإرسال وإظهار شريط التقدم
        submitButton.style.display = 'none';
        progressSection.style.display = 'block';
        
        // عرض الرسالة الأولى مباشرة
        progressStatus.textContent = statusMessages[0];
        
        // محاكاة التقدم
        let progress = 0;
        let messageIndex = 0;
        
        const interval = setInterval(function() {
            // زيادة تدريجية صغيرة لضمان عدم تخطي الرسائل (0-5% كحد أقصى)
            progress += Math.random() * 4 + 1;
            
            if (progress > 95) {
                progress = 95; // توقف عند 95% حتى ينتهي الاستيراد فعلياً
            }
            
            progressBar.style.width = progress + '%';
            progressBar.setAttribute('aria-valuenow', progress);
            progressText.textContent = Math.round(progress) + '%';
            
            // تحديد الرسالة المناسبة بناءً على التقدم
            let newMessageIndex;
            if (progress < 84) {
                newMessageIndex = Math.floor(progress / 12);
            } else {
                // عند 84% وما فوق، نعرض الرسالة الأخيرة
                newMessageIndex = statusMessages.length - 1;
            }
            
            // عرض جميع الرسائل المتخطاة (لضمان عدم تفويت أي رسالة)
            while (messageIndex < newMessageIndex && messageIndex < statusMessages.length - 1) {
                messageIndex++;
                progressStatus.textContent = statusMessages[messageIndex];
            }
            
            if (progress >= 95) {
                clearInterval(interval);
            }
        }, 500);
    });
});
</script>
@endpush
