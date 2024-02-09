<?php

namespace Database\Factories;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Factories\Factory;


class ExpenseFactory extends Factory
{
    protected $model = Expense::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' =>  $this->faker->randomNumber(),
            'name' => $this->faker->name(),
        ];
    }
}
