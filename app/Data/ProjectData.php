<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class ProjectData extends Data
{
    public function __construct(
        public string $name,
        public string $description,
        public string $phone_number,
    ) {}
}
