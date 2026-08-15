<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = rtrim($this->faker->sentence(6), '.');
        $status = $this->faker->randomElement(PostStatus::cases());

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.Str::random(6),
            'excerpt' => $this->faker->paragraph(),
            'body' => implode("\n\n", $this->faker->paragraphs(4)),
            'status' => $status->value,
            'published_at' => $status === PostStatus::Published ? $this->faker->dateTimeBetween('-6 months') : null,
        ];
    }
}
