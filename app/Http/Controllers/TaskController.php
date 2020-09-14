<?php

namespace App\Http\Controllers;

use App\Task;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\Task as TaskResource;
use App\Http\Resources\TaskCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{

    public function index()
    {
        $tasks = Task::all();

        return new TaskCollection($tasks);
    }

    public function store(TaskStoreRequest $request)
    {
        $task = Task::create($request->validated());

        return new TaskResource($task);
    }

    public function show(Request $request, Task $task)
    {
        return new TaskResource($task);
    }

    public function update(TaskUpdateRequest $request, Task $task)
    {
        $task->update($request->validated());

        return new TaskResource($task);
    }

    public function destroy($task)
    {
        $task_exists = Task::find($task);

        if (!$task_exists) {
            return response()->json([
                'message' => 'Task ' . $task . ' does not exist in Database'
            ]);
        }

        DB::beginTransaction();

        Task::destroy($task);
        DB::table('task_user')->where('task_id', $task)->delete();

        DB::commit();

        return response()->json([
            'message' => 'Task ' . $task . ' deleted successfully'
        ]);
    }

    public function mytasks()
    {
        $my_id = Auth::user()->id;

        $my_tasks = DB::table('task_user')
            ->select('task_id')
            ->where('user_id', $my_id)
            ->get();

        return $my_tasks;
    }

    public function assign_task_user(Request $request, $task_id)
    {

        $task_not_exist = [
            'code' => 404,
            'message' => 'Task: ' . $task_id . ' does not exist'
        ];

        if (!Task::find($task_id)) {
            return response()->json(['errors' => $task_not_exist]);
        }

        $user_id = $request->user_id;


        if (DB::table('task_user')->where('task_id', $task_id)->where('user_id', $user_id)->exists()) {
            return response()->json(['errors' => 'User already assigned to this task']);
        }

        DB::table('task_user')->insert(
            ['task_id' => $task_id, 'user_id' => $user_id]
        );


        $success_message = 'Users: ' . $user_id . ' assigned to task: ' . $task_id;

        return ['message' => $success_message];
    }

    public function toggle_complete(Request $request, $task_id)
    {
        $forbidden = [
            'code' => 403,
            'message' => 'I am sorry, you are not authorized to perform this action'
        ];
        $assigned_users = DB::table('task_user')->where('task_id', $task_id)->select('user_id')->get();
        $assigned_users_array = [];

        foreach ($assigned_users as $assigned_user) {
            array_push($assigned_users_array, $assigned_user->user_id);
        }
        $is_assigned = in_array(Auth::user()->id, $assigned_users_array);

        if (Auth::user()->is_admin || $is_assigned) {

            $task_finished = Task::find($task_id)->finished;

            $val = abs($task_finished -= 1);

            Task::find($task_id)->update(['finished' => $val]);

            $complete = $val ? 'complete' : 'incomplete';

            return ['message' => 'Task: ' . $task_id . ' marked as ' . $complete];
        }
        return response()->json(['errors' => $forbidden]);
    }
}
