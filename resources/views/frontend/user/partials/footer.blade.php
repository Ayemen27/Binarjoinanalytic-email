<footer class="dashboard-footer">
    <div class="row justify-content-between">
        <div class="col-auto">
            <p class="mb-0">
                {!! replacePlaceholders(translate('copyright', 'general'), [
                    'copyright_year' => '<span data-year></span>',
                    'site_name' => getSetting('site_name'),
                ]) !!}</p>
        </div>
        <div class="col-auto">
            <p class="mb-0">{!! replacePlaceholders(translate('Crafted with', 'general'), [
                'site_name' => '<a href="' . getSetting('site_url') . '">' . getSetting('site_name') . '</a>',
            ]) !!}
            </p>
        </div>
    </div>
</footer>
