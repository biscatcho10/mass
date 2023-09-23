<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\User;
use App\Models\ReasonForReject;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\TaskRepositoryInterface;
use App\Models\Mark;
use App\Exceptions\GerneralException;
use DB;


class TaskRepository implements TaskRepositoryInterface
{

    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function getAllTasks()
    {
        $this->checkTaskExpired();
        if (Auth::user()->hasRole('admin')) {
            $data = Task::all();
        } else {
            $data = Task::where('assign_to', Auth::user()->id)->orWhere('created_by', Auth::user()->id)->get();
        }
        $tasks = $data->map(function ($task, $key) {
            $task->is_mark = $this->isMark($task->id);
            return $task;
        });
        return $tasks;
    }

    public function getTask($id)
    {
        $task = Task::findOrFail($id);
        $task->is_mark = $this->isMark($id);
        return $task;
    }

    public function createTask($data)
    {
        if (isset($data['attachments'])) {
            $files = [];

            foreach ($data['attachments'] as $key => $file) {
                $files[] = $this->uploadFile($file);
            }
            $data['attachment'] = implode(",", $files);
        }
        if (isset($data['audio'])) {

            $data['audio'] = $this->uploadFile($data['audio']);
        }
        $task = Task::create($data);

        $title = __("main.task.title");
        $body = __("main.task.body", ['user' => Auth::user()->name]);
        $this->notifyUser($task->assign_to, $task->status, $title, $body, $task->id);

        return $task;
    }

    public function updateTask($status, $id)
    {
        $task = Task::find($id);

        if ($task) {
            $status_var = ['pending', 'approved', 'rejected', 'done', 'expired'];
            if ($status == 'rejected') {
                $task = $this->updateStatus($task, (string)array_search($status, $status_var));
                if ($status == 'rejected') {
                    $this->deleteTask($id);
                }
                $user = $task->assign_to;
            } elseif ($task->assign_to == Auth::user()->id && $status != 'rejected') {
                $task = $this->updateStatus($task, (string)array_search($status, $status_var));
                $user = $task->created_by;
            } else {
                throw new GerneralException("Unauthenticated", 401);
            }


            $title = __("main.task-status.title", ['status' => __('main.status.'. $status)]);
            $body = __("main.task-status.body", ['user' => Auth::user()->name]);
            $this->notifyUser($user, $status, $title, $body, $task->id);
            $this->deleteReason($id);
            $task->is_mark = $this->isMark($task->id);

            return $task;
        }

        throw new GerneralException("Task not found", 401);
    }

    public function destoryTask($id)
    {
        $task = Task::findOrFail($id);
        if ($task->created_by == Auth::user()->id) {
            return $this->deleteTask($id);
        }
        throw new GerneralException("Unauthenticated", 401);
    }

    public function markTask($id)
    {
        $task = Task::findOrFail($id);
        if ($this->markPermission($task)) {
            $mark = Mark::firstOrCreate(['task_id' => $id]);
            $task->is_mark = $this->isMark($id);
            return $task;
        } else {
            throw new GerneralException("Unauthenticated", 401);
        }
    }

    public function unmarkTask($id)
    {
        $task = Task::findOrFail($id);
        if ($this->markPermission($task)) {
            $this->deleteMarkByTaskId($id);
            return $task;
        } else {
            throw new GerneralException("Unauthenticated", 401);
        }
    }

    public function getReasonTask()
    {
        if (Auth::user()->hasRole('admin')) {
            $data  = ReasonForReject::all();
        } else {
            $data = ReasonForReject::join('tasks', 'tasks.id', '=', 'reason_for_rejects.task_id')
                ->join('users', 'users.id', '=', 'tasks.created_by')
                ->where('tasks.created_by', '=', Auth::user()->id)
                ->get();
        }
        return $data;
    }

    public function filterTaskByStatus($status)
    {
        switch ($status) {
            case ('pending'):
                $tasks = $this->getTaskByStatus(0);
                break;
            case ('approved'):
                $tasks = $this->getTaskByStatus(1);
                break;
            case ('rejected'):
                $tasks = $this->getTaskByStatus(2);
                break;
            case ('done'):
                $tasks = $this->getTaskByStatus(3);
                break;
            case ('expired'):
                $tasks = $this->getTaskByStatus(4);
                break;
            case ('mark'):
                $tasks =  task::join('marks', 'marks.task_id', '=', 'tasks.id')->where('user_id', Auth::user()->id)->get(['tasks.*']);
                break;
            default:
                $tasks = Task::all();
        }

        $tasks = $tasks->map(function ($task, $key) {
            $task->is_mark = $this->isMark($task->id);
            return $task;
        });
        return $tasks;
    }

    public function createResonForTask($data)
    {
        $task = Task::findOrFail($data['task_id']);
        if (Auth::user()->id == $task->assign_to) {
            if (isset($data['audio'])) {
                $data['audio'] = $this->uploadFile($data['audio']);
            }
            $data = ReasonForReject::create($data);

            $title = __("main.task-status.title", ['status' => __('main.status.' . $task->status)]);
            $body = __("main.task-status.reject_body", ['user' => Auth::user()->name]);
            $this->notifyUser($task->created_by, $task->status, $title, $body, $task->id);

            return $data;
        }
        throw new GerneralException("Unauthenticated", 401);
    }


    protected function deleteTask($id)
    {
        DB::transaction(function () use ($id) {
            Task::destroy($id);
            $this->deleteMarkByTaskId($id);
        });
    }

    protected function deleteReason($task_id)
    {
        ReasonForReject::where('task_id', $task_id)->delete();
    }

    protected function updateStatus($task, $status)
    {
        $task->update(['status' => $status]);
        return $task;
    }

    protected function hashPass($password)
    {
        return Hash::make($password);
    }



    protected function notifyUser($id, $type, $title, $body, $task_id)
    {

        $user = User::find($id);

        $data = [
            "to" => $user->fcm_token,
            "notification" =>
            [
                'title' => $title,
                'body' => $body,
                'sound' => 'notification.mp3',
            ],
            "data" => [
                "type" => $type,
                'title' => $title,
                'body' => $body,
                'task' => $task_id,
                'user_id' => $id
            ],
        ];



        return send_notification_FCM($data);
    }

    protected function uploadFile($file)
    {
        $date = Carbon::now();
        $monthName = $date->format('FY');
        $file_name = time() . '.' . $file->extension();
        $full_path = \Storage::path("public/tasks/$monthName/files");
        $file->move($full_path, $file_name);
        return  "tasks/$monthName/files/" . $file_name;
    }

    protected function deleteOldFile($file)
    {
        \Storage::delete('/public/' . $file);
    }

    protected function isMark($id)
    {
        return Mark::where('task_id', $id)->where('user_id', Auth::user()->id)->exists();
    }

    protected function markPermission($task)
    {
        return in_array(Auth::user()->id, [$task->created_by, $task->assign_to]);
    }

    protected function deleteMarkByTaskId($id)
    {
        Mark::where('user_id', Auth::user()->id)->where('task_id', $id)->delete();
    }

    protected function getTaskByStatus($status)
    {
        $id = Auth::user()->id;
        return Task::where('status', $status)->where(function ($query) use ($id) {
            $query->where('assign_to', $id)->orWhere('created_by', $id);
        })->get();
    }

    protected function checkTaskExpired()
    {
        Task::where('end_date', '<', Carbon::now('Asia/Riyadh')->format('Y-m-d H:i:s'))->update(['status' => '4']);
    }
}
