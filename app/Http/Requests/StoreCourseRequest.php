<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole(['owner', 'admin', 'teacher']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'about' => ['required', 'string'],
            'category_id' => ['required', 'integer'],
            'path_trailer' => ['required', 'string', 'max:255'],
            'thumbnail' => ['required', 'image', 'mimes:jpg,jpeg,png'],
            'course_keypoints.*' => ['required', 'string', 'max:255'],
        ];
    }
}
