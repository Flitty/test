<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * Class UpdateUserProfileRequest
 * @package App\Http\Requests
 */
class UpdateUserProfileRequest extends FormRequest
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
            'email' => [
                'nullable',
                Rule::unique('users')->ignore(Auth::id()),
            ],
            'name' => 'nullable|string',
            'password' => 'nullable||min:6|max:30|regex: /^\S*$/',
            'birthday' => 'nullable|date',
            'avatar' => 'nullable|string',
            'avatar_file' => 'nullable|image',
            'gender' => 'nullable|bool',
            'news_mailing' => 'nullable|bool',
            'biography' => 'nullable|string'
        ];
    }
}
