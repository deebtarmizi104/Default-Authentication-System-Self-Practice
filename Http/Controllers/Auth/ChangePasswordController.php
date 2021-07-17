<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChangePasswordController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }

    #^ restrict access back to change-password page using the browser back
    #button when currently in login page after the password changed

    public function index(){
      return view('auth.passwords.change');
    }

    public function changepassword(Request $request){

      $this->validate($request, [

      'oldpassword' => 'required',
      'password' => 'required | confirmed'

    ]);

    $hashedPassword = Auth::user()->password;
    if(Hash::check($request->oldpassword, $hashedPassword)){  #if the current password is same as the inserted old password field then...
      $user = User::find(Auth::id());
      $user->password = Hash::make($request->password); #insert the new password in the database with hashed
      $user->save();  #save new password
      Auth::logout(); #auto logout

      return redirect()->route('login')->with('successMsg', "Password is successfully changed.");
    }else{ #if entered old password does not match with the old password

      return redirect()->back()->with('errorMsg', "Invalid Old Password");
    }
    }
}
