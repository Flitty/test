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
            'points' => 'required|min:1',
            'points.*.latitude' => 'required|numeric|max:90|min:-90',
            'points.*.longitude' => 'required|numeric|max:180|min:-180',
        ];
    }
}
