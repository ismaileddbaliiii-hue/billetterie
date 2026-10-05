<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handleStripe(Request $request)
    {
        // TODO: add Stripe webhook handling logic
        return response()->json(['status' => 'ok']);
    }

    public function handlePaypal(Request $request)
    {
        // TODO: add PayPal webhook handling logic
        return response()->json(['status' => 'ok']);
    }
}
