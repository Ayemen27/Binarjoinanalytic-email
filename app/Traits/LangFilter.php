<?php

namespace App\Traits;



trait LangFilter
{
    public function scopeFilteredByLang($query, $request)
    {
        return $query->when($request->has('lang') && $request->lang !== 'all', function ($query) use ($request) {
            $query->where('lang', $request->lang);
        });
    }
}
