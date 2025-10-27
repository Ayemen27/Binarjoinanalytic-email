<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();
        });
        if (env("APP_IN_DEV_MODE", false)) {

            $items = array(
                ["key" => "site_name", "value" => env('APP_NAME', 'Title')],
                ["key" => "site_url", "value" => env('APP_URL', 'localhost')],
                ["key" => "logo", "value" => "uploads/site/logo.png"],
                ["key" => "dark_logo", "value" => "uploads/site/dark_logo.png"],
                ["key" => "favicon", "value" => "uploads/site/icon.png"],
                ["key" => "version", "value" => "1.0"],
                ["key" => "theme", "value" => "basic"],
                ["key" => "license_key", "value" => ""],
                ["key" => "date_format", "value" => ""],
                ["key" => "timezone", "value" => "Europe/London"],
                ["key" => "main_color", "value" => "#000000"],
                ["key" => "secondary_color", "value" => "#000000"],
                ["key" => "thirty_color", "value" => "#A22C2C"],
                ["key" => "https_force", "value" => "1"],
                ["key" => "enable_registration", "value" => "1"],
                ["key" => "enable_verification", "value" => "0"],
                ["key" => "enable_cookie", "value" => "1"],
                ["key" => "default_currency", "value" => "USD"],
                ["key" => "default_language", "value" => "en"],
                ["key" => "mail_mailer", "value" => "sendmail"],
                ["key" => "mail_host", "value" => "smtp-relay.sendinblue.com"],
                ["key" => "mail_port", "value" => "587"],
                ["key" => "mail_username", "value" => "mail_username@gmail.com"],
                ["key" => "mail_password", "value" => "mail_from_address"],
                ["key" => "mail_encryption", "value" => "tls"],
                ["key" => "mail_from_address", "value" => "mail_from_address@test.com"],
                ["key" => "mail_from_name", "value" => "Nameddd"],
                ["key" => "hide_default_lang", "value" => "1"],
                ["key" => "enable_preloader", "value" => "1"],
                ["key" => "privacy_policy", "value" => ""],
                ["key" => "terms_of_service", "value" => ""],
                ["key" => "cookie_policy", "value" => ""],
                ["key" => "captcha", "value" => "none"],
                ["key" => "captcha_login", "value" => "0"],
                ["key" => "captcha_register", "value" => "0"],
                ["key" => "captcha_contact", "value" => "0"],
                ["key" => "captcha_rest_password", "value" => "0"],
                ["key" => "captcha_admin", "value" => "0"],
            );
            DB::table('settings')->insert($items);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
