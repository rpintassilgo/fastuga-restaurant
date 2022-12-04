<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Http\Resources\ProjectResource;
use App\Http\Requests\StoreUpdateProjectRequest;

class ProjectController extends Controller
{
    public function index()
    {
        return ProjectResource::collection(Project::all());
    }

    public function getProjectsOfUser(User $user)
    {
        return ProjectResource::collection($user->projects);
    }

    public function getProjectsInProgressOfUser(User $user)
    {
        return ProjectResource::collection($user->projects()->where('status', 'W')->get());
    }        

    public function show(Project $project)
    {
        return new ProjectResource($project);
    }

    public function showWithTasks(Project $project)
    {
        ProjectResource::$format = 'withTasks';
        return new ProjectResource($project);
    }

    public function store(StoreUpdateProjectRequest $request)
    {
        $newProject = Project::create($request->validated());
        return new ProjectResource($newProject);
    }

    public function update(StoreUpdateProjectRequest $request, Project $project)
    {
        $project->update($request->validated());
        return new ProjectResource($project);
    }

    public function destroy(Project $project)
    {
        Task::where("project_id", $project->id)->delete();
        $project->delete();
        return new ProjectResource($project);
    }
}
