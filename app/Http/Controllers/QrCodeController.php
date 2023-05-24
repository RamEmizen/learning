<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class QrCodeController extends Controller
{
    public function index()
    {
       $userData = User::orderBy('id','desc')->get();
 
      return view('qrcode.qrcode',compact('userData'));
    }
}