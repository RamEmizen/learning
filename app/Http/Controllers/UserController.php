<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Spatie\Permission\Models\Role;
use DB;
use Hash,Auth;
use Illuminate\Support\Arr;

class UserController extends Controller
{
   public function userList(Request $request)
   {
    
    if(Auth::user()->roles[0]->id == '1'){
        // $userData = User::orderBy('id','asc')->get();
        $date ="";
        $name="";
        $userData= User::doesntHave('roles');
        if(!empty($request->date)){
            $date = date('Y-m-d',strtotime($request->date));
            $userData= $userData->whereDate('created_at',$date);
        } 
        if(!empty($request->first_name)){
            $name = $request->first_name;
            $userData= $userData->where('first_name',$name)->orWhere('last_name',$name);
        }

        $userData = $userData->orderBY('id','desc')->get();

    }else{
        $userData = User::where('created_by_id',Auth::user()->id)->orderBy('id','desc')->get();
    }
     return view('users.list',compact('userData','date','name'));
   }

    public function addUser(){
        $countries = Country::all();
        $state = State::all();
        $city = City::all();
     return view('users.add',compact(['countries','state','city']));  
    }
    public function storeUser(Request $request){
        try{
             $data = $request->all();
             $rules = [
                'first_name' => 'required',
                'last_name' => 'required',
                'mobile' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10:mobile|unique:users,mobile,' . @$request->user_id,
                'email' => 'required|email|unique:users,email,' . @$request->user_id,
                'image' => 'required',
            ];
            $request->validate($rules);

            if($request->hasFile('image'))
            {
            $img_name = 'img_'.time().'.'.$request->image->getClientOriginalExtension();
            $request->image->move(public_path('img/'), $img_name);
            $imagePath = 'img/'.$img_name;
            $data['image'] = $imagePath;
          }
          $userData = 
           [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'mobile' => $data['mobile'],
            'email' => $data['email'],
            'image' =>$imagePath,
            'created_by_id'=> Auth::user()->id

           ];
             User::create($userData);
             return redirect()->route('user.list')->with('user sucessfully add');
        } catch(Exception $e){
            return redirect()->back()->with('error','something wrong');
            }
    }

    
    public function userEdit($id){

      $user =User::where('id',$id)->first();
      // dd($user->name);
      return view('users.uedit', compact('user'));

      }

      public function userUpdate(Request $request){
      try{
        $data = $request->all();
        $rule = [
            'first_name' => 'required',
            'last_name' => 'required',
            'mobile' => 'required',
            'email' => 'required|email|unique:users,email,' . @$request->id,
            // 'file' => empty($data['oldImage']) ? 'reqiured|image|mimes:jpg,png,jpeg,gif,svg' : 'image|mimes:jpg,png,jpeg,gif,svg',
        ];
        if(empty($data['oldImage'])){            
            $rule = [
                'file' => 'required|image|mimes:jpg,png,jpeg,gif,svg'
            ];
        }else{            
            $rule = [
                'file' => 'image|mimes:jpg,png,jpeg,gif,svg'
            ];
        }
        $request->validate($rule);
        $userData = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'mobile' => $data['mobile'],
            'email' => $data['email'],            
        ];
        if ($image = $request->file('file')) {
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $destinationPath = 'img/'.$profileImage;
            $file  = $image->move($destinationPath, $profileImage);
            $userData['image'] = $file;
        }
        User::where('id',$request->id)->update($userData);
        return redirect()->route('user.list')->with('user succsessfully update');
      }catch(Exception $e){
       return redirect()->back()->with('error','with something wrong');
      }
      }

      public function userDelete(Request $request){
        try{
       $data = User::find($request->id);
        $data->delete();
        // return json_encode(['status'=>200,'massage'=>'delete']);
        return response()->json(['status'=>200,'massage'=>'delete']);
        }catch(Expception $e){
            return response()->json(['status' => 500, 'message' => "Something problem in internal system!"]);
        }
      }
      public function userShow($id){
        $user = User::where('id',$id)->get();
        return view('users.ushow',compact('user'));
    }
//password reset
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

 // this is use to user managemenet roll controlller
  public function index(Request $request)
    {
        // $data = User::where()->orderBy('id','DESC')->paginate(5);
        $data = User::whereHas("roles", function($q){ $q->where("name",'!=',null); })->paginate(5);
        return view('users.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('users.create',compact('roles'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);
    
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
    
        $user = User::create($input);
        $user->assignRole($request->input('roles'));
    
        return redirect()->route('users.index')
                        ->with('success','User created successfully');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show',compact('user'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
    
        return view('users.edit',compact('user','roles','userRole'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);
    
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }
    
        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
    
        $user->assignRole($request->input('roles'));
    
        return redirect()->route('users.index')
                        ->with('success','User updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
    }
}

