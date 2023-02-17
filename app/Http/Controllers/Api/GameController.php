<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Games;
use App\Models\GameLobby;
use Illuminate\Http\Request;
use Validator;
use Auth;

class GameController extends Controller
{
    public function game(Request $request)
    {
        try {
            $data = $request->all();
            $validator = Validator::make($request->all(), [
                'game_category_name' => 'required',
                'image' => 'required',
                'wager' => 'required',
                'winner_take' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 409, 'response' => 'Error', 'message' => implode(",", $validator->messages()->all()), 'data' => $data], 409);
            }
            if ($request->hasFile('image')) {
                $img_name = 'img_' . time() . '.' . $request->image->getClientOriginalExtension();
                // dd( $img_name);
                $request->image->move(public_path('img/'), $img_name);
                $imagePath = 'img/' . $img_name;
            }
            $gameData = [
                'game_category_name' => $data['game_category_name'],
                'image' => $imagePath,
                'wager' => $data['wager'],
                'winner_take' => $data['winner_take'],
            ];
            $game = Games::create($gameData);
            return response()->json(['status' => 200, 'response' => 'sucess', 'message' => 'game add sucess']);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'response' => 'error', 'message' => 'Something went wrong'], 500);
        }
    }

     public function gameLobby(Request $request){
       try{
            $data = $request->all();
            $useId = Auth::user()->id;
            $validator = Validator::make($request->all(), [
                'game_id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 409, 'response' => 'Error', 'message' => implode(",", $validator->messages()->all()), 'data' => $data], 409);
            }
        $game_id = explode(",", $request->game_id,-1); 
        if(count($game_id) > 0){
            foreach($game_id as $val){
                $game = Games::where('id',$val)->first();
                if(!$game){
                    return response()->json(['status'=>409,'response'=>'error','message'=>'game id not valid']);
                }
            $gameLoby = GameLobby::where(['user_id' => $useId, 'game_id' => $val])->first();
        
            if($gameLoby){
                return response()->json(['status'=>409,'response'=>'error','message'=>'game is already add']);
            }

            foreach($game_id as $id){
            $gameData =[
                'user_id'=>$useId,
                'game_id'=>$id,
            ]; 
            $gameSave = GameLobby::create($gameData);
            //    dd($gameSave);
            }
            return response()->json(['status'=>200, 'response'=>'sucess','message'=>'add suceessfully']);
            }
        
        }

        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'response' => 'error', 'message' => 'Something went wrong'], 500);
        }
    }

    public function getGameDetails(){
     try{
        $userId = Auth::user()->id;
        $game = GameLobby::with('gameDetails')->where('user_id',$userId)->get();
            $data['playStation'] =[];
            $data['xBox']=[];

       foreach($game as $val){
         $image = $val->gameDetails->image;
         if(!empty($image)){
            $image = 'img/'. @$image;
         }
            $arr = [
            'game_id'=> $val->game_id,
            'name'  => $val->gameDetails->game_category_name,
            'image' => $image,
            'count' => GameLobby::where('game_id', $val->game_id)->count(),

            ];
          if ($val->gameDetails->game_category_name == 1) {
            array_push($data['playStation'], $arr);
        } else {
            array_push($data['xBox'], $arr);
        }
        return response()->json(['status'=>200, 'response'=>'sucess','message'=>'get  suceessfully', 'data'=>$data]);

       }

     } catch (\Exception $e) {
        dd($e);
        return response()->json(['status' => 500, 'response' => 'error', 'message' => 'Something went wrong'], 500);
    }




    }
}
