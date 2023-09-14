<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Auth;



class Task extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'description',
        'assign_to',
        'created_by',
        'start_date',
        'end_date',
        'status',
        'attachment',
        'audio',
        'title'
    ];
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        // 'created_by',
        'created_at',
        'updated_at'
      
    ];

    protected static function booted()
    {
        static::creating(function ($task) {
            $task->created_by = Auth::id();
            $task->status = '0'; 
        });
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
         'mark' => 'boolean',
        
    ];
    
    
    public function getStatusAttribute($value)
    {
        $status = ['pending','approved','rejected','done','expired'];
        return $status[$value];
    }
    
   
    
    public function getAttachmentAttribute($value)
    {
        if(is_null($value)){
            return $value;
        }else{
            $files = explode(",",$value);
        foreach($files as $key=>$file){
           $files[$key] = env('APP_URL').'/storage/'.$file;
        }
        return $files;
        }
        
    }
    
    public function getAudioAttribute($value)
    {
        if(is_null($value)){
            return $value;
        }else{   
            return env('APP_URL').'/storage/'.$value;
        }
    }
    
    
     public function reason()
    {
        return $this->belongsTo('App\Models\ReasonForReject','id','task_id');
    }
    
        
     
    
    public function auther()
    {
        return $this->belongsTo('App\Models\User' ,'created_by');
    }

    public function assign()
    {
        return $this->belongsTo('App\Models\User' ,'assign_to');
    }
    
    
}
