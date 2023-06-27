<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::orderBy('name')->get();
        return response()->json($positions, Response::HTTP_OK);
    }
}

