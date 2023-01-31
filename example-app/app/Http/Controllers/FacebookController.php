<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FacebookController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
        
            $user = Socialite::driver('facebook')->stateless()->user();
            $finduser = User::where('social_id', $user->id)->first();
         
            if($finduser){
         
                Auth::login($finduser);
                return redirect()->intended('/home');
         
            }else{
                $newUser = User::updateOrCreate(['email' => $user->email],[

                        'name' => $user->name,
                        'social_id'=> $user->id,
                        'social_type' => 'facebook'

                    ]);
        
                Auth::login($newUser);
                return redirect()->intended('/home');
            }
       
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}