<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\RegisterService;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Exception;


class RegisterController extends Controller
{
    use RegistersUsers;

    protected function create(RegisterRequest $req)
    {
        if (RegisterService::create($req->all())) {

            return response()->json([
                'status' => 'Вы успешно зарегистрировались!'
            ]);
        }

        return response()->json([
            'status' => '422'
        ]);
    }

    public function getUserRole()
    {
        return Role::get()->all();
    }

//    public function googleRedirect()
//    {
//        return Socialite::driver('google')->redirect();
////        return response()->json([
////            'status' => '123123123'
////        ]);
//    }
//
//    public function loginWithGoogle()
//    {
//        try {
//            $user = Socialite::driver('google')->user();
//            $isUser = User::where('google_id', $user->id)->first();
//
//            if ($isUser) {
//                Auth::login($isUser);
//
//                return response()->json([
//                    'status' => 'Готово!'
//                ]);
//            } else {
//                $createUser = User::create([
//                    'name' => $user->name,
//                    'email' => $user->email,
//                    'google_id' => $user->id,
//                    'password' => Hash::make(Str::random(32)),
//                ]);
//
//                Auth::login($createUser);
//
////                return redirect('/');
//                return response()->json([
//                    'status' => 'Готово!'
//                ]);
//            }
//
//        } catch (Exception $exception) {
//            dd($exception->getMessage());
//        }
//    }
}
