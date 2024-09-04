<?php

namespace App\Http\Controllers;

use App\Data\ProjectData;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $projects = Project::paginate();

        return $this->successResponse('Projects retrieved successfully', ProjectResource::collection($projects));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $projectData = ProjectData::from($request);

        Project::create($projectData->toArray());

        return $this->createdResponse('Complaint created successfully');
    }

    /**
     * @param Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Project $project): \Illuminate\Http\JsonResponse
    {
        return $this->successResponse('Project retrieved successfully', new ProjectResource($project));
    }

    /**
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Project $project): \Illuminate\Http\JsonResponse
    {
        $projectData = ProjectData::from($request);

        $project->update($projectData->toArray());

        return $this->successResponse('Project updated successfully', new ProjectResource($project));
    }

    /**
     * @param Project $project
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Project $project): \Illuminate\Http\JsonResponse
    {
        $project->delete();

        return $this->successResponse('Project deleted successfully');
    }
}
