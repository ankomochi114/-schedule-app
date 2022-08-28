<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'stage_id', 'venue_time', 'start_time', 'end_time', 'description', 'player_id', 'date'
    ];

    public function player(){
        return $this->belongsToMany(Player::class);
    }

    public function stage(){
        return $this->belongsTo(Stage::class);
    }
}
