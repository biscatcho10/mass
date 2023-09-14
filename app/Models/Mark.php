<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Auth;



class Mark extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'task_id',
    ];
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at'
      
    ];

    protected static function booted()
    {
        static::creating(function ($task) {
            $task->user_id = Auth::id();
        });
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