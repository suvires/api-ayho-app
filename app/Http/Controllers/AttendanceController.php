<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::orderBy('name')->get();
        return response()->json($attendances, Response::HTTP_OK);
    }
}