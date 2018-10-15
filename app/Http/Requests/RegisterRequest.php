<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class RegisterRequest
 * @package App\Http\Requests
 */
class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'unique:users|email|required',
            'name' => 'string|required',
            'password' => 'required||min:6|max:30|regex: /^\S*$/',
            'birthday' => 'nullable|date',
            'avatar' => 'nullable|string',
            'avatar_file' => 'nullable|image',
            'gender' => 'nullable|bool',
            'news_mailing' => 'nullable|bool',
            'biography' => 'nullable|string'
        ];
    }
}
