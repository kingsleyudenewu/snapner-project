<?php

namespace App\Data;

use App\Enums\StatusEnum;
use App\Models\User;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class ProjectData extends Data
{
    public function __construct(
        public string $name,
        public string $description,
        public string $status,
        public string $start_date,
        public string $end_date,
    ) {}

    public static function stopOnFirstFailure(): bool
    {
        return true;
    }

    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', Rule::enum(StatusEnum::class)],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
        ];
    }
}
