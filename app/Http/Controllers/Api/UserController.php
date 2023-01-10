<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Validator,Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Log;
use Str;



class UserController extends Controller
{
  public function registration(Request $request)
    {
      try{
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'mobile' => 'required',
            'address' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        // $success['token'] = $user->createToken('MyLaravelApp')->accessToken;
        // $success['first_name'] = $user->first_name;
        return response()->json(['status' =>200, 'message' => 'Login successfully']);
      }catch (\Exception $e) {
        return response()->json('error', 'something wrong');
    }
    }

    public function login(Request $request){ 
      try{
        if($request->type =='email'){
      if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
          $user = Auth::user(); 
          $success['token'] =  $user->createToken('MyLaravelApp')-> accessToken;
          // $success['userId'] = $user->id;
          return response()->json(['status' =>200, 'message' => 'Login successfully',  'success' => $success]); 
      }else{
        return response()->json(['status'=>401,'response' => 'error','message' => 'email not exits'], 401); 
      }
    } 

  else{
      $user = User::where('mobile',$request->mobile)->first();  
      if(!empty($user)){
        if(Hash::check($request->password,$user->password)){
          $success['token'] =  $user->createToken('MyLaravelApp')-> accessToken;
          return response()->json(['status'=>200,'response' => 'success','message' => 'Login successfully','data' => $success], 200); 
        }else{
          return response()->json(['status'=>401,'response' => 'error','message' => 'Password not metch'], 401);   
        }
      }else{
        return response()->json(['status'=>401,'response' => 'error','message' => 'Phone number not exits'], 401); 
      }      

  }
     }catch (\Exception $e) {
        return  response()->json(['error', 'something wrong']);
    }
  }


  public function updateProfile(Request $request)
  {
   try{
    $user = auth()->guard('api')->user();
    $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'mobile' => 'required',
            'address' => 'required',
            'email' => 'required|email|unique:users,email,'. $user->id
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        
        if($request->hasFile('image'))
        
        {
        $img_name = 'img_'.time().'.'.$request->image->getClientOriginalExtension();
        // dd( $img_name);
        $request->image->move(public_path('img/'), $img_name);
        $imagePath = 'img/'.$img_name;        
        $user->image = $imagePath;
      }

        $user->first_name = $request['first_name'];
        $user->last_name = $request['last_name'];
        $user->mobile = $request['mobile'];
        $user->address = $request['address'];
        $user->email = $request['email'];        
        $user->save();
        return response()->json(['success' => 'profile sucessfully updat']);
      }catch (\Exception $e) {
        return  response()->json(['error', 'something wrong']);
      }
  }

   public function getProfile(Request $request){
     try{
        // $user = auth()->guard('api')->user();
        $id = $request->id;
        $user = User::find($id);
        if(!empty($user['image']))
        {
         $user['image'] = 'img/'. @$user["image"];
        }
       return response()->json(['success' => $user]);
      }catch (\Exception $e) {
       return  response()->json(['error', 'something wrong']);
      }
   }

   public function mobileOtp(Request $request){
        try{
        $otp = rand(1000,9999);
        Log::info("otp = ".$otp);
        $user = User::where('mobile','=',$request->mobile)->update(['otp' => $otp]);
        if($user){

        $mobile_details = [
            'subject' => 'Testing Application OTP',
            'body' => 'Your OTP is : '. $otp
        ];
      // dd( $mobile_details);
        return response(["status" => 200, 'otp'=> $mobile_details, "message" => "OTP sent successfully"]);
        }
        else{
            return response(["status" => 401, 'message' => 'Invalid']);
        }
      }catch (\Exception $e) {
        return  response()->json(['error', 'something wrong']);
      }
    }

   public function verifyOtp(Request $request){
        try{
        $user  = User::where([['mobile','=',$request->mobile],['otp','=',$request->otp]])->first();
        if($user){
            // auth()->login($user, true);
            Auth::loginUsingId($user->id);
            User::where('mobile','=',$request->mobile)->update(['otp' => null]);
            $accessToken = auth()->user()->createToken('authToken')->accessToken;

            return response(["status" => 200, "message" => "Success", 'user' => auth()->user(), 'access_token' => $accessToken]);
        }
        else{
            return response(["status" => 401, 'message' => 'Invalid']);
        }
      }catch (\Exception $e) {
        return  response()->json(['error', 'something wrong']);
      }
    }

   public function forgot(Request $request)
    {
      try {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
        ]);
        if ($validator->fails()) {
            $obj = ["Status" => false, "success" => 0, "errors" => $validator->errors()];
            return response()->json($obj);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $encode = base64_encode($request->email);
            $url = route('reset.password.get',base64_encode($request->email));

            \Mail::send('email.forgetPassword', ['url' => $url], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Reset Password');
            });
            $obj = [
                "Status" => true, "success" => 1,
                'msg' => "Forget Password link has been sent to Email.",
            ];
            return response()->json($obj);
        } else {
            $obj = [
                "Status" => false, "success" => 0,
                'msg' => "No Email Found !",
            ];
            return response()->json($obj);
        }
    } catch (\Exception $ex) {
      dd($ex);
        return json_encode(['status' => 500, 'message' => 'Email is not valid!']);
    }
 }
}