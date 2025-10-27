<?php

namespace App\Services;

use App\Models\Translate;
use Illuminate\Http\Request;

class TranslateFilterService
{
    public function filterTranslate(Request $request, $code)
    {
        $query = Translate::query();

        $query->where('lang', $code);

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('key', 'like', '%' . $request->input('q') . '%')
                    ->orWhere('value', 'like', '%' . $request->input('q') . '%');
            });
        }

        // Apply additional filters if needed
        if ($request->filled('group') && $request->input('group') != "all") {
            $query->where('collection', $request->input('group'));
        }

        if ($request->filled('status')) {
            if ($request->status == 0) {
                $query->where(function ($q) {
                    $q->whereNull('value')->orWhere('value', '');
                });
            }
        }

        $limit = $request->input('limit', 500); // Default to 15 items per page if not specified

        return $query->paginate($limit);


        //return $query->get();
    }
}
