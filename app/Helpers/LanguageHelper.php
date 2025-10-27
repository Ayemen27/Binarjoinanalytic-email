<?php

use \Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\Cache;

if (!function_exists('getLocalizedURL')) {
    /**
     * Get the URL for a specific language.
     */

    function getLocalizedURL($lang)
    {
        return LaravelLocalization::getLocalizedURL($lang, null, [], true);
    }
}

if (!function_exists('getCurrentLang')) {
    /**
     * Get the current application language code.
     */

    function getCurrentLang()
    {
        return LaravelLocalization::getCurrentLocale();
    }
}

if (!function_exists('getCurrentLangDirection')) {

    function getCurrentLangDirection()
    {
        $langCode = getCurrentLang();
        $language = \App\Models\Language::where('code', $langCode)->first();

        return $language ? $language->direction : false; // Assuming 'rtl' is a boolean field in the Language model
    }
}

if (!function_exists('getCurrentLangName')) {
    /**
     * Get the name of the current application language.
     */

    function getCurrentLangName()
    {
        return LaravelLocalization::getCurrentLocaleName();
    }
}

if (!function_exists('getSupportedLanguages')) {
    /**
     * Get an array of supported language codes and their names.
     */

    function getSupportedLanguages()
    {
        $languages = getAllLanguages();

        $locales = [];
        foreach ($languages as $language) {
            $locales[$language->code] = [
                'name' => $language->name,
            ];
        }

        return $locales;
    }
}

if (!function_exists('getAllLanguages')) {
    /**
     * Get a collection of all languages.
     */

    function getAllLanguages()
    {
        return \App\Models\Language::all();
    }
}

if (!function_exists('countLanguages')) {
    /**
     * Get a collection of all languages.
     */

    function countLanguages()
    {
        return \App\Models\Language::all()->count();
    }
}


if (!function_exists('getAllCacheLanguages')) {
    /**
     * Get all languages from cache if available.
     */

    function getAllCacheLanguages()
    {
        return Cache::rememberForever('all_languages', function () {
            return getAllLanguages();
        });
    }
}

if (!function_exists('getAllLanguagesValidation')) {
    /**
     * Get a comma-separated string of all language codes for validation.
     */

    function getAllLanguagesValidation()
    {
        $collections = \App\Models\Language::pluck('code')->toArray();
        return implode(',', $collections);
    }
}