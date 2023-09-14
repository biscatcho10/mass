<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'type',
        'task_id',
        'user_id',
    ];
    
    
      public function user()
    {
        return $this->belongsTo('App\Models\User' ,'user_id');
    }

    public function task()
    {
        return $this->belongsTo('App\Models\Task' ,'task_id');
    }   
}
