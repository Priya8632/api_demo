<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
        
            $user = Socialite::driver('google')->user();
            // dd($user);
            $finduser = User::where('social_id', $user->id)->first();
            if($finduser){
         
                Auth::login($finduser);
                return redirect()->intended('/home');
         
            }else{

                $newUser = User::create(['email' => $user->email],[

                        'name' => $user->name,
                        'social_id'=> $user->id,
                        'social_type' => 'google'
                    ]);
        
                Auth::login($newUser);
                return redirect()->intended('/home');
            }
       
        } catch (Exception $e) {
            
            dd($e->getMessage());
        }
    }

}
