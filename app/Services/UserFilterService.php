<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;

class UserFilterService
{
    public function filterUsers(Request $request)
    {
        $query = User::query();

        if ($request->has('q')) {
            $searchTerm = $request->input('q');
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ['%' . $searchTerm . '%'])
                    ->orWhere('email', 'LIKE', '%' . $searchTerm . '%');
            });
        }


        if ($request->has('email_status')) {
            $emailStatus = $request->input('email_status');

            if ($emailStatus === '1') {
                $query->whereNotNull('email_verified_at');
            } elseif ($emailStatus === '0') {
                $query->whereNull('email_verified_at');
            }
        }


        if ($request->has('status')) {
            $accountStatus = $request->input('status');

            if ($accountStatus === '1') {
                $query->where('status', 1);
            } elseif ($accountStatus === '0') {
                $query->where('status', 0);
            }
        }







        $orderBy = $request->input('order_by') === "name" ? 'firstname' : 'created_at';

        $orderType = $request->input('order_type') ?? 'desc';
        $query->orderBy($orderBy, $orderType);
        $limit = $request->input('limit', 25); // Default to 15 items per page if not specified

        return $query->paginate($limit);
    }
}
