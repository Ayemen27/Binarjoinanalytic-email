    @guest
        <section class="section">
            <div class="container">
                <div class="question section-shapes mt-5" data-aos="fade-up" data-aos-duration="1000">
                    <h2 class="mb-3">{{ translate('Sign Up Now', 'general') }}</h2>
                    <p class="lead">{{ translate('Sign Up to Get Access to Exclusive Features', 'general') }}
                    </p>
                    <a href="{{ getSetting('call_to_action') }}"
                        class="btn btn-secondary btn-lg">{{ translate('Register Now', 'general') }}</a>
                </div>
            </div>
        </section>
    @endguest
