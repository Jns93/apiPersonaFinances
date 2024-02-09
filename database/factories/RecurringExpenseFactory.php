<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\RecurringExpense;

class RecurringExpenseFactory extends Factory
{
    protected $model = RecurringExpense::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->randomNumber(),
            'user_id' => $this->faker->randomNumber(),
            'category_id' => $this->faker->randomNumber(),
            'subcategory_id' => $this->faker->randomNumber(),
            'name' => $this->faker->name(),
            'amount' => 10,
            'expiration_day' => 5
        ];
    }
}
