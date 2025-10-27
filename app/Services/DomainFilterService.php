<?php

namespace App\Services;

use App\Models\Domain;
use Illuminate\Http\Request;

class DomainFilterService
{
    public function filterDomains(Request $request)
    {
        $query = Domain::query();

        if ($request->has('q')) {
            $query->where('domain', 'like', '%' . $request->input('q') . '%');
        }

        if ($request->has('type') && $request->input('type') != "all") {
            $query->where('type', $request->input('type'));
        }

        if ($request->has('status') && $request->input('status') != "all") {
            $query->where('status', $request->input('status'));
        }

        $orderBy = $request->input('order_by') ?? 'created_at';
        $orderType = $request->input('order_type') ?? 'desc';
        $query->orderBy($orderBy, $orderType);
        $limit = $request->input('limit', 25); // Default to 15 items per page if not specified

        return $query->paginate($limit);
    }
}
