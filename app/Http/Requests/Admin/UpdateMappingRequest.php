<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMappingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Kosong berarti key sengaja dilepas dari permission mana pun.
            'permission_id' => [
                'nullable',
                Rule::exists(config('permission.table_names.permissions', 'permissions'), 'id'),
            ],
        ];
    }
}
