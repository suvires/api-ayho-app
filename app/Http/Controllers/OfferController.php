<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Offer;
use App\Models\OfferUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($offers,  Response::HTTP_OK);
    }

    public function like(Request $request){
        $user = Auth::user();

        $user->load('roles');
        if(!$user->hasRole('Candidate')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }

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

    public function getMyCompanyOffers()
    {
        $user = Auth::user();

        $user->load('roles');
        if(!$user->hasRole('Recruiter')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $offers = Offer::where('company_id', $user->company->id)
                ->get();

        return response()->json($offers,  Response::HTTP_OK);
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

    public function getMatch(Request $request)
    {
        $user = Auth::user();

        $match = OfferUser::where('user_id', $user->id)
                ->where('liked', true)
                ->where('id', $request->id)
                ->where('user_id', $user->id)
                ->with('offer')
                ->first();

        $match->load('offer.company');

        return response()->json($match,  Response::HTTP_OK);
    }

    public function create(Request $request)
    {
        $user = auth()->user();

        if(!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);

        }
        $user->load('roles');
        if(!$user->hasRole('Recruiter')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $offer = new Offer();
        $offer->title = $request->title;
        $offer->description = $request->description;
        $offer->salary = $request->salary;
        $offer->schedule_id = $request->schedule_id;
        $offer->attendance_id = $request->attendance_id;
        $offer->company_id = $user->company->id;
        $offer->save();

        $offer->places()->sync($request->input('places', []));
        $offer->positions()->sync($request->input('positions', []));
        $offer->skills()->sync($request->input('skills', []));

        return response()->json($offer, Response::HTTP_CREATED);
    }
}