<?php

namespace App\Traits;



trait UserFilter
{
    public function scopeFiltered($query, $request)
    {
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


        if ($request->has('email_status')) {
            $emailStatus = $request->input('email_status');

            if ($emailStatus === '1') {
                $query->whereNotNull('email_verified_at');
            } elseif ($emailStatus === '0') {
                $query->whereNull('email_verified_at');
            }
        }


        if ($request->has('account_status')) {
            $accountStatus = $request->input('account_status');

            if ($accountStatus === '1') {
                $query->where('status', 1);
            } elseif ($accountStatus === '0') {
                $query->where('status', 0);
            }
        }


        if ($request->has('between') && is_array($request->input('between')) && count($request->input('between')) === 2) {

            $startDate = $request->input('between')[0];
            $endDate = $request->input('between')[1];

            // Check if both start and end dates are not empty
            if (!empty($startDate) && !empty($endDate)) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        }


        if ($request->has('sorting')) {
            $query->orderBy('created_at', $request->input('sorting'));
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }
}
