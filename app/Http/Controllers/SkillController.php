<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SkillController extends Controller
{
    public function index(Request $request)
    {      
        $skills = Skill::orderBy('name')->get();
        return response()->json($skills, Response::HTTP_OK);
    }
}
