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
            'id' => $this->faker->randomNumber(1, true),
            'user_id' => $this->faker->randomNumber(1, true),
            'category_id' => $this->faker->randomNumber(1, true),
            'subcategory_id' => $this->faker->randomNumber(1, true),
            'name' => $this->faker->name(),
            'amount' => 10,
            'expiration_day' => 5
        ];
    }
}
