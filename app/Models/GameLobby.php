<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameLobby extends Model
{
    use HasFactory;
    protected $table ='game_lobby';
    // protected $fillable = ['user_id','game_id'];
    protected $guarded = []; 
    
    public function gameDetails()
    { 
        return $this->hasOne(Games::class,'id','game_id');
    }
}
