<?php

namespace App\Http\Requests\Admin;

use App\Enums\ResourceAction;
use App\Support\Resources\ResourceKey;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreResourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Key selalu di-slugify lebih dulu. Admin mengetik "Laporan Bulanan",
     * yang tersimpan "laporan_bulanan" — bentuk yang dipakai kode.
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('key')) {
            $this->merge(['key' => Str::of($this->input('key'))->slug('_')->lower()->value()]);
        }
    }

    public function rules(): array
    {
        return [
            'key' => [
                'required', 'string', 'max:255',
                'regex:'.ResourceKey::RESOURCE_PATTERN,
                Rule::unique('resources', 'key'),
            ],
            'label' => ['required', 'string', 'max:255'],
            'group' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'actions' => ['required', 'array', 'min:1'],
            'actions.*' => [Rule::enum(ResourceAction::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'key.regex' => 'Nama resource hanya boleh huruf kecil, angka, tanda hubung, dan garis bawah.',
            'actions.required' => 'Pilih minimal satu aksi.',
        ];
    }
}
