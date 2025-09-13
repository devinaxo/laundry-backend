<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Ropa',
                'description' => 'Prendas de vestir en general',
                'active' => true
            ],
            [
                'name' => 'Acolchados',
                'description' => 'Acolchados, edredones y frazadas',
                'active' => true
            ],
            [
                'name' => 'Acolchados con Pluma',
                'description' => 'Acolchados rellenos de pluma',
                'active' => true
            ],
            [
                'name' => 'Fundas de Colchón',
                'description' => 'Protectores y fundas de colchón',
                'active' => true
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
