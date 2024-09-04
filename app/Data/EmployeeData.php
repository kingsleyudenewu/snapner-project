<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class EmployeeData extends Data
{
    public function __construct(
        public string $name,
        public int $project_id,
        public string $email,
        public string $position,
    ) {}

    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'email' => ['required', 'email', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
        ];
    }

    public static function stopOnFirstFailure(): bool
    {
        return true;
    }
}
