<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rolesTable = config('permission.table_names.roles', 'roles');
        $permissionsTable = config('permission.table_names.permissions', 'permissions');

        return [
            'name' => [
                'required', 'string', 'max:255', 'regex:/^[a-z][a-z0-9_-]*$/',
                Rule::unique($rolesTable, 'name')->ignore($this->route('role')),
            ],
            'label' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'permissions' => ['array'],
            'permissions.*' => [Rule::exists($permissionsTable, 'id')],
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => 'Nama role hanya boleh huruf kecil, angka, tanda hubung, dan garis bawah.',
        ];
    }
}
