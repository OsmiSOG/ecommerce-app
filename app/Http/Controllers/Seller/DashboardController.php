<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index() : \Inertia\Response {
        return Inertia::render('Seller/Dashboard');
    }
}
