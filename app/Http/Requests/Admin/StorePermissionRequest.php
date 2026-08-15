<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $table = config('permission.table_names.permissions', 'permissions');

        return [
            'name' => [
                'required', 'string', 'max:255',
                'regex:/^[a-z][a-z0-9_.-]*$/',
                Rule::unique($table, 'name')->where('guard_name', config('auth.defaults.guard', 'web')),
            ],
            'label' => ['nullable', 'string', 'max:255'],
            'group' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => 'Nama permission hanya boleh huruf kecil, angka, titik, tanda hubung, dan garis bawah.',
        ];
    }
}
