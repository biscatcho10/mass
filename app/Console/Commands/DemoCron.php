<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

class DemoCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:cron';

    /**
     * The console command description.
     *
     * @var string
     */
     
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
       
        
        $tasks = Task::where('end_date', '<',Carbon::now('Asia/Riyadh')->format('Y-m-d H:i:s'))->where('notify',0)->get();
        
    
            foreach($tasks as $task){
                $user = User::find($task->assign_to);
                $created = User::find($task->created_by);
                
                $registration_ids = [$user->fcm_token, $created->fcm_token];

                $data = [
                    "registration_ids" => $registration_ids,
                    // "to" => [$user->fcm_token, $created->fcm_token],
                    "notification" =>
                        [
                        'title' => 'time task finished!!!!',
                        'body' => 'task from '.$created->name,
                        'sound' => 'notification.mp3',
                        ],
                    "data" => [
                        "type" => 'expired',
                        'title' => 'time task finished!',
                        'body' => 'task from '.$created->name,
                        'task' => $task->id,
                        'user_id' => $user->id
                    ],
                ];
            
            send_notification_FCM($data);
            $task->notify = 1;
            $task->update();
        }
        $this->info('Successfully sent expired tasks to everyone.');
    }
}
