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
            // Obtener la ruta absoluta del archivo en el servidor
            $absolutePath = storage_path('app/public/' . $path);

            // Usar getimagesize para obtener las dimensiones de la imagen
            $imageDetails = getimagesize($absolutePath);
            $width = $imageDetails[0];
            $height = $imageDetails[1];

            // Guardar el ancho y el alto en el objeto company
            $company->image_width = $width;
            $company->image_height = $height;
        }

        $company->save();

        return response()->json($company, Response::HTTP_CREATED);
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

        $company = $user->company;
        $company->name = $request->name;
        $company->description = $request->description;
        $company->user_id = $user->id;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            if ($company->image_url) {
                // Remover el prefijo '/storage/' para obtener la ruta correcta en el sistema de archivos.
                $path = substr($company->image_url, strlen('/storage/'));

                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            $path = $request->file('image')->store('images/companies', 'public');


            $company->image_url = Storage::url($path);

            // Obtener la ruta absoluta del archivo en el servidor
            $absolutePath = storage_path('app/public/' . $path);

            // Usar getimagesize para obtener las dimensiones de la imagen
            $imageDetails = getimagesize($absolutePath);
            $width = $imageDetails[0];
            $height = $imageDetails[1];

            // Guardar el ancho y el alto en el objeto company
            $company->image_width = $width;
            $company->image_height = $height;

        }

        $company->save();

        return response()->json($company, Response::HTTP_CREATED);
    }
}