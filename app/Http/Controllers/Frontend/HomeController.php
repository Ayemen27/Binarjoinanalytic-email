<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Payments\PaypalController;

class HomeController extends Controller
{

    public function index()
    {
        setMetaTags();
        return view()->theme('index');
    }


    public function dashboard()
    {
        return view('frontend.user.dashboard');
    }
}
