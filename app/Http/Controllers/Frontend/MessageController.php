<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
public function storeGuest(Request $request)
{
    $request->validate([
        'guest_name' => 'required|string|max:100',
        'guest_email' => 'required|email',
        'content' => 'required|string|max:1000',
    ]);

    DB::table('messages')->insert([
        'sender' => $request->guest_name,
        'sender_email' => $request->guest_email,
        'content' => $request->content,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return response()->json(['message' => 'Message sent successfully!']);
}

public function fetchByGuestEmail(Request $request)
{
    $guestEmail = $request->query('guest_email');

    if (!$guestEmail) {
        return response()->json(['error' => 'guest_email is required'], 400);
    }

    $messages = DB::table('messages')->where('sender_email', $guestEmail)
                       ->orderBy('created_at', 'asc')
                       ->get(['sender', 'content','admin_reply']);

    return response()->json($messages);
}

}
