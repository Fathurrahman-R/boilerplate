<?php

namespace Database\Factories;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Resource>
 */
class ResourceFactory extends Factory
{
    protected $model = Resource::class;

    public function definition(): array
    {
        $name = Str::slug($this->faker->unique()->words(2, true), '_');

        return [
            'key' => $name,
            'label' => Str::headline($name),
            'group' => 'Umum',
            'description' => $this->faker->sentence(),
            'is_locked' => false,
        ];
    }

    public function locked(): static
    {
        return $this->state(fn (): array => ['is_locked' => true]);
    }
}
