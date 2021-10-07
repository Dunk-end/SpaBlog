<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:3',
            'login' => 'required|min:3|unique:users',
            'password' => 'required|alpha_dash|min:8|same:password_confirm',
            'password_confirm' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Поле имя обязательное!',
            'name.min' => 'Минимальная длина поля имя не менее 3!',
            'login.required' => 'Поле логин обязательное!',
            'login.unique' => 'Пользователь с таким логин уже существует!',
            'login.min' => 'Минимальная длина поля логин не менее 3!',
            'password.required' => 'Поле пароль обязательное!',
            'password.alpha_dash' => 'Поле пароль должно содержать только буквенные символы, цифры, знаки подчёркивания и дефисы!',
            'password.same' => 'Пароли не совпадают!',
            'password.min' => 'Минимальная длина пароля не менее 8!',
            'password_confirm.required' => 'Поле проверки пароля обязательное!'
        ];
    }
}
