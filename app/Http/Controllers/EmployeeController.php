<?php

namespace App\Http\Controllers;

use App\Data\EmployeeData;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $employees = Employee::paginate();

        return $this->successResponse('Employees retrieved successfully', EmployeeResource::collection($employees));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $employeeData = EmployeeData::from($request);

        $employee = Employee::create($employeeData->toArray());

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
        $employeeData = EmployeeData::from($request);

        $employee->update($employeeData->toArray());

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
}
