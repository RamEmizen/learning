<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;
use App\Models\SystemSetting;
use App\Models\Notification;
use Carbon\Carbon;


class Helper
{

    public static function testcheck()
    {
        return ["test1", "test1"];
    }

    /*
     * Get Generate Setting
     */

    public static function getNotificaton($notiData)
    {
        $SERVER_API_KEY = 'AAAAggU1X2k:APA91bHIoGFVp1S2dQ0jNqvEfys7-ymVfHNel5yZgvoe7SqBCY_bvzecp2INxXD3ytAC6LZZijI7snYs0iLUlIQK1KfViQm_YedGRY4O-CgkxgGVFUNbORH5xT7YoLhjmcHt8MOzlg2g';
  
            $data = [
                "registration_ids" => $notiData['device_token'],
                "notification" => [
                    "title" => $notiData['title'],
                    "body" => $notiData['body'],  
                ]
            ];
            $dataString = json_encode($data);
            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
                   
            $response = curl_exec($ch);
            dd($response);
    }

}
