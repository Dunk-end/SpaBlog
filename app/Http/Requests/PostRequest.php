<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
            'desc' => 'required|min:8',
            'file' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Поле название обязательное!',
            'name.min' => 'Минимальная длина поля название не менее 3!',
            'desc.required' => 'Поле описание обязательное!',
            'desc.min' => 'Минимальная длина описания не менее 8!',
            'file.required' => 'Изображение обязательно!'
        ];
    }
}
