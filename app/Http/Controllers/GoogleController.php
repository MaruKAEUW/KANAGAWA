<?php

  

namespace App\Http\Controllers;

  

use Illuminate\Http\Request;

use Laravel\Socialite\Facades\Socialite;

use Exception;

use App\Models\User;

use Illuminate\Support\Facades\Auth;

  

class GoogleController extends Controller

{

    /**

     * Create a new controller instance.

     *

     * @return void

     */

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();

    }

        

    /**

     * Create a new controller instance.

     *

     * @return void

     */

     public function handleGoogleCallback()

     {
         try {
           
             $user = Socialite::driver('google')->user();
             $finduser = User::where('email', $user->email)->first();
             
             if($finduser){
                 Auth::login($finduser);
             
                 return redirect()->to('http://localhost:5173/save-acc/'.$finduser->id);
             }else{
                 $newUser = User::create([
                     'name' => $user->name,
                     'email' => $user->email,
                     'google_id'=> $user->id,
                      'deleted_at' => 1,
                      'status'=>1,
                      'role_id' => 4,
                     'password' => encrypt('123456789')
 
                 ]);
 
       
 
                 Auth::login($newUser);
                 return redirect()->to('http://localhost:5173/save-acc/'.$newUser->id);
            
             }
 
       
 
         } catch (Exception $e) {
 
             dd($e->getMessage());
 
         }
 
     }

}