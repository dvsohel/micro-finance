<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Member Information
            'member_id' => ['required', 'string', 'max:255', 'unique:members,member_id'],
            'name' => ['required', 'string', 'max:255'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'date_of_birth' => ['nullable', 'date'],
            'nid' => ['nullable', 'string', 'max:255', 'unique:members,nid'],

            // Contact Information
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'division' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'upazila' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],

            // Additional Information
            'occupation' => ['nullable', 'string', 'max:255'],
            'join_date' => ['nullable', 'date'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
            'remarks' => ['nullable', 'string'],
        ];
    }
}

