<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    public function login(LoginRequest $req)
    {
        if (Auth::attempt($req->only(['login', 'password']))) {
            $user = Auth::user();
            foreach ($user->roles as $role) {
                $response = [
                    'role_name' => $role->name,
                    'role_id'   => $role->id,
                    'user_id'   => $user->id
                ];
            }

            return response()->json([
                'status' => 'Вы успешно авторизовались!',
                'id'     => $user->getAuthIdentifier(),
                'token'  => $user->getRememberToken(),
                'role'   => $response['role_name'],
                'name'   => $user->name,
                'login'  => $user->login
            ]);
        }
        return response()->json([
            'status' => '422'
        ]);
    }

    public function logout() {
        Auth::logout();

        return response()->json([
            'status' => 'Вы вышли!',
            'token' => ''
        ]);
    }
}
