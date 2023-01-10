<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;
class UserController extends Controller
{
   public function userList()
   {
     $userData = User::orderBy('id','asc')->get();
     return view('user.list',compact('userData'));
   }

   public function resetPassword($token){
        try{
          $email = \base64_decode($token);
          // $reset = User::where('email',$email)->first();
          return view('reset-password',compact('email'));
      } catch (Exception $e) {
        return redirect()->back()->with('error', 'something wrong');
      }
   }

   public function updatePassword(Request $request){
           $data = $request->all();
        try{
              $request->validate([
                'password' => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required_with:password|same:password|min:6',
            ]);
            $user = User::where('email', $data['email'])
                ->update(['password' => Hash::make($request->password)]);
                return redirect()->back()->with('message','sucessfully update');

        }catch(Exception $e){
        return redirect()->back()->with('error','something wrong');
        }
  }
}
