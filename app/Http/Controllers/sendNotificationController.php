<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use App\Models\User;
use Notification;
use App\Notifications\MyFirstNotification;
  
class sendNotificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
  
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
  
    public function sendNotification()
    {
        $users = User::first();
        // dd( $users);
        // $users = User::where('status', '1')->first();
 
        $details = [
            'greeting' => 'Hi Artisan',
            'body' => 'This is my first notification from ItSolutionStuff.com',
            'thanks' => 'Thank you for using ItSolutionStuff.com tuto!',
            'actionText' => 'View My Site',
            'actionURL' => url('/'),
            'id' => 1
        ];
        $users->notify(new MyFirstNotification($details));
    //    $data =  Notification::send($users, new MyFirstNotification($details));
    //    dd($data);
   
    }
  
}