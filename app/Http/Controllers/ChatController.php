<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function get_user_chats()
    {
        $user = auth()->user();

        //check auth
        if(!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        //check role candidate
        if(!$user->hasRole('Candidate')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $chats = Chat::where('user_id', $user->id)
        ->with('offer.company')
        ->get();

        return response()->json($chats);
    }

    public function get_company_chats()
    {
        $user = auth()->user();

        //check auth
        if(!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        //check role recruiter
        if(!$user->hasRole('Recruiter')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        //TODO: el chat no lleva company_id sino offer id
        $chats = Chat::where('company_id', $user->id)->get();

        return response()->json($chats);
    }
    public function get_user_chat_messages(Request $request)
    {
        $user = auth()->user();

        //check auth
        if(!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        //check role candidate
        if(!$user->hasRole('Candidate')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $chat = Chat::with(['messages', 'offer.company'])
        ->where('id', $request->id)
        ->where('user_id', $user->id)
        ->first();

        if(!$chat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Chat not found',
            ], 404);
        }

        return response()->json($chat);


    }

    public function get_company_chat_messages(Request $request)
    {
        $user = auth()->user();

        //check auth
        if(!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        //check role recruiter
        if(!$user->hasRole('Recruiter')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        //TODO: el chat no lleva company_id sino offer id
        $chat = Chat::with('messages')
        ->where('offer_id', $user->company->id)
        ->where('chat_id', $request->chat_id)
        ->first();

        if(!$chat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Chat not found',
            ], 404);
        }

        return response()->json($chat);
    }

    public function send_message(Request $request)
    {
        $user = auth()->user();

        //check auth
        if(!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        if($user->hasRole('Candidate')) {
            $chat = Chat::where('user_id', $user->id)
            ->where('id', $request->id)
            ->first();
        } else {
            //TODO: el chat no lleva company_id sino offer
            $chat = Chat::where('company_id', $user->id)
            ->where('id', $request->id)
            ->first();
        }

        if(!$chat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Chat not found: '.$user->id,
            ], 404);
        }

        $message = new Message();
        $message->chat_id = $chat->id;

        if($user->hasRole('Candidate')) {
            $message->sender = 'user';
        } else {
            $message->sender = 'company';
        }

        $message->content = $request->content;
        $message->save();

        return response()->json($message, 201);
    }
}
