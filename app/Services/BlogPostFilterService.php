<?php

namespace App\Services;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogPostFilterService
{
    public function filterPosts(Request $request)
    {
        $query = BlogPost::query();

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