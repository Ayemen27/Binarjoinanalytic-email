<x-mail::message>
    # SMTP Test Email

    This is a test email to ensure your SMTP configuration is working correctly.

    If you are seeing this message, it means your SMTP settings are properly configured and the email is being sent
    successfully.

    @component('mail::button', ['url' => url('/')])
        Visit Our Site
    @endcomponent

</x-mail::message>
