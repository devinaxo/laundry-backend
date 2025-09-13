<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clothesCategories = Category::where('name', 'Ropa')->first();
        $comfortersCategories = Category::where('name', 'Acolchados')->first();
        $featherComfortersCategories = Category::where('name', 'Acolchados con Pluma')->first();
        $mattressCoversCategories = Category::where('name', 'Fundas de Colchón')->first();

        $clothesSubs = [
            [
                'category_id' => $clothesCategories->id,
                'name' => 'Camperas',
                'description' => 'Camperas y abrigos',
                'price' => 1500.00,
                'active' => true
            ],
            [
                'category_id' => $clothesCategories->id,
                'name' => 'Ropa Variada',
                'description' => 'Ropa en general (camisas, pantalones, etc.)',
                'price' => 800.00,
                'active' => true
            ]
        ];

        // Subcategorías para Acolchados
        $comfortersSubs = [
            [
                'category_id' => $comfortersCategories->id,
                'name' => 'Una Plaza',
                'description' => 'Acolchado de una plaza',
                'price' => 2500.00,
                'active' => true
            ],
            [
                'category_id' => $comfortersCategories->id,
                'name' => 'Una Plaza y Media',
                'description' => 'Acolchado de una plaza y media',
                'price' => 3000.00,
                'active' => true
            ],
            [
                'category_id' => $comfortersCategories->id,
                'name' => 'Dos Plazas',
                'description' => 'Acolchado de dos plazas',
                'price' => 3500.00,
                'active' => true
            ],
            [
                'category_id' => $comfortersCategories->id,
                'name' => 'Dos Plazas y Media',
                'description' => 'Acolchado de dos plazas y media',
                'price' => 4000.00,
                'active' => true
            ]
        ];

        // Subcategorías para Acolchados con Pluma
        $featherComfortersSubs = [
            [
                'category_id' => $featherComfortersCategories->id,
                'name' => 'Una Plaza',
                'description' => 'Acolchado con pluma de una plaza',
                'price' => 3500.00,
                'active' => true
            ],
            [
                'category_id' => $featherComfortersCategories->id,
                'name' => 'Una Plaza y Media',
                'description' => 'Acolchado con pluma de una plaza y media',
                'price' => 4000.00,
                'active' => true
            ],
            [
                'category_id' => $featherComfortersCategories->id,
                'name' => 'Dos Plazas',
                'description' => 'Acolchado con pluma de dos plazas',
                'price' => 4500.00,
                'active' => true
            ],
            [
                'category_id' => $featherComfortersCategories->id,
                'name' => 'Dos Plazas y Media',
                'description' => 'Acolchado con pluma de dos plazas y media',
                'price' => 5000.00,
                'active' => true
            ]
        ];

        // Subcategorías para Fundas de Colchón
        $mattressCoversSubs = [
            [
                'category_id' => $mattressCoversCategories->id,
                'name' => 'Una Plaza',
                'description' => 'Funda de colchón de una plaza',
                'price' => 1200.00,
                'active' => true
            ],
            [
                'category_id' => $mattressCoversCategories->id,
                'name' => 'Una Plaza y Media',
                'description' => 'Funda de colchón de una plaza y media',
                'price' => 1400.00,
                'active' => true
            ],
            [
                'category_id' => $mattressCoversCategories->id,
                'name' => 'Dos Plazas',
                'description' => 'Funda de colchón de dos plazas',
                'price' => 1600.00,
                'active' => true
            ],
            [
                'category_id' => $mattressCoversCategories->id,
                'name' => 'Dos Plazas y Media',
                'description' => 'Funda de colchón de dos plazas y media',
                'price' => 1800.00,
                'active' => true
            ]
        ];

        $allSubs = array_merge(
            $clothesSubs,
            $comfortersSubs,
            $featherComfortersSubs,
            $mattressCoversSubs
        );

        foreach ($allSubs as $sub) {
            Subcategory::create($sub);
        }
    }
}
