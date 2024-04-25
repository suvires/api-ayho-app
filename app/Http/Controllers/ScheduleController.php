<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $schedules = Schedule::orderBy('name')->get();
        return response()->json($schedules, Response::HTTP_OK);
    }
}
