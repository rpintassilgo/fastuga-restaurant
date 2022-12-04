<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use App\Http\Resources\TaskResource;
use App\Http\Requests\StoreUpdateTaskRequest;
use App\Http\Requests\UpdateCompleteTaskRequest;

class TaskController extends Controller
{
    public function getTasksOfUser(Request $request, User $user)
    {
        //TaskResource::$format = 'detailed';
        if (!$request->has('include_assigned')) {
            return TaskResource::collection($user->tasks->sortByDesc('id'));
        } else {
            return TaskResource::collection($user->tasks->merge($user->assigedTasks)->sortByDesc('id'));
        }
    }

    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    public function store(StoreUpdateTaskRequest $request)
    {
        $newTask = Task::create($request->validated());
        return new TaskResource($newTask);
    }

    public function update(StoreUpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());
        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        $task->assignedUsers()->detach();
        $task->delete();
        return new TaskResource($task);
    }

    public function update_completed(UpdateCompleteTaskRequest $request, Task $task)
    {
        $task->completed = $request->validated()['completed'];
        $task->save();
        return new TaskResource($task);
    }
}
