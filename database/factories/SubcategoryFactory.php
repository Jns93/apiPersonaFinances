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
            'id' =>  $this->faker->randomNumber(),
            'category_id' => $this->faker->randomNumber(),
            'name' => $this->faker->word(),
        ];
    }
}
