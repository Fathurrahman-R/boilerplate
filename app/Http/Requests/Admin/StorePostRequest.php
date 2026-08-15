<?php

namespace App\Http\Requests\Admin;

use App\Enums\PostStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Otorisasi ditangani PostPolicy di controller.
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
            'status' => ['required', Rule::enum(PostStatus::class)],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'Judul',
            'excerpt' => 'Ringkasan',
            'body' => 'Isi',
            'status' => 'Status',
        ];
    }
}
