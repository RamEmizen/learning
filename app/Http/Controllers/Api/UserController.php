<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Agora\Src\RtcTokenBuilder;
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
        $input['name'] = $input['first_name'].' '.$input['last_name'];
        $user = User::create($input);
        // $success['token'] = $user->createToken('MyLaravelApp')->accessToken;
        // $success['first_name'] = $user->first_name;
        return response()->json(['status' => 200, 'response' => 'Success', 'message' => 'Register successfully'], 200);
      }catch (\Exception $e) {
        return response()->json(['status'=>500,'response' => 'error','message' => 'Something went wrong'], 500);
    }
    }

    public function login(Request $request){ 
      try{
        if($request->type =='email'){
      if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
          $user = Auth::user(); 
          $success['token'] =  $user->createToken('MyLaravelApp')-> accessToken;
          // $success['userId'] = $user->id;
          return response()->json(['status' => 200, 'response' => 'Success', 'message' => 'login successfully','success' => $success], 200);

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
          return response()->json(['status'=>409,'response' => 'error','message' => 'Password not metch'], 409);   
        }
      }else{
        return response()->json(['status'=>409,'response' => 'error','message' => 'Phone number not exits'], 409); 
      }      
  }
     }catch (\Exception $e) {
        return response()->json(['status'=>500,'response' => 'error','message' => 'Something went wrong'], 500);
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
            // return response()->json(['status' => 409, 'response' => 'Error', 'message' => implode(",", $validator->messages()->all()), 'data' => $request->all()], 409);
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
        // return response()->json(['success' => 'Profile sucessfully updat']);
        return response()->json(['status' => 200, 'response' => 'Success', 'message' => 'Profile sucessfully updat'], 200);
      }catch (\Exception $e) {
        return response()->json(['status' => 500, 'response' => 'error', 'message' => 'Something went wrong'], 500);
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
       return response()->json(['status' => 200, 'response' => 'Success', 'message' => 'Profile details get successfully','success' => $user], 200);

      }catch (\Exception $e) {
        return response()->json(['status' => 500, 'response' => 'error', 'message' => 'Something went wrong'], 500);
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
        return response(["status" => 200, 'response' => 'Success', 'otp'=> $mobile_details, "message" => "OTP sent successfully"],200);
        }
        else{
            return response(["status" => 409, 'response' => 'error', 'message' => 'Invalid'],409);
        }
      }catch (\Exception $e) {
        return  response()->json(['status'=>'500', 'response'=> 'error','message'=>'something wrong'],500);
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
            return response(["status" => 409, 'response'=>'error','message' => 'Invalid'],409);
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
        return json_encode(['status' => 500, 'response'=>'error', 'message' => 'Email is not valid!'],500);
    }
 }
  //agora
    public function generate_token(Request $request){
      try {
           $validator = Validator::make($request->all(), [
              'channelName' => 'required',
          ]);
          if ($validator->fails()) {

              $obj = ["Status" => false, "success" => 0, "errors" => $validator->errors()];
              return response()->json($obj);
          }
        $appID = env('AGORA_APP_ID');
        $appCertificate = env('AGORA_APP_CERTIFICATE');
        $channelName = $request->channelName;
        $user = Auth::user()->name;
        $role = RtcTokenBuilder::RoleAttendee;
        $expireTimeInSeconds = 3600;
        $currentTimestamp = now()->getTimestamp();
        $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;
        $rtcToken = RtcTokenBuilder::buildTokenWithUserAccount($appID, $appCertificate, $channelName, $user, $role, $privilegeExpiredTs);
        return response()->json(['status'=>200, 'messsage'=>'sucess' ,'data'=>$rtcToken]);
      } catch (\Exception $ex) {
        dd($ex);
          $obj = ["Status" => false, "success" => 0, "msg" => "'Something problem in internal system!"];
          return response()->json($obj);
      } 
    }
}