<?php

namespace App\Services;

use App\Models\Page;
use Illuminate\Http\Request;

class PageFilterService
{
    public function filterPages(Request $request)
    {
        $query = Page::query();

        if ($request->has('q')) {
            $query->where('title', 'like', '%' . $request->input('q') . '%');
        }

        if ($request->has('lang') && $request->input('lang') != "all") {
            $query->where('lang', $request->input('lang'));
        }

        $orderBy = $request->input('order_by') ?? 'created_at';
        $orderType = $request->input('order_type') ?? 'desc';
        $query->orderBy($orderBy, $orderType);
        $limit = $request->input('limit', 25); // Default to 15 items per page if not specified

        return $query->paginate($limit);
    }
}