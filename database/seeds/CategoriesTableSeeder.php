<?php

use Illuminate\Database\Seeder;
use App\Models\Category;


class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name' => 'Moradia',
        ]);

        Category::create([
            'name' => 'Alimentação',
        ]);

        Category::create([
            'name' => 'Transporte',
        ]);

        Category::create([
            'name' => 'Planos por assinatura',
        ]);

        Category::create([
            'name' => 'Saúde',
        ]);

        Category::create([
            'name' => 'Lazer',
        ]);

        Category::create([
            'name' => 'Compras',
        ]);

        Category::create([
            'name' => 'Conhecimento',
        ]);

        Category::create([
            'name' => 'Investimentos',
        ]);

        Category::create([
            'name' => 'Renda',
        ]);
    }
}
