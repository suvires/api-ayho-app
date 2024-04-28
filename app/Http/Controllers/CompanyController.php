<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::all();
        return response()->json($companies, Response::HTTP_OK);
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

        $company = new Company();
        $company->name = $request->name;
        $company->description = $request->description;
        $company->user_id = $user->id;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images/companies', 'public');
            $company->image_url = Storage::url($path);
        }

        $company->save();

        return response()->json($company, Response::HTTP_CREATED);
    }
}