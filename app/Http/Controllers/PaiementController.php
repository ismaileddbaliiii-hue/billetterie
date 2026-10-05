<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaiementController extends Controller
{
    public function webhook(Request $request)
    {
        // TODO: Implement webhook handling logic as needed
        return response()->json(['status' => 'ok'], Response::HTTP_OK);
    }
}
