<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Subcategory;

class SubcategoryFactory extends Factory
{
    protected $model = Subcategory::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' =>  $this->faker->randomNumber(1, true),
            'category_id' => $this->faker->randomNumber(1, true),
            'name' => $this->faker->name(),
        ];
    }
}
