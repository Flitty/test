<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateFigureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'perimeter' => 'nullable',
            'points' => 'required|min:3',
            'points.*.latitude' => 'required|numeric|max:90|min:-90',
            'points.*.longitude' => 'required|numeric|max:180|min:-180',
        ];
    }
}
