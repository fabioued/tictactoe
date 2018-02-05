<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = ['end_date','winner_id'];

    public function turns(){
        $this->hasMany('App\Turn','game_id');
    }
}
