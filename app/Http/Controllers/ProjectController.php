<?php

namespace App\Http\Controllers;

use App\Data\ComplaintData;
use App\Data\ProjectData;
use App\Models\Complaint;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
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
}
