<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterService
{
    public static function create(array $data)
    {
        $user = User::create([
            'name' => data_get($data, 'name'),
            'login' => data_get($data, 'login'),
            'password' => Hash::make(data_get($data, 'password')),
            'remember_token' => Str::random(64)
        ]);

        $user->roles()->attach(data_get($data, 'role_user'));

        return true;
    }
}
