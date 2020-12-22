<?php

namespace App\Http\Controllers;

use App\Subscription;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $amount = 10 *100;
            $payment_intent = \Stripe\PaymentIntent::create([
                'description' => 'Stripe Test Payment',
                'amount' => $amount,
                'currency' => 'usd',
            ]);
            $intent = $payment_intent->client_secret;
            return $data = view('payment_form',compact('intent'))->render();
        }
        return view('home');
    }
    public function store(Request $request)
    {
        try {
            $remainingDays = now()->daysInMonth-now()->day;
            $exists = Subscription::whereMonth('created_at',now()->format('m'))->whereYear('created_at',now()->format('Y'))->first();
            if (!$exists) {
                $subscription = new Subscription();
                $subscription->user_id = \Auth::id();
                $subscription->amount = $request->amount ?? 10;
                $subscription->save();
            }
            $user = \Auth::user();
            $user->status = 1;
            $user->expire = now()->addDays($remainingDays)->format('Y-m-d');
            $user->save();
            return redirect()->back()->with('status','Payment has been successful');
        }catch (\Exception $e){return $e->getMessage();}
    }
}
