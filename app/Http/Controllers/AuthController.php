<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class AuthController extends Controller
{
    public function signIn(Request $request) 
    {     
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['message' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();
        
        $token = $user->createToken('token')->plainTextToken;        
        
        $user->load('attendances', 'positions', 'schedules', 'places', 'skills');

        $profile = Profile::where('user_id', $user['id'])->first();        

        $data = $user->only(['id', 'name', 'email', 'attendances', 'positions', 'schedules', 'places', 'skills']);
        $data['image_width'] = $profile->image_width;
        $data['image_height'] = $profile->image_height;
        $data['image'] = $profile->image_url;
        $data['linkedin_url'] = $profile->linkedin_url;
        $data['salary'] = $profile->salary;
        $data['access_token'] = $token; 
        
        return response()->json($data, Response::HTTP_OK);               
    }

    public function signUp(Request $request) 
    {
        $requestData = json_decode($request->input('data'), true);                       
        
        $data = [
            'email' => $requestData['email'],
            'password' => $requestData['password'],
            'name' => $requestData['name'],
        ];

        $existingUser = User::where('email', $data['email'])->first();
        if ($existingUser) {
            return response()->json(['message' => 'Email already exists'], Response::HTTP_CONFLICT);            
        }             
        
        $user = User::create($data);
        $user->makeVisible('password');

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $image = Image::make($file);
            $image_width = $image->getWidth();
            $image_height = $image->getHeight();
            $extension = $file->getClientOriginalExtension();
            $path = $file->storeAs('public/images/users', $user['id'].'.'.$extension);            
            $image_url = URL::to('/') . Storage::url($path);         
        }

        $data_profile = [
            'linkedin_url' => $requestData['linkedin_url'],
            'salary' => $requestData['salary'],
            'user_d' => $user['id'],
            'image_url' => $image_url,
            'image_width' => $image_width,
            'image_height' => $image_height,
        ];       
      
        $user->profile()->create($data_profile);
        $user->load('profile');

        $attendances = $requestData['attendances'];        
        $user->attendances()->attach($attendances);        
        $user->load('attendances');

        $skills = $requestData['skills'];        
        $user->skills()->attach($skills);        
        $user->load('skills');

        $schedules = $requestData['schedules'];        
        $user->schedules()->attach($schedules);        
        $user->load('schedules');

        $positions = $requestData['positions'];        
        $user->positions()->attach($positions);        
        $user->load('positions');

        $places = $requestData['places'];        
        $user->places()->attach($places);        
        $user->load('places');

        return response()->json(['message' => 'success'], Response::HTTP_OK);     
    }

    public function signOut() 
    {
        Auth::user()->tokens()->delete();
        return response()->json(['message' => 'success'], Response::HTTP_OK);
    }

    public function getUser()
    {       
        $user = Auth::user();        
        
        $user->load('attendances', 'positions', 'schedules', 'places', 'skills');

        $profile = Profile::where('user_id', $user['id'])->first();        

        $data = $user->only(['id', 'name', 'email', 'attendances', 'positions', 'schedules', 'places', 'skills']);
        $data['image_width'] = $profile->image_width;
        $data['image_height'] = $profile->image_height;
        $data['image'] = $profile->image_url;
        $data['linkedin_url'] = $profile->linkedin_url;
        $data['salary'] = $profile->salary;        
        
        return response()->json($data, Response::HTTP_OK);       
    }
}
