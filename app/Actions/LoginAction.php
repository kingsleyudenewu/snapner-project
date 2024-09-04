<?php

namespace App\Actions;

use App\Models\User;

class LoginAction
{
    public function execute(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        return (new GenerateTokenAction())->execute($user);
    }
}
