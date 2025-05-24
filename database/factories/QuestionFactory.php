<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->sentence(),
            'is_active' => $this->faker->randomElement([0, 1]),
            'amount_appear' => $this->faker->numberBetween(0, 10),
            'question_category_id' => $this->faker->numberBetween(0, 10)
        ];
    }
}
