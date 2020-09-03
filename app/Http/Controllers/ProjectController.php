<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Resources\Project as ProjectResource;
use App\Http\Resources\ProjectCollection;
use App\Project;
use App\Task;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::paginate(10);

        return new ProjectCollection($projects);
    }

    /**
     * @param \App\Http\Requests\ProjectStoreRequest $request
     * @return \App\Http\Resources\Project
     */
    public function store(ProjectStoreRequest $request)
    {
        $project = Project::create($request->validated());

        return new ProjectResource($project);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Project $project
     * @return \App\Http\Resources\Project
     */
    public function show(Request $request, Project $project)
    {
        return new ProjectResource($project);
    }

    /**
     * @param \App\Http\Requests\ProjectUpdateRequest $request
     * @param \App\Project $project
     * @return \App\Http\Resources\Project
     */
    public function update(ProjectUpdateRequest $request, Project $project)
    {
        $project->update($request->validated());

        return new ProjectResource($project);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Project $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Project $project)
    {
        $project->delete();

        return response()->noContent(200);
    }

    public function get_project_tasks($project)
    {
        return Task::where('project_id', $project)->get();
    }
}
