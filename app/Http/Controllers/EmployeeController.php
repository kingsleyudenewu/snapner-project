<?php

namespace App\Http\Controllers;

use App\Data\EmployeeData;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Notifications\WelcomeEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EmployeeController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $employees = Cache::remember('users', now()->addMinutes(15), function () {
            return Employee::with('project')->paginate();
        });

        return $this->successResponse('Employees retrieved successfully', EmployeeResource::collection($employees));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $employeeData = EmployeeData::from($request);

        $employee = Employee::create($employeeData->toArray());

        $employee->notify(new WelcomeEmployee($employee));

        return $this->createdResponse('Employee created successfully', new EmployeeResource($employee));
    }

    /**
     * @param Employee $employee
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Employee $employee): \Illuminate\Http\JsonResponse
    {
        return $this->successResponse('Employee retrieved successfully', new EmployeeResource($employee));
    }

    /**
     * @param Request $request
     * @param Employee $employee
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Employee $employee): \Illuminate\Http\JsonResponse
    {
        $validatedData = $request->validate(EmployeeData::rules($employee->id));
        $data = EmployeeData::from(...$validatedData);
        $employee->update($data->toArray());

        return $this->successResponse('Employee updated successfully', new EmployeeResource($employee));
    }

    /**
     * @param Employee $employee
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Employee $employee): \Illuminate\Http\JsonResponse
    {
        $employee->delete();

        return $this->successResponse('Employee deleted successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore(int $id): \Illuminate\Http\JsonResponse
    {
        $employee = Employee::withTrashed()->findOrFail($id);

        if (!$employee) {
            return $this->notFoundResponse('Employee is not deleted');
        }

        $employee->restore();

        return $this->successResponse('Employee restored successfully');
    }
}
