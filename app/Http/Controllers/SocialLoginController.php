<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Instructor;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialLoginController extends Controller
{
    public function create($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function store($provider)
    {
        $socialUser = Socialite::driver($provider)->stateless()->user();

        $existinguser = User::where('email', $socialUser->getEmail())->first();

        $instructor = Instructor::where('instructor_email', $socialUser->getEmail())->first();

        if ($instructor) {
            $roleId = 2; 
        } else {
            $roleId = 1;
        }

        if ($existinguser) {
            Auth::login($existinguser);
        } else {

            $newuser = User::updateOrCreate(
                ['email' => $socialUser->email],
                [
                    'name' => $socialUser->name,
                    'password' => bcrypt(Str::random(16)),
                    'provider' => $provider,
                    'created_at' => now(),
                    'role_id' => $roleId

                ]
            );

            Auth::login($newuser);


            if (Auth::user()->role_id == 2) {
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('register-course');
            }
        }
    }
}
