<?php

namespace App\Http\Controllers;

use App\Rules\ValidRole;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{


    public function loginUser()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        //Auth user with credentials
        $user = auth()->user();

        $role = request(['role']);
        $user->load('roles');
        if(!$user->hasRole($role)) {
            auth()->logout();
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function updateProfile(Request $request){
        $user = auth()->user();
        $user->load('roles');

        if(!$user->hasRole('Candidate')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $linkedin = $request->input('linkedin');
        $salary = $request->input('salary');
        $positions = json_decode($request->input('positions'));
        $skills = json_decode($request->input('skills'));
        $places = json_decode($request->input('places'));
        $schedules = json_decode($request->input('schedules'));
        $attendances = json_decode($request->input('attendances'));
        $image = $request->file('image');

        $validator = Validator::make([
            'linkedin' => $linkedin,
            'salary' => $salary,
            'positions' => $positions,
            'skills' => $skills,
            'places' => $places,
            'schedules' => $schedules,
            'attendances' => $attendances,
            'image' => $image,
        ], [
            'linkedin' => 'required|string',
            'salary' => 'required|numeric|min:1',
            'positions' => 'required|array|min:1|max:5',
            'skills' => 'required|array|min:1|max:10',
            'places' => 'required|array|min:1',
            'schedules' => 'required|array|min:1',
            'attendances' => 'required|array|min:1',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048', // Max size 2MB
        ]);

        // Ejecuta la validación
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        $profile = $user->profile;

        $profile->linkedin = $request->input('linkedin');
        $profile->salary = $request->input('salary');

        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            if ($profile->image_url) {
                // Remover el prefijo '/storage/' para obtener la ruta correcta en el sistema de archivos.
                $path = substr($profile->image_url, strlen('/storage/'));

                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            $path = $request->file('image')->store('images/users', 'public');


            $profile->image_url = Storage::url($path);

            // Obtener la ruta absoluta del archivo en el servidor
            $absolutePath = storage_path('app/public/' . $path);

            // Usar getimagesize para obtener las dimensiones de la imagen
            $imageDetails = getimagesize($absolutePath);
            $width = $imageDetails[0];
            $height = $imageDetails[1];

            // Guardar el ancho y el alto en el objeto company
            $profile->image_width = $width;
            $profile->image_height = $height;

        }
        $profile->save();

        $user->positions()->sync(json_decode($request->input('positions')));
        $user->skills()->sync(json_decode($request->input('skills')));
        $user->places()->sync(json_decode($request->input('places')));
        $user->schedules()->sync(json_decode($request->input('schedules')));
        $user->attendances()->sync(json_decode($request->input('attendances')));

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
        ], Response::HTTP_OK);
    }

    public function createProfile(Request $request)
    {
        $user = auth()->user();
        $user->load('roles');

        if(!$user->hasRole('Candidate')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $linkedin = $request->input('linkedin');
        $salary = $request->input('salary');
        $positions = json_decode($request->input('positions'));
        $skills = json_decode($request->input('skills'));
        $places = json_decode($request->input('places'));
        $schedules = json_decode($request->input('schedules'));
        $attendances = json_decode($request->input('attendances'));
        $image = $request->file('image');

        $validator = Validator::make([
            'linkedin' => $linkedin,
            'salary' => $salary,
            'positions' => $positions,
            'skills' => $skills,
            'places' => $places,
            'schedules' => $schedules,
            'attendances' => $attendances,
            'image' => $image,
        ], [
            'linkedin' => 'required|string',
            'salary' => 'required|numeric|min:1',
            'positions' => 'required|array|min:1|max:5',
            'skills' => 'required|array|min:1|max:10',
            'places' => 'required|array|min:1',
            'schedules' => 'required|array|min:1',
            'attendances' => 'required|array|min:1',
            'image' => 'required|file|mimes:jpg,jpeg,png|max:2048', // Max size 2MB
        ]);

        // Ejecuta la validación
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        $profile = $user->profile()->updateOrCreate([], [
            'linkedin' => $request->input('linkedin'),
            'salary' => $request->input('salary'),
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $path = $request->file('image')->store('images/users', 'public');
            $profile->image_url = Storage::url($path);

            // Obtener la ruta absoluta del archivo en el servidor
            $absolutePath = storage_path('app/public/' . $path);

            // Usar getimagesize para obtener las dimensiones de la imagen
            $imageDetails = getimagesize($absolutePath);
            $width = $imageDetails[0];
            $height = $imageDetails[1];

            // Guardar el ancho y el alto en el objeto company
            $profile->image_width = $width;
            $profile->image_height = $height;

        }
        $profile->save();

        $user->positions()->sync(json_decode($request->input('positions')));
        $user->skills()->sync(json_decode($request->input('skills')));
        $user->places()->sync(json_decode($request->input('places')));
        $user->schedules()->sync(json_decode($request->input('schedules')));
        $user->attendances()->sync(json_decode($request->input('attendances')));

        return response()->json([
            'status' => 'success',
            'message' => 'Profile created successfully',
        ], Response::HTTP_OK);
    }

    public function registerUser(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => ['required', new ValidRole],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
        ], 201);
    }

    public function meUser()
    {
        $user = auth()->user();

        $user->load('roles:name');
        $user->load('positions');
        $user->load('skills');
        $user->load('places');
        $user->load('schedules');
        $user->load('attendances');

        if($user->hasRole('Recruiter')) {
            $user->load('company');
        } else {
            $user->load('profile');
        }

        return response()->json($user);
    }

    public function logoutUser()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refreshUser()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        $user = auth()->user();
        $user->load('roles:name');

        $user->accessToken = $token;
        $user->expires = auth()->factory()->getTTL() * 60;

        return response()->json($user);
    }
}