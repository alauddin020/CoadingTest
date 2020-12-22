<?php

namespace App\Http\Controllers;

use App\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FrontController extends Controller
{
    public function index()
    {
        $payments = Subscription::with('user')->whereMonth('created_at',now()->format('m'))->whereYear('created_at',now()->format('Y'))->get();
        return view('welcome',compact('payments'));
    }
}
