<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'user_id' => User::all()->random()->id,
            'title' => $this->faker->unique()->sentence(),
            'description' => $this->faker->text(),
            'is_public' => $this->faker->randomElement(
                [true, false]
            ),
            'sound_file_path' => '/example/path/to/sound/file.mp3'
        ];
    }
}
