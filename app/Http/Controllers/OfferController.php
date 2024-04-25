<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Offer;
use App\Models\OfferUser;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OfferController extends Controller
{
    public function index()
    {
        $data = Offer::with('company', 'skills', 'positions', 'schedule', 'places', 'attendance')->get();
        return response()->json($data,  Response::HTTP_OK);
    }

     public function getOffers()
    {
        $user = Auth::user();
        $offers = Offer::with('company', 'skills', 'positions', 'schedule', 'places', 'attendance')
            ->whereDoesntHave('users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->get();

        return response()->json($offers,  Response::HTTP_OK);
    }

    public function like(Request $request){
        $user = Auth::user();
        $user->offers()->syncWithoutDetaching([$request->offer_id => ['liked' => 1]]);
        return response()->json(['message' => 'success'],  Response::HTTP_OK);
    }

    public function undo(){
        $user = Auth::user();
        $lastOffer = $user->offers()->latest('id')->first();
        $lastOffer->load('company', 'skills', 'positions', 'schedule', 'places', 'attendance');
        $user->offers()->detach($lastOffer->id);
         return response()->json($lastOffer, Response::HTTP_OK);
    }

    public function dislike(Request $request){
        $user = Auth::user();
        $user->offers()->syncWithoutDetaching([$request->offer_id => ['liked' => 0]]);
        return response()->json(['message' => 'success'],  Response::HTTP_OK);
    }

    public function getMatches()
    {
        $user = Auth::user();

        $matches = OfferUser::where('user_id', $user->id)
                ->where('liked', true)
                ->with('offer')
                ->get();

        $matches->load('offer.company');

        return response()->json($matches,  Response::HTTP_OK);
    }
}