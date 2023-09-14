<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ReasonForReject extends Model
{
    use HasFactory;
    
       protected $fillable = [
        'description',
        'task_id',
        'user_id',
        'audio'
    ];

    protected static function booted()
    {
        static::creating(function ($reason) {
            $reason->user_id = Auth::id();
        });
    }

    public function getAudioAttribute($value)
    {
        if(is_null($value)){
            return $value;
        }else{
            return env('APP_URL').'/storage/'.$value;
        }
        
    }



    public function auther()
    {
        return $this->belongsTo('App\Models\User' ,'user_id');
    }

    public function task()
    {
        return $this->belongsTo('App\Models\Task' ,'task_id');
    }    
}
