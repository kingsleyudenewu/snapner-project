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
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $name = $request->query('name');
        $status = $request->query('status');

        // Query projects with filters
        $projects = Project::when($name, function($query, $name) {
            return $query->where('name', 'like', '%' . $name . '%');
        })->when($status, function($query, $status) {
            return $query->where('status', $status);
        })->paginate();

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

        return $this->createdResponse('Complaint created successfully', new ProjectResource($projectData));
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
        if ($request->user()->cannot('update', $project)) {
            abort(403, 'You are not authorized to update this project');
        }

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
        if (request()->user()->cannot('delete', $project)) {
            abort(403, 'You are not authorized to delete this project');
        }

        $project->delete();

        return $this->successResponse('Project deleted successfully');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function summary(Request $request)
    {
        $summary = Project::selectRaw('COUNT(*) as total_projects')
            ->selectRaw('(SELECT COUNT(*) FROM employees) as total_employees')
            ->selectRaw('status, COUNT(*) as total_by_status')
            ->groupBy('status')
            ->get();

        // Separate the total projects and total employees
        $totalProjects = $summary->first()->total_projects;
        $totalEmployees = $summary->first()->total_employees;

        // Extract the projects grouped by status
        $projectsGroupedByStatus = $summary->map(function ($item) {
            return [
                'status' => $item->status,
                'total' => $item->total_by_status
            ];
        });

        return $this->successResponse('Projects summary retrieved successfully', [
            'total_projects' => $totalProjects,
            'total_employees' => $totalEmployees,
            'projects_grouped_by_status' => $projectsGroupedByStatus
        ]);
    }
}
