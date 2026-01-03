<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\IncompleteOrder;

class IncompleteOrderController extends Controller
{
    public function store(Request $request)
    {
        $cart = session()->get('cart');

        if (!$cart) {
            return response()->json(['status' => false]);
        }

        IncompleteOrder::updateOrCreate(
            [
                'session_id' => session()->getId(),
                'status' => 'incomplete'
            ],
            [
                'phone' => $request->phone,
                'name' => $request->name,
                'ip_address' => $request->ip(),
                'cart_data' => json_encode($cart),
                'total' => cartTotal()
            ]
        );

        return response()->json(['status' => true]);
    }
}

