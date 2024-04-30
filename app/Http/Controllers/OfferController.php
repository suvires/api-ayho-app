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
        $offers = Offer::with('company', 'skills', 'positions', 'schedules', 'places', 'attendances')
            ->where('published', 1)
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

    public function getMyCompanyOffer(Request $request)
    {
        $user = Auth::user();

        $user->load('roles');
        if(!$user->hasRole('Recruiter')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $offer = Offer::where('company_id', $user->company->id)
                ->where('id', $request->id)
                ->with('skills', 'positions', 'schedules', 'places', 'attendances')
                ->first();

        if(!$offer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Offer not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json($offer,  Response::HTTP_OK);
    }

    public function getMatches()
    {
        $user = Auth::user();

        $matches = OfferUser::where('user_id', $user->id)
                ->where('liked', true)
                ->with('offer.company')
                ->get();

        return response()->json($matches,  Response::HTTP_OK);
    }

    public function getMatch(Request $request)
    {
        $user = Auth::user();

        $match = OfferUser::where('user_id', $user->id)
                ->where('liked', true)
                ->where('id', $request->id)
                ->where('user_id', $user->id)
                ->with('offer.company', 'offer.skills', 'offer.positions', 'offer.schedules', 'offer.places', 'offer.attendances')
                ->first();

        if(!$match) {
            return response()->json([
                'status' => 'error',
                'message' => 'Match not found',
            ], Response::HTTP_NOT_FOUND);
        }

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
        $offer->company_id = $user->company->id;
        $offer->save();

        $offer->places()->sync($request->input('places', []));
        $offer->positions()->sync($request->input('positions', []));
        $offer->skills()->sync($request->input('skills', []));
        $offer->schedules()->sync($request->input('schedules', []));
        $offer->attendances()->sync($request->input('attendances', []));

        return response()->json($offer, Response::HTTP_CREATED);
    }

    public function update(Request $request)
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

        $offer = Offer::findOrFail($request->id)->where('company_id', $user->company->id)
        ->where('id', $request->id)
        ->first();

        $offer->title = $request->title;
        $offer->description = $request->description;
        $offer->salary = $request->salary;
        $offer->company_id = $user->company->id;
        $offer->save();

        $offer->places()->sync($request->input('places', []));
        $offer->positions()->sync($request->input('positions', []));
        $offer->skills()->sync($request->input('skills', []));
        $offer->schedules()->sync($request->input('schedules', []));
        $offer->attendances()->sync($request->input('attendances', []));

        return response()->json($offer, Response::HTTP_CREATED);
    }
    public function delete(Request $request){

        $user = Auth::user();

        $user->load('roles');
        if(!$user->hasRole('Recruiter')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $offer = Offer::find($request->id)->where('company_id', $user->company->id)
        ->where('id', $request->id)
        ->first();
        if(!$offer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Offer not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $offer->delete();

        return response()->json(['message' => 'success'],  Response::HTTP_OK);
    }

    public function unpublish(Request $request){

        $user = Auth::user();

        $user->load('roles');
        if(!$user->hasRole('Recruiter')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $offer = Offer::find($request->id)->where('company_id', $user->company->id)
        ->where('id', $request->id)
        ->first();
        if(!$offer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Offer not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $offer->published = 0;
        $offer->save();

        return response()->json(['message' => 'success'],  Response::HTTP_OK);
    }

    public function publish(Request $request){

        $user = Auth::user();

        $user->load('roles');
        if(!$user->hasRole('Recruiter')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $offer = Offer::find($request->id)->where('company_id', $user->company->id)
        ->where('id', $request->id)
        ->first();
        if(!$offer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Offer not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $offer->published = 1;
        $offer->save();

        return response()->json(['message' => 'success'],  Response::HTTP_OK);
    }

}