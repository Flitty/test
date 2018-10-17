<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class ValidatePointRequest
 * @package App\Http\Requests
 */
class ValidatePointRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'points' => 'required|min:4',
            'points.*.latitude' => 'required',
            'points.*.longitude' => 'required',
        ];
    }
}
