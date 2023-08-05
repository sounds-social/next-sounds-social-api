<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sound>
 */
class SoundFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(2);

        return [
            'user_id' => User::all()->random()->id,
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->text(),
            'is_public' => $this->faker->randomElement(
                [true, false]
            ),
            'sound_file_path' => '/file/audio/test.mp3'
        ];
    }
}
