<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PlaceController extends Controller
{
    public function index()
    {
        $places = Place::orderBy('name')->get();
        return response()->json($places, Response::HTTP_OK);
    }
}