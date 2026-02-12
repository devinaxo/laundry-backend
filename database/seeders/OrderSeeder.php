<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $clients = Client::where('active', true)->get();
        $subcategories = Subcategory::where('active', true)->get();

        // Check if we have enough clients
        if ($clients->count() < 27) {
            throw new \Exception('Not enough clients in database. Please run ClientSeeder first.');
        }

        // Create a mapping of client index to actual client ID
        $clientIds = $clients->pluck('id')->toArray();

        // Helper function to get random subcategory by name pattern
        $getSubcategory = function($name) use ($subcategories) {
            return $subcategories->where('name', $name)->first();
        };
        
        // Helper function to get client ID by index (0-based)
        $getClientId = function($index) use ($clientIds) {
            return $clientIds[$index] ?? null;
        };

        $orders = [
            // Order 1 - María González - Completed
            [
                'client_index' => 0,
                'order_number' => 'ORD-2026-001',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(60),
                'created_at' => Carbon::now()->subDays(60),
                'estimated_delivery_date' => Carbon::now()->subDays(55),
                'actual_delivery_date' => Carbon::now()->subDays(54),
                'notes' => 'Acolchado blanco con detalles florales, camisas de algodón',
                'items' => [
                    ['subcategory' => 'Dos Plazas', 'quantity' => 1, 'notes' => 'Acolchado blanco'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 5, 'notes' => 'Camisas y pantalones'],
                ]
            ],
            // Order 2 - Carlos Rodríguez
            [
                'client_index' => 1,
                'order_number' => 'ORD-2026-002',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(58),
                'created_at' => Carbon::now()->subDays(58),
                'estimated_delivery_date' => Carbon::now()->subDays(53),
                'actual_delivery_date' => Carbon::now()->subDays(53),
                'notes' => 'Campera de cuero marrón oscuro, campera jean celeste, funda roja',
                'items' => [
                    ['subcategory' => 'Camperas', 'quantity' => 2, 'notes' => 'Campera de cuero y campera de jean'],
                    ['subcategory' => 'Una Plaza y Media', 'quantity' => 1, 'notes' => 'Funda de colchón roja'],
                ]
            ],
            // Order 3 - Ana Martínez
            [
                'client_index' => 2,
                'order_number' => 'ORD-2026-003',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(55),
                'created_at' => Carbon::now()->subDays(55),
                'estimated_delivery_date' => Carbon::now()->subDays(50),
                'actual_delivery_date' => Carbon::now()->subDays(49),
                'notes' => 'Acolchado beige con pluma de ganso, funda king size a juego',
                'items' => [
                    ['subcategory' => 'Dos Plazas y Media', 'quantity' => 1, 'notes' => 'Acolchado con pluma beige'],
                    ['subcategory' => 'Dos Plazas', 'quantity' => 1, 'notes' => 'Funda de colchón'],
                ]
            ],
            // Order 4 - Roberto López
            [
                'client_index' => 3,
                'order_number' => 'ORD-2026-004',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(52),
                'created_at' => Carbon::now()->subDays(52),
                'estimated_delivery_date' => Carbon::now()->subDays(47),
                'actual_delivery_date' => Carbon::now()->subDays(47),
                'notes' => 'Variedad de ropa familiar, camperas de invierno gruesas con capucha',
                'items' => [
                    ['subcategory' => 'Ropa Variada', 'quantity' => 10, 'notes' => 'Ropa de toda la familia'],
                    ['subcategory' => 'Camperas', 'quantity' => 3, 'notes' => 'Camperas de invierno'],
                ]
            ],
            // Order 5 - Laura Fernández
            [
                'client_index' => 4,
                'order_number' => 'ORD-2026-005',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(50),
                'created_at' => Carbon::now()->subDays(50),
                'estimated_delivery_date' => Carbon::now()->subDays(45),
                'actual_delivery_date' => Carbon::now()->subDays(45),
                'notes' => 'Acolchados infantiles con personajes animados, fundas coloridas',
                'items' => [
                    ['subcategory' => 'Una Plaza', 'quantity' => 2, 'notes' => 'Acolchados de niños'],
                    ['subcategory' => 'Una Plaza', 'quantity' => 2, 'notes' => 'Fundas de colchón infantiles'],
                ]
            ],
            // Order 6 - Jorge Sánchez
            [
                'client_index' => 5,
                'order_number' => 'ORD-2026-006',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(48),
                'created_at' => Carbon::now()->subDays(48),
                'estimated_delivery_date' => Carbon::now()->subDays(43),
                'actual_delivery_date' => Carbon::now()->subDays(42),
                'notes' => 'Acolchados con olor a guardado, ropa variada necesita limpieza profunda',
                'items' => [
                    ['subcategory' => 'Dos Plazas', 'quantity' => 2, 'notes' => 'Acolchados matrimoniales'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 15, 'notes' => 'Ropa guardada, necesita limpieza profunda'],
                    ['subcategory' => 'Dos Plazas', 'quantity' => 1, 'notes' => 'Funda de colchón'],
                ]
            ],
            // Order 7 - Patricia Díaz
            [
                'client_index' => 6,
                'order_number' => 'ORD-2026-007',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(46),
                'created_at' => Carbon::now()->subDays(46),
                'estimated_delivery_date' => Carbon::now()->subDays(41),
                'actual_delivery_date' => Carbon::now()->subDays(40),
                'notes' => 'Acolchado premium con pluma de ganso, camperas de diferentes talles',
                'items' => [
                    ['subcategory' => 'Dos Plazas y Media', 'quantity' => 1, 'notes' => 'Acolchado premium con pluma de ganso'],
                    ['subcategory' => 'Camperas', 'quantity' => 4, 'notes' => 'Camperas de toda la familia'],
                ]
            ],
            // Order 8 - Miguel Torres
            [
                'client_index' => 7,
                'order_number' => 'ORD-2026-008',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(44),
                'created_at' => Carbon::now()->subDays(44),
                'estimated_delivery_date' => Carbon::now()->subDays(39),
                'actual_delivery_date' => Carbon::now()->subDays(39),
                'notes' => 'Camisas blancas y celestes de trabajo, requieren planchado',
                'items' => [
                    ['subcategory' => 'Ropa Variada', 'quantity' => 8, 'notes' => 'Camisas de trabajo'],
                ]
            ],
            // Order 9 - Silvia Ramírez
            [
                'client_index' => 8,
                'order_number' => 'ORD-2026-009',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(42),
                'created_at' => Carbon::now()->subDays(42),
                'estimated_delivery_date' => Carbon::now()->subDays(37),
                'actual_delivery_date' => Carbon::now()->subDays(37),
                'notes' => 'Acolchados y fundas a rayas verticales, ropa variada',
                'items' => [
                    ['subcategory' => 'Una Plaza y Media', 'quantity' => 2, 'notes' => 'Acolchados'],
                    ['subcategory' => 'Una Plaza y Media', 'quantity' => 2, 'notes' => 'Fundas'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 6, 'notes' => null],
                ]
            ],
            // Order 10 - Fernando Moreno
            [
                'client_index' => 9,
                'order_number' => 'ORD-2026-010',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(40),
                'created_at' => Carbon::now()->subDays(40),
                'estimated_delivery_date' => Carbon::now()->subDays(35),
                'actual_delivery_date' => Carbon::now()->subDays(35),
                'notes' => 'Acolchado azul marino con relleno de pluma',
                'items' => [
                    ['subcategory' => 'Dos Plazas', 'quantity' => 1, 'notes' => 'Acolchado azul con pluma'],
                ]
            ],
            // Order 11 - Gabriela Castro
            [
                'client_index' => 10,
                'order_number' => 'ORD-2026-011',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(38),
                'created_at' => Carbon::now()->subDays(38),
                'estimated_delivery_date' => Carbon::now()->subDays(33),
                'actual_delivery_date' => Carbon::now()->subDays(33),
                'notes' => 'Acolchado rosa con estampado de princesas, funda blanca bordada',
                'items' => [
                    ['subcategory' => 'Una Plaza', 'quantity' => 1, 'notes' => 'Acolchado rosa infantil'],
                    ['subcategory' => 'Una Plaza', 'quantity' => 1, 'notes' => 'Funda blanca'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 4, 'notes' => 'Ropa de niños'],
                ]
            ],
            // Order 12 - Claudia Vargas
            [
                'client_index' => 12,
                'order_number' => 'ORD-2026-012',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(36),
                'created_at' => Carbon::now()->subDays(36),
                'estimated_delivery_date' => Carbon::now()->subDays(31),
                'actual_delivery_date' => Carbon::now()->subDays(30),
                'notes' => 'Ropa de invierno guardada, acolchado grueso con funda decorativa',
                'items' => [
                    ['subcategory' => 'Ropa Variada', 'quantity' => 12, 'notes' => 'Ropa de temporada'],
                    ['subcategory' => 'Dos Plazas y Media', 'quantity' => 1, 'notes' => 'Acolchado grueso'],
                ]
            ],
            // Order 13 - Daniel Acosta
            [
                'client_index' => 13,
                'order_number' => 'ORD-2026-013',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(34),
                'created_at' => Carbon::now()->subDays(34),
                'estimated_delivery_date' => Carbon::now()->subDays(29),
                'actual_delivery_date' => Carbon::now()->subDays(29),
                'notes' => 'Camperas de tela impermeable, vestidos de seda y faldas',
                'items' => [
                    ['subcategory' => 'Camperas', 'quantity' => 2, 'notes' => null],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 7, 'notes' => 'Vestidos y faldas'],
                ]
            ],
            // Order 14 - Mónica Flores
            [
                'client_index' => 14,
                'order_number' => 'ORD-2026-014',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(32),
                'created_at' => Carbon::now()->subDays(32),
                'estimated_delivery_date' => Carbon::now()->subDays(27),
                'actual_delivery_date' => Carbon::now()->subDays(27),
                'notes' => 'Acolchados livianos de verano, fundas con diseños geométricos',
                'items' => [
                    ['subcategory' => 'Dos Plazas', 'quantity' => 2, 'notes' => 'Acolchados livianos'],
                    ['subcategory' => 'Dos Plazas', 'quantity' => 2, 'notes' => 'Fundas decorativas'],
                ]
            ],
            // Order 15 - Alejandro Ruiz
            [
                'client_index' => 15,
                'order_number' => 'ORD-2026-015',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(30),
                'created_at' => Carbon::now()->subDays(30),
                'estimated_delivery_date' => Carbon::now()->subDays(25),
                'actual_delivery_date' => Carbon::now()->subDays(25),
                'notes' => 'Ropa deportiva de algodón, campera negra con cierre plateado',
                'items' => [
                    ['subcategory' => 'Ropa Variada', 'quantity' => 6, 'notes' => 'Ropa sport'],
                    ['subcategory' => 'Camperas', 'quantity' => 1, 'notes' => 'Campera negra'],
                ]
            ],
            // Order 16 - Susana Gutiérrez
            [
                'client_index' => 16,
                'order_number' => 'ORD-2026-016',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(28),
                'created_at' => Carbon::now()->subDays(28),
                'estimated_delivery_date' => Carbon::now()->subDays(23),
                'actual_delivery_date' => Carbon::now()->subDays(22),
                'notes' => 'Tres acolchados infantiles con motivos de animales, ropa de niños',
                'items' => [
                    ['subcategory' => 'Una Plaza', 'quantity' => 3, 'notes' => 'Acolchados infantiles'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 8, 'notes' => null],
                ]
            ],
            // Order 17 - Pablo Herrera
            [
                'client_index' => 17,
                'order_number' => 'ORD-2026-017',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(26),
                'created_at' => Carbon::now()->subDays(26),
                'estimated_delivery_date' => Carbon::now()->subDays(21),
                'actual_delivery_date' => Carbon::now()->subDays(21),
                'notes' => 'Acolchado king size con pluma premium, funda satinada gris',
                'items' => [
                    ['subcategory' => 'Dos Plazas y Media', 'quantity' => 1, 'notes' => 'Acolchado con pluma'],
                    ['subcategory' => 'Dos Plazas y Media', 'quantity' => 1, 'notes' => 'Funda king size'],
                ]
            ],
            // Order 18 - Beatriz Méndez
            [
                'client_index' => 18,
                'order_number' => 'ORD-2026-018',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(24),
                'created_at' => Carbon::now()->subDays(24),
                'estimated_delivery_date' => Carbon::now()->subDays(19),
                'actual_delivery_date' => Carbon::now()->subDays(19),
                'notes' => 'Lavado familiar completo, camperas de invierno con capucha',
                'items' => [
                    ['subcategory' => 'Ropa Variada', 'quantity' => 15, 'notes' => 'Lavado completo familia'],
                    ['subcategory' => 'Camperas', 'quantity' => 3, 'notes' => 'Camperas de invierno'],
                ]
            ],
            // Order 19 - Raúl Jiménez
            [
                'client_index' => 19,
                'order_number' => 'ORD-2026-019',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(22),
                'created_at' => Carbon::now()->subDays(22),
                'estimated_delivery_date' => Carbon::now()->subDays(17),
                'actual_delivery_date' => Carbon::now()->subDays(17),
                'notes' => 'Acolchado y funda de tonos neutros, buen estado',
                'items' => [
                    ['subcategory' => 'Una Plaza y Media', 'quantity' => 1, 'notes' => null],
                    ['subcategory' => 'Una Plaza y Media', 'quantity' => 1, 'notes' => 'Funda'],
                ]
            ],
            // Order 20 - Elena Navarro
            [
                'client_index' => 20,
                'order_number' => 'ORD-2026-020',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(20),
                'created_at' => Carbon::now()->subDays(20),
                'estimated_delivery_date' => Carbon::now()->subDays(15),
                'actual_delivery_date' => Carbon::now()->subDays(15),
                'notes' => 'Acolchado verde oliva, ropa casual de algodón',
                'items' => [
                    ['subcategory' => 'Dos Plazas', 'quantity' => 1, 'notes' => 'Acolchado verde'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 5, 'notes' => null],
                ]
            ],
            // Order 21 - Gustavo Pereyra
            [
                'client_index' => 21,
                'order_number' => 'ORD-2026-021',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(18),
                'created_at' => Carbon::now()->subDays(18),
                'estimated_delivery_date' => Carbon::now()->subDays(13),
                'actual_delivery_date' => Carbon::now()->subDays(12),
                'notes' => 'Camperas deportivas, uniformes y ropa de trabajo',
                'items' => [
                    ['subcategory' => 'Camperas', 'quantity' => 2, 'notes' => 'Camperas sport'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 10, 'notes' => 'Ropa de trabajo'],
                ]
            ],
            // Order 22 - Mariana Domínguez
            [
                'client_index' => 22,
                'order_number' => 'ORD-2026-022',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(16),
                'created_at' => Carbon::now()->subDays(16),
                'estimated_delivery_date' => Carbon::now()->subDays(11),
                'actual_delivery_date' => Carbon::now()->subDays(11),
                'notes' => 'Acolchados infantiles con estampados, fundas a juego, ropa de niños',
                'items' => [
                    ['subcategory' => 'Una Plaza', 'quantity' => 2, 'notes' => 'Acolchados niños'],
                    ['subcategory' => 'Una Plaza', 'quantity' => 2, 'notes' => 'Fundas infantiles'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 6, 'notes' => 'Ropa de niños'],
                ]
            ],
            // Order 23 - Sergio Villalba
            [
                'client_index' => 23,
                'order_number' => 'ORD-2026-023',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(14),
                'created_at' => Carbon::now()->subDays(14),
                'estimated_delivery_date' => Carbon::now()->subDays(9),
                'actual_delivery_date' => Carbon::now()->subDays(9),
                'notes' => 'Dos acolchados matrimoniales de buena calidad, ropa variada',
                'items' => [
                    ['subcategory' => 'Dos Plazas', 'quantity' => 2, 'notes' => 'Acolchados matrimonio'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 12, 'notes' => null],
                ]
            ],
            // Order 24 - Cecilia Rojas
            [
                'client_index' => 24,
                'order_number' => 'ORD-2026-024',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(12),
                'created_at' => Carbon::now()->subDays(12),
                'estimated_delivery_date' => Carbon::now()->subDays(7),
                'actual_delivery_date' => Carbon::now()->subDays(7),
                'notes' => 'Acolchado king size premium con pluma, camperas de cuero',
                'items' => [
                    ['subcategory' => 'Dos Plazas y Media', 'quantity' => 1, 'notes' => 'Acolchado premium pluma'],
                    ['subcategory' => 'Camperas', 'quantity' => 2, 'notes' => null],
                ]
            ],
            // Order 25 - Martín Molina
            [
                'client_index' => 25,
                'order_number' => 'ORD-2026-025',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(10),
                'created_at' => Carbon::now()->subDays(10),
                'estimated_delivery_date' => Carbon::now()->subDays(5),
                'actual_delivery_date' => Carbon::now()->subDays(5),
                'notes' => 'Gran cantidad de ropa de temporada, diferentes tipos de telas',
                'items' => [
                    ['subcategory' => 'Ropa Variada', 'quantity' => 20, 'notes' => 'Ropa de temporada'],
                ]
            ],
            // Order 26 - Andrea Silva
            [
                'client_index' => 26,
                'order_number' => 'ORD-2026-026',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(9),
                'created_at' => Carbon::now()->subDays(9),
                'estimated_delivery_date' => Carbon::now()->subDays(4),
                'actual_delivery_date' => Carbon::now()->subDays(4),
                'notes' => 'Acolchado mediano con manchas leves, ropa casual',
                'items' => [
                    ['subcategory' => 'Una Plaza y Media', 'quantity' => 1, 'notes' => null],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 4, 'notes' => null],
                ]
            ],
            // Order 27 - Diego Arias
            [
                'client_index' => 27,
                'order_number' => 'ORD-2026-027',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(8),
                'created_at' => Carbon::now()->subDays(8),
                'estimated_delivery_date' => Carbon::now()->subDays(3),
                'actual_delivery_date' => Carbon::now()->subDays(3),
                'notes' => 'Acolchado y funda rojos con bordados decorativos',
                'items' => [
                    ['subcategory' => 'Dos Plazas', 'quantity' => 1, 'notes' => 'Acolchado rojo'],
                    ['subcategory' => 'Dos Plazas', 'quantity' => 1, 'notes' => 'Funda roja'],
                ]
            ],
            // Order 28 - Valeria Cabrera
            [
                'client_index' => 28,
                'order_number' => 'ORD-2026-028',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(7),
                'created_at' => Carbon::now()->subDays(7),
                'estimated_delivery_date' => Carbon::now()->subDays(2),
                'actual_delivery_date' => Carbon::now()->subDays(2),
                'notes' => 'Camperas de diferentes talles familiares, ropa de diario',
                'items' => [
                    ['subcategory' => 'Camperas', 'quantity' => 4, 'notes' => 'Camperas familia completa'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 8, 'notes' => null],
                ]
            ],
            // Order 29 - María González - Second Order
            [
                'client_index' => 0,
                'order_number' => 'ORD-2026-029',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(45),
                'created_at' => Carbon::now()->subDays(45),
                'estimated_delivery_date' => Carbon::now()->subDays(40),
                'actual_delivery_date' => Carbon::now()->subDays(40),
                'notes' => 'Ropa variada de uso diario, acolchado infantil celeste',
                'items' => [
                    ['subcategory' => 'Ropa Variada', 'quantity' => 7, 'notes' => null],
                    ['subcategory' => 'Una Plaza', 'quantity' => 1, 'notes' => 'Acolchado infantil'],
                ]
            ],
            // Order 30 - Carlos Rodríguez - Second Order
            [
                'client_index' => 1,
                'order_number' => 'ORD-2026-030',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(43),
                'created_at' => Carbon::now()->subDays(43),
                'estimated_delivery_date' => Carbon::now()->subDays(38),
                'actual_delivery_date' => Carbon::now()->subDays(38),
                'notes' => 'Acolchado king size con pluma de ganso, campera deportiva',
                'items' => [
                    ['subcategory' => 'Dos Plazas y Media', 'quantity' => 1, 'notes' => 'Acolchado pluma'],
                    ['subcategory' => 'Camperas', 'quantity' => 1, 'notes' => null],
                ]
            ],
            // Order 31 - Ana Martínez - Second Order
            [
                'client_index' => 2,
                'order_number' => 'ORD-2026-031',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(41),
                'created_at' => Carbon::now()->subDays(41),
                'estimated_delivery_date' => Carbon::now()->subDays(36),
                'actual_delivery_date' => Carbon::now()->subDays(36),
                'notes' => 'Ropa casual de fin de semana, pantalones y remeras',
                'items' => [
                    ['subcategory' => 'Ropa Variada', 'quantity' => 9, 'notes' => 'Ropa casual'],
                ]
            ],
            // Order 32 - Roberto López - Second Order
            [
                'client_index' => 3,
                'order_number' => 'ORD-2026-032',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(39),
                'created_at' => Carbon::now()->subDays(39),
                'estimated_delivery_date' => Carbon::now()->subDays(34),
                'actual_delivery_date' => Carbon::now()->subDays(33),
                'notes' => 'Dos acolchados medianos con fundas a cuadros, ropa casual',
                'items' => [
                    ['subcategory' => 'Una Plaza y Media', 'quantity' => 2, 'notes' => 'Acolchados'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 5, 'notes' => null],
                ]
            ],
            // Order 33 - Laura Fernández - Second Order
            [
                'client_index' => 4,
                'order_number' => 'ORD-2026-033',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(37),
                'created_at' => Carbon::now()->subDays(37),
                'estimated_delivery_date' => Carbon::now()->subDays(32),
                'actual_delivery_date' => Carbon::now()->subDays(32),
                'notes' => 'Acolchado matrimonial liso, camperas infantiles forradas',
                'items' => [
                    ['subcategory' => 'Dos Plazas', 'quantity' => 1, 'notes' => null],
                    ['subcategory' => 'Camperas', 'quantity' => 2, 'notes' => 'Camperas niños'],
                ]
            ],
            // Order 34 - Jorge Sánchez - Second Order
            [
                'client_index' => 5,
                'order_number' => 'ORD-2026-034',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(35),
                'created_at' => Carbon::now()->subDays(35),
                'estimated_delivery_date' => Carbon::now()->subDays(30),
                'actual_delivery_date' => Carbon::now()->subDays(30),
                'notes' => 'Ropa variada de algodón y poliester',
                'items' => [
                    ['subcategory' => 'Ropa Variada', 'quantity' => 11, 'notes' => 'Ropa variada'],
                ]
            ],
            // Order 35 - Patricia Díaz - Second Order
            [
                'client_index' => 6,
                'order_number' => 'ORD-2026-035',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(33),
                'created_at' => Carbon::now()->subDays(33),
                'estimated_delivery_date' => Carbon::now()->subDays(28),
                'actual_delivery_date' => Carbon::now()->subDays(28),
                'notes' => 'Acolchado y funda celeste claro con nubes bordadas',
                'items' => [
                    ['subcategory' => 'Una Plaza', 'quantity' => 1, 'notes' => 'Acolchado celeste'],
                    ['subcategory' => 'Una Plaza', 'quantity' => 1, 'notes' => 'Funda celeste'],
                ]
            ],
            // Order 36 - Miguel Torres - Second Order
            [
                'client_index' => 7,
                'order_number' => 'ORD-2026-036',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(31),
                'created_at' => Carbon::now()->subDays(31),
                'estimated_delivery_date' => Carbon::now()->subDays(26),
                'actual_delivery_date' => Carbon::now()->subDays(26),
                'notes' => 'Uniformes de trabajo azules, campera impermeable',
                'items' => [
                    ['subcategory' => 'Ropa Variada', 'quantity' => 14, 'notes' => 'Ropa de trabajo'],
                    ['subcategory' => 'Camperas', 'quantity' => 1, 'notes' => 'Campera trabajo'],
                ]
            ],
            // Order 37 - Silvia Ramírez - Second Order
            [
                'client_index' => 8,
                'order_number' => 'ORD-2026-037',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(29),
                'created_at' => Carbon::now()->subDays(29),
                'estimated_delivery_date' => Carbon::now()->subDays(24),
                'actual_delivery_date' => Carbon::now()->subDays(24),
                'notes' => 'Acolchado amarillo mostaza con borde decorativo',
                'items' => [
                    ['subcategory' => 'Dos Plazas', 'quantity' => 1, 'notes' => 'Acolchado amarillo'],
                ]
            ],
            // Order 38 - Fernando Moreno - Second Order
            [
                'client_index' => 9,
                'order_number' => 'ORD-2026-038',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(27),
                'created_at' => Carbon::now()->subDays(27),
                'estimated_delivery_date' => Carbon::now()->subDays(22),
                'actual_delivery_date' => Carbon::now()->subDays(22),
                'notes' => 'Acolchado king size con pluma negra, funda satinada',
                'items' => [
                    ['subcategory' => 'Dos Plazas y Media', 'quantity' => 1, 'notes' => 'Acolchado con pluma negro'],
                    ['subcategory' => 'Dos Plazas y Media', 'quantity' => 1, 'notes' => 'Funda negra'],
                ]
            ],
            // Order 39 - Gabriela Castro - Second Order
            [
                'client_index' => 10,
                'order_number' => 'ORD-2026-039',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(25),
                'created_at' => Carbon::now()->subDays(25),
                'estimated_delivery_date' => Carbon::now()->subDays(20),
                'actual_delivery_date' => Carbon::now()->subDays(20),
                'notes' => 'Ropa infantil con manchas, requiere tratamiento especial',
                'items' => [
                    ['subcategory' => 'Ropa Variada', 'quantity' => 8, 'notes' => 'Ropa de niños'],
                ]
            ],
            // Order 40 - Claudia Vargas - Second Order
            [
                'client_index' => 12,
                'order_number' => 'ORD-2026-040',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(23),
                'created_at' => Carbon::now()->subDays(23),
                'estimated_delivery_date' => Carbon::now()->subDays(18),
                'actual_delivery_date' => Carbon::now()->subDays(18),
                'notes' => 'Acolchado con funda floreada, camperas de jean y cuero',
                'items' => [
                    ['subcategory' => 'Una Plaza y Media', 'quantity' => 1, 'notes' => null],
                    ['subcategory' => 'Camperas', 'quantity' => 2, 'notes' => null],
                ]
            ],
            // Order 41 - Daniel Acosta - Second Order
            [
                'client_index' => 13,
                'order_number' => 'ORD-2026-041',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(21),
                'created_at' => Carbon::now()->subDays(21),
                'estimated_delivery_date' => Carbon::now()->subDays(16),
                'actual_delivery_date' => Carbon::now()->subDays(16),
                'notes' => 'Camperas sport térmicas, ropa de gimnasio',
                'items' => [
                    ['subcategory' => 'Ropa Variada', 'quantity' => 10, 'notes' => null],
                ]
            ],
            // Order 42 - Mónica Flores - Second Order
            [
                'client_index' => 14,
                'order_number' => 'ORD-2026-042',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(19),
                'created_at' => Carbon::now()->subDays(19),
                'estimated_delivery_date' => Carbon::now()->subDays(14),
                'actual_delivery_date' => Carbon::now()->subDays(14),
                'notes' => 'Acolchados livianos estampados, fundas con detalles en encaje',
                'items' => [
                    ['subcategory' => 'Dos Plazas', 'quantity' => 1, 'notes' => 'Acolchado blanco'],
                    ['subcategory' => 'Dos Plazas', 'quantity' => 1, 'notes' => 'Funda blanca'],
                ]
            ],
            // Order 43 - Alejandro Ruiz - Second Order
            [
                'client_index' => 15,
                'order_number' => 'ORD-2026-043',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(17),
                'created_at' => Carbon::now()->subDays(17),
                'estimated_delivery_date' => Carbon::now()->subDays(12),
                'actual_delivery_date' => Carbon::now()->subDays(12),
                'notes' => 'Uniformes deportivos, campera de entrenamiento',
                'items' => [
                    ['subcategory' => 'Camperas', 'quantity' => 3, 'notes' => 'Camperas invierno'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 5, 'notes' => null],
                ]
            ],
            // Order 44 - Susana Gutiérrez - Second Order
            [
                'client_index' => 16,
                'order_number' => 'ORD-2026-044',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(15),
                'created_at' => Carbon::now()->subDays(15),
                'estimated_delivery_date' => Carbon::now()->subDays(10),
                'actual_delivery_date' => Carbon::now()->subDays(10),
                'notes' => 'Acolchados infantiles decorativos, ropa escolar',
                'items' => [
                    ['subcategory' => 'Una Plaza', 'quantity' => 2, 'notes' => 'Acolchados niños'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 12, 'notes' => 'Ropa familiar'],
                ]
            ],
            // Order 45 - Pablo Herrera - Second Order
            [
                'client_index' => 17,
                'order_number' => 'ORD-2026-045',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(13),
                'created_at' => Carbon::now()->subDays(13),
                'estimated_delivery_date' => Carbon::now()->subDays(8),
                'actual_delivery_date' => Carbon::now()->subDays(8),
                'notes' => 'Acolchado de lujo, funda con textura de seda',
                'items' => [
                    ['subcategory' => 'Ropa Variada', 'quantity' => 6, 'notes' => null],
                ]
            ],
            // Order 46 - Beatriz Méndez - Second Order
            [
                'client_index' => 18,
                'order_number' => 'ORD-2026-046',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(11),
                'created_at' => Carbon::now()->subDays(11),
                'estimated_delivery_date' => Carbon::now()->subDays(6),
                'actual_delivery_date' => Carbon::now()->subDays(6),
                'notes' => 'Ropa de temporada completa, camperas de diferentes estilos',
                'items' => [
                    ['subcategory' => 'Dos Plazas y Media', 'quantity' => 1, 'notes' => 'Acolchado pluma gris'],
                    ['subcategory' => 'Camperas', 'quantity' => 2, 'notes' => null],
                ]
            ],
            // Order 47 - María González - Third Order
            [
                'client_index' => 0,
                'order_number' => 'ORD-2026-047',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(30),
                'created_at' => Carbon::now()->subDays(30),
                'estimated_delivery_date' => Carbon::now()->subDays(25),
                'actual_delivery_date' => Carbon::now()->subDays(25),
                'notes' => 'Acolchado dos plazas con estampado floral, ropa de uso diario',
                'items' => [
                    ['subcategory' => 'Ropa Variada', 'quantity' => 8, 'notes' => 'Ropa de verano'],
                    ['subcategory' => 'Una Plaza y Media', 'quantity' => 1, 'notes' => null],
                ]
            ],
            // Order 48 - Carlos Rodríguez - Third Order
            [
                'client_index' => 1,
                'order_number' => 'ORD-2026-048',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(28),
                'created_at' => Carbon::now()->subDays(28),
                'estimated_delivery_date' => Carbon::now()->subDays(23),
                'actual_delivery_date' => Carbon::now()->subDays(23),
                'notes' => 'Ropa de vestir formal, camisas blancas para planchar',
                'items' => [
                    ['subcategory' => 'Dos Plazas', 'quantity' => 2, 'notes' => 'Acolchados'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 6, 'notes' => null],
                ]
            ],
            // Order 49 - Ana Martínez - Third Order
            [
                'client_index' => 2,
                'order_number' => 'ORD-2026-049',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(26),
                'created_at' => Carbon::now()->subDays(26),
                'estimated_delivery_date' => Carbon::now()->subDays(21),
                'actual_delivery_date' => Carbon::now()->subDays(21),
                'notes' => 'Acolchado queen size con pluma sintética, ropa casual de familia',
                'items' => [
                    ['subcategory' => 'Camperas', 'quantity' => 1, 'notes' => 'Campera beige'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 4, 'notes' => null],
                ]
            ],
            // Order 50 - Roberto López - Third Order
            [
                'client_index' => 3,
                'order_number' => 'ORD-2026-050',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(24),
                'created_at' => Carbon::now()->subDays(24),
                'estimated_delivery_date' => Carbon::now()->subDays(19),
                'actual_delivery_date' => Carbon::now()->subDays(19),
                'notes' => 'Ropa de trabajo pesada, uniformes con manchas de grasa',
                'items' => [
                    ['subcategory' => 'Una Plaza', 'quantity' => 1, 'notes' => 'Acolchado rosa'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 7, 'notes' => 'Ropa niños'],
                ]
            ],
            // Order 51 - Laura Fernández - Third Order
            [
                'client_index' => 4,
                'order_number' => 'ORD-2026-051',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(22),
                'created_at' => Carbon::now()->subDays(22),
                'estimated_delivery_date' => Carbon::now()->subDays(17),
                'actual_delivery_date' => Carbon::now()->subDays(17),
                'notes' => 'Acolchados infantiles gemelos, fundas con personajes animados',
                'items' => [
                    ['subcategory' => 'Dos Plazas y Media', 'quantity' => 1, 'notes' => 'Acolchado pluma premium'],
                    ['subcategory' => 'Camperas', 'quantity' => 2, 'notes' => null],
                ]
            ],
            // Order 52 - Jorge Sánchez - Third Order
            [
                'client_index' => 5,
                'order_number' => 'ORD-2026-052',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(20),
                'created_at' => Carbon::now()->subDays(20),
                'estimated_delivery_date' => Carbon::now()->subDays(15),
                'actual_delivery_date' => Carbon::now()->subDays(15),
                'notes' => 'Camperas de tela gruesa, ropa de abrigo',
                'items' => [
                    ['subcategory' => 'Ropa Variada', 'quantity' => 13, 'notes' => 'Lavado completo'],
                ]
            ],
            // Order 53 - Patricia Díaz - Third Order
            [
                'client_index' => 6,
                'order_number' => 'ORD-2026-053',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(18),
                'created_at' => Carbon::now()->subDays(18),
                'estimated_delivery_date' => Carbon::now()->subDays(13),
                'actual_delivery_date' => Carbon::now()->subDays(13),
                'notes' => 'Acolchado premium king size, camperas elegantes de cuero',
                'items' => [
                    ['subcategory' => 'Una Plaza y Media', 'quantity' => 2, 'notes' => 'Acolchados'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 5, 'notes' => null],
                ]
            ],
            // Order 54 - Miguel Torres - Third Order
            [
                'client_index' => 7,
                'order_number' => 'ORD-2026-054',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(16),
                'created_at' => Carbon::now()->subDays(16),
                'estimated_delivery_date' => Carbon::now()->subDays(11),
                'actual_delivery_date' => Carbon::now()->subDays(11),
                'notes' => 'Camisas de trabajo blancas y grises, requieren planchado profesional',
                'items' => [
                    ['subcategory' => 'Dos Plazas', 'quantity' => 1, 'notes' => 'Acolchado marrón'],
                    ['subcategory' => 'Dos Plazas', 'quantity' => 1, 'notes' => 'Funda marrón'],
                ]
            ],
            // Order 55 - Silvia Ramírez - Third Order
            [
                'client_index' => 8,
                'order_number' => 'ORD-2026-055',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(14),
                'created_at' => Carbon::now()->subDays(14),
                'estimated_delivery_date' => Carbon::now()->subDays(9),
                'actual_delivery_date' => Carbon::now()->subDays(9),
                'notes' => 'Acolchado doble faz con rayas, funda con cierre',
                'items' => [
                    ['subcategory' => 'Camperas', 'quantity' => 3, 'notes' => 'Camperas familia'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 9, 'notes' => null],
                ]
            ],
            // Order 56 - Fernando Moreno - Third Order
            [
                'client_index' => 9,
                'order_number' => 'ORD-2026-056',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(12),
                'created_at' => Carbon::now()->subDays(12),
                'estimated_delivery_date' => Carbon::now()->subDays(7),
                'actual_delivery_date' => Carbon::now()->subDays(7),
                'notes' => 'Acolchado de pluma grueso para invierno, funda de microfibra',
                'items' => [
                    ['subcategory' => 'Una Plaza', 'quantity' => 1, 'notes' => null],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 5, 'notes' => null],
                ]
            ],
            // Order 57 - Raúl Jiménez - Second Order
            [
                'client_index' => 19,
                'order_number' => 'ORD-2026-057',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(10),
                'created_at' => Carbon::now()->subDays(10),
                'estimated_delivery_date' => Carbon::now()->subDays(5),
                'actual_delivery_date' => Carbon::now()->subDays(5),
                'notes' => 'Acolchado con cuadros escoceses, funda a juego',
                'items' => [
                    ['subcategory' => 'Dos Plazas y Media', 'quantity' => 1, 'notes' => 'Acolchado pluma'],
                    ['subcategory' => 'Camperas', 'quantity' => 2, 'notes' => null],
                ]
            ],
            // Order 58 - Elena Navarro - Second Order
            [
                'client_index' => 20,
                'order_number' => 'ORD-2026-058',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(9),
                'created_at' => Carbon::now()->subDays(9),
                'estimated_delivery_date' => Carbon::now()->subDays(4),
                'actual_delivery_date' => Carbon::now()->subDays(4),
                'notes' => 'Ropa casual variada, jeans y remeras',
                'items' => [
                    ['subcategory' => 'Ropa Variada', 'quantity' => 11, 'notes' => 'Ropa variada'],
                ]
            ],
            // Order 59 - Gustavo Pereyra - Second Order
            [
                'client_index' => 21,
                'order_number' => 'ORD-2026-059',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(8),
                'created_at' => Carbon::now()->subDays(8),
                'estimated_delivery_date' => Carbon::now()->subDays(3),
                'actual_delivery_date' => Carbon::now()->subDays(3),
                'notes' => 'Camperas de nylon, ropa de gimnasio con manchas de sudor',
                'items' => [
                    ['subcategory' => 'Una Plaza y Media', 'quantity' => 1, 'notes' => 'Acolchado'],
                    ['subcategory' => 'Una Plaza y Media', 'quantity' => 1, 'notes' => 'Funda'],
                ]
            ],
            // Order 60 - Mariana Domínguez - Second Order
            [
                'client_index' => 22,
                'order_number' => 'ORD-2026-060',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(7),
                'created_at' => Carbon::now()->subDays(7),
                'estimated_delivery_date' => Carbon::now()->subDays(2),
                'actual_delivery_date' => Carbon::now()->subDays(2),
                'notes' => 'Acolchados infantiles con dibujos de autos, ropa de niños pequeños',
                'items' => [
                    ['subcategory' => 'Dos Plazas', 'quantity' => 1, 'notes' => null],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 6, 'notes' => null],
                ]
            ],
            // Order 61 - Sergio Villalba - Second Order
            [
                'client_index' => 23,
                'order_number' => 'ORD-2026-061',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(6),
                'created_at' => Carbon::now()->subDays(6),
                'estimated_delivery_date' => Carbon::now()->subDays(1),
                'actual_delivery_date' => Carbon::now()->subDays(1),
                'notes' => 'Acolchado grande con manchas de café, necesita limpieza profunda',
                'items' => [
                    ['subcategory' => 'Camperas', 'quantity' => 1, 'notes' => 'Campera negra'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 8, 'notes' => null],
                ]
            ],
            // Order 62 - Cecilia Rojas - Second Order
            [
                'client_index' => 24,
                'order_number' => 'ORD-2026-062',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(5),
                'created_at' => Carbon::now()->subDays(5),
                'estimated_delivery_date' => Carbon::now()->subDays(1),
                'actual_delivery_date' => Carbon::now()->subDays(1),
                'notes' => 'Ropa elegante de fiesta, vestidos delicados',
                'items' => [
                    ['subcategory' => 'Una Plaza', 'quantity' => 2, 'notes' => 'Acolchados infantiles'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 7, 'notes' => 'Ropa niños'],
                ]
            ],
            // Order 63 - Martín Molina - Second Order
            [
                'client_index' => 25,
                'order_number' => 'ORD-2026-063',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(15),
                'created_at' => Carbon::now()->subDays(15),
                'estimated_delivery_date' => Carbon::now()->subDays(10),
                'actual_delivery_date' => Carbon::now()->subDays(10),
                'notes' => 'Acolchado pesado de invierno, ropa de abrigo variada',
                'items' => [
                    ['subcategory' => 'Dos Plazas y Media', 'quantity' => 1, 'notes' => 'Acolchado premium'],
                    ['subcategory' => 'Dos Plazas y Media', 'quantity' => 1, 'notes' => 'Funda'],
                ]
            ],
            // Order 64 - Andrea Silva - Second Order
            [
                'client_index' => 26,
                'order_number' => 'ORD-2026-064',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(13),
                'created_at' => Carbon::now()->subDays(13),
                'estimated_delivery_date' => Carbon::now()->subDays(8),
                'actual_delivery_date' => Carbon::now()->subDays(8),
                'notes' => 'Acolchado liviano de verano, ropa deportiva',
                'items' => [
                    ['subcategory' => 'Ropa Variada', 'quantity' => 15, 'notes' => 'Ropa temporada'],
                ]
            ],
            // Order 65 - Diego Arias - Second Order
            [
                'client_index' => 27,
                'order_number' => 'ORD-2026-065',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(11),
                'created_at' => Carbon::now()->subDays(11),
                'estimated_delivery_date' => Carbon::now()->subDays(6),
                'actual_delivery_date' => Carbon::now()->subDays(6),
                'notes' => 'Camperas de cuero negro, ropa de motociclista',
                'items' => [
                    ['subcategory' => 'Camperas', 'quantity' => 2, 'notes' => null],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 5, 'notes' => null],
                ]
            ],
            // Order 66 - Valeria Cabrera - Second Order
            [
                'client_index' => 28,
                'order_number' => 'ORD-2026-066',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(10),
                'created_at' => Carbon::now()->subDays(10),
                'estimated_delivery_date' => Carbon::now()->subDays(5),
                'actual_delivery_date' => Carbon::now()->subDays(5),
                'notes' => 'Camperas de diferentes miembros de familia, ropa casual completa',
                'items' => [
                    ['subcategory' => 'Dos Plazas', 'quantity' => 1, 'notes' => 'Acolchado'],
                    ['subcategory' => 'Dos Plazas', 'quantity' => 1, 'notes' => 'Funda'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 4, 'notes' => null],
                ]
            ],
            // Order 67 - Gabriela Castro - Third Order
            [
                'client_index' => 10,
                'order_number' => 'ORD-2026-067',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(9),
                'created_at' => Carbon::now()->subDays(9),
                'estimated_delivery_date' => Carbon::now()->subDays(4),
                'actual_delivery_date' => Carbon::now()->subDays(4),
                'notes' => 'Acolchado infantil con hadas, ropa de niña con volados',
                'items' => [
                    ['subcategory' => 'Una Plaza', 'quantity' => 1, 'notes' => null],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 6, 'notes' => null],
                ]
            ],
            // Order 68 - Claudia Vargas - Third Order
            [
                'client_index' => 12,
                'order_number' => 'ORD-2026-068',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(8),
                'created_at' => Carbon::now()->subDays(8),
                'estimated_delivery_date' => Carbon::now()->subDays(3),
                'actual_delivery_date' => Carbon::now()->subDays(3),
                'notes' => 'Acolchado con funda de satén, camperas de jean',
                'items' => [
                    ['subcategory' => 'Camperas', 'quantity' => 3, 'notes' => 'Camperas'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 8, 'notes' => null],
                ]
            ],
            // Order 69 - Daniel Acosta - Third Order
            [
                'client_index' => 13,
                'order_number' => 'ORD-2026-069',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(7),
                'created_at' => Carbon::now()->subDays(7),
                'estimated_delivery_date' => Carbon::now()->subDays(2),
                'actual_delivery_date' => Carbon::now()->subDays(2),
                'notes' => 'Ropa de emergencia, urgente, vestidos para evento',
                'items' => [
                    ['subcategory' => 'Dos Plazas y Media', 'quantity' => 1, 'notes' => 'Acolchado pluma'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 5, 'notes' => null],
                ]
            ],
            // Order 70 - Mónica Flores - Third Order
            [
                'client_index' => 14,
                'order_number' => 'ORD-2026-070',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(6),
                'created_at' => Carbon::now()->subDays(6),
                'estimated_delivery_date' => Carbon::now()->subDays(1),
                'actual_delivery_date' => Carbon::now()->subDays(1),
                'notes' => 'Acolchados térmicos, fundas de colores brillantes',
                'items' => [
                    ['subcategory' => 'Una Plaza y Media', 'quantity' => 1, 'notes' => null],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 10, 'notes' => 'Ropa variada'],
                ]
            ],
            // Order 71 - Alejandro Ruiz - Third Order
            [
                'client_index' => 15,
                'order_number' => 'ORD-2026-071',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(5),
                'created_at' => Carbon::now()->subDays(5),
                'estimated_delivery_date' => Carbon::now()->subDays(1),
                'actual_delivery_date' => Carbon::now()->subDays(1),
                'notes' => 'Ropa deportiva de alta tecnología, campera running',
                'items' => [
                    ['subcategory' => 'Dos Plazas', 'quantity' => 2, 'notes' => 'Acolchados'],
                ]
            ],
            // Order 72 - Susana Gutiérrez - Third Order
            [
                'client_index' => 16,
                'order_number' => 'ORD-2026-072',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(4),
                'created_at' => Carbon::now()->subDays(4),
                'estimated_delivery_date' => Carbon::now()->subDays(1),
                'actual_delivery_date' => Carbon::now()->subDays(1),
                'notes' => 'Acolchados infantiles premium, ropa escolar uniforme completo',
                'items' => [
                    ['subcategory' => 'Ropa Variada', 'quantity' => 14, 'notes' => 'Lavado completo'],
                    ['subcategory' => 'Camperas', 'quantity' => 2, 'notes' => null],
                ]
            ],
            // Order 73 - Pablo Herrera - Third Order
            [
                'client_index' => 17,
                'order_number' => 'ORD-2026-073',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(3),
                'created_at' => Carbon::now()->subDays(3),
                'estimated_delivery_date' => Carbon::now()->subDays(1),
                'actual_delivery_date' => Carbon::now()->subDays(1),
                'notes' => 'Acolchado de lujo importado, funda de seda italiana',
                'items' => [
                    ['subcategory' => 'Una Plaza', 'quantity' => 1, 'notes' => 'Acolchado infantil'],
                    ['subcategory' => 'Una Plaza', 'quantity' => 1, 'notes' => 'Funda'],
                ]
            ],
            // Order 74 - Beatriz Méndez - Third Order
            [
                'client_index' => 18,
                'order_number' => 'ORD-2026-074',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(2),
                'created_at' => Carbon::now()->subDays(2),
                'estimated_delivery_date' => Carbon::now()->subDays(1),
                'actual_delivery_date' => Carbon::now()->subDays(1),
                'notes' => 'Ropa completa de familia, camperas de temporada mixtas',
                'items' => [
                    ['subcategory' => 'Dos Plazas y Media', 'quantity' => 1, 'notes' => 'Acolchado premium pluma'],
                    ['subcategory' => 'Dos Plazas y Media', 'quantity' => 1, 'notes' => 'Funda premium'],
                ]
            ],
            // Order 75 - María González - Fourth Order
            [
                'client_index' => 0,
                'order_number' => 'ORD-2026-075',
                'status' => 'delivered',
                'reception_date' => Carbon::now()->subDays(15),
                'created_at' => Carbon::now()->subDays(15),
                'estimated_delivery_date' => Carbon::now()->subDays(10),
                'actual_delivery_date' => Carbon::now()->subDays(10),
                'notes' => 'Acolchado queen con bordados finos, ropa delicada',
                'items' => [
                    ['subcategory' => 'Camperas', 'quantity' => 2, 'notes' => null],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 9, 'notes' => null],
                ]
            ],
            // Order 76 - Carlos Rodríguez - Fourth Order - In Progress
            [
                'client_index' => 1,
                'order_number' => 'ORD-2026-076',
                'status' => 'in_progress',
                'reception_date' => Carbon::now()->subDays(5),
                'created_at' => Carbon::now()->subDays(5),
                'estimated_delivery_date' => Carbon::now(),
                'actual_delivery_date' => null,
                'notes' => 'Acolchado king size con pluma premium, requiere cuidado especial',
                'items' => [
                    ['subcategory' => 'Una Plaza y Media', 'quantity' => 1, 'notes' => null],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 7, 'notes' => null],
                ]
            ],
            // Order 77 - Ana Martínez - Fourth Order - In Progress
            [
                'client_index' => 2,
                'order_number' => 'ORD-2026-077',
                'status' => 'in_progress',
                'reception_date' => Carbon::now()->subDays(4),
                'created_at' => Carbon::now()->subDays(4),
                'estimated_delivery_date' => Carbon::now()->addDays(1),
                'actual_delivery_date' => null,
                'notes' => 'Ropa fina de seda y encaje, tratamiento delicado',
                'items' => [
                    ['subcategory' => 'Dos Plazas', 'quantity' => 1, 'notes' => 'Acolchado'],
                    ['subcategory' => 'Camperas', 'quantity' => 1, 'notes' => null],
                ]
            ],
            // Order 78 - Roberto López - Fourth Order - In Progress
            [
                'client_index' => 3,
                'order_number' => 'ORD-2026-078',
                'status' => 'in_progress',
                'reception_date' => Carbon::now()->subDays(3),
                'created_at' => Carbon::now()->subDays(3),
                'estimated_delivery_date' => Carbon::now()->addDays(2),
                'actual_delivery_date' => null,
                'notes' => 'Ropa de trabajo con manchas de aceite, lavado industrial',
                'items' => [
                    ['subcategory' => 'Ropa Variada', 'quantity' => 18, 'notes' => 'Ropa completa familia'],
                ]
            ],
            // Order 79 - Laura Fernández - Fourth Order - In Progress
            [
                'client_index' => 4,
                'order_number' => 'ORD-2026-079',
                'status' => 'in_progress',
                'reception_date' => Carbon::now()->subDays(4),
                'created_at' => Carbon::now()->subDays(4),
                'estimated_delivery_date' => Carbon::now()->addDays(1),
                'actual_delivery_date' => null,
                'notes' => 'Camperas infantiles multicolores, acolchado juvenil',
                'items' => [
                    ['subcategory' => 'Una Plaza', 'quantity' => 2, 'notes' => 'Acolchados niños'],
                    ['subcategory' => 'Una Plaza', 'quantity' => 2, 'notes' => 'Fundas niños'],
                ]
            ],
            // Order 80 - Jorge Sánchez - Fourth Order - In Progress
            [
                'client_index' => 5,
                'order_number' => 'ORD-2026-080',
                'status' => 'in_progress',
                'reception_date' => Carbon::now()->subDays(3),
                'created_at' => Carbon::now()->subDays(3),
                'estimated_delivery_date' => Carbon::now()->addDays(2),
                'actual_delivery_date' => null,
                'notes' => 'Ropa con olor a humedad, necesita secado prolongado',
                'items' => [
                    ['subcategory' => 'Dos Plazas y Media', 'quantity' => 1, 'notes' => 'Acolchado pluma blanco'],
                    ['subcategory' => 'Camperas', 'quantity' => 1, 'notes' => null],
                ]
            ],
            // Order 81 - Patricia Díaz - Fourth Order - In Progress
            [
                'client_index' => 6,
                'order_number' => 'ORD-2026-081',
                'status' => 'in_progress',
                'reception_date' => Carbon::now()->subDays(2),
                'created_at' => Carbon::now()->subDays(2),
                'estimated_delivery_date' => Carbon::now()->addDays(3),
                'actual_delivery_date' => null,
                'notes' => 'Camperas de marca, tratamiento premium',
                'items' => [
                    ['subcategory' => 'Ropa Variada', 'quantity' => 11, 'notes' => null],
                ]
            ],
            // Order 82 - Miguel Torres - Fourth Order - In Progress
            [
                'client_index' => 7,
                'order_number' => 'ORD-2026-082',
                'status' => 'in_progress',
                'reception_date' => Carbon::now()->subDays(5),
                'created_at' => Carbon::now()->subDays(5),
                'estimated_delivery_date' => Carbon::now(),
                'actual_delivery_date' => null,
                'notes' => 'Camisas blancas empresariales, planchado profesional requerido',
                'items' => [
                    ['subcategory' => 'Una Plaza y Media', 'quantity' => 1, 'notes' => null],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 8, 'notes' => 'Ropa trabajo'],
                ]
            ],
            // Order 83 - Silvia Ramírez - Fourth Order - In Progress
            [
                'client_index' => 8,
                'order_number' => 'ORD-2026-083',
                'status' => 'in_progress',
                'reception_date' => Carbon::now()->subDays(4),
                'created_at' => Carbon::now()->subDays(4),
                'estimated_delivery_date' => Carbon::now()->addDays(1),
                'actual_delivery_date' => null,
                'notes' => 'Acolchado con manchas de vino, necesita prelavado',
                'items' => [
                    ['subcategory' => 'Dos Plazas', 'quantity' => 1, 'notes' => null],
                    ['subcategory' => 'Camperas', 'quantity' => 2, 'notes' => null],
                ]
            ],
            // Order 84 - Fernando Moreno - Fourth Order - In Progress
            [
                'client_index' => 9,
                'order_number' => 'ORD-2026-084',
                'status' => 'in_progress',
                'reception_date' => Carbon::now()->subDays(3),
                'created_at' => Carbon::now()->subDays(3),
                'estimated_delivery_date' => Carbon::now()->addDays(2),
                'actual_delivery_date' => null,
                'notes' => 'Ropa de cama completa, sábanas y fundas coordinadas',
                'items' => [
                    ['subcategory' => 'Ropa Variada', 'quantity' => 13, 'notes' => 'Ropa variada'],
                ]
            ],
            // Order 85 - Gabriela Castro - Fourth Order - In Progress
            [
                'client_index' => 10,
                'order_number' => 'ORD-2026-085',
                'status' => 'in_progress',
                'reception_date' => Carbon::now()->subDays(2),
                'created_at' => Carbon::now()->subDays(2),
                'estimated_delivery_date' => Carbon::now()->addDays(3),
                'actual_delivery_date' => null,
                'notes' => 'Ropa infantil delicada, acolchado con pelúche',
                'items' => [
                    ['subcategory' => 'Dos Plazas y Media', 'quantity' => 1, 'notes' => 'Acolchado pluma negro'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 6, 'notes' => null],
                ]
            ],
            // Order 86 - Claudia Vargas - Fourth Order - Pending
            [
                'client_index' => 12,
                'order_number' => 'ORD-2026-086',
                'status' => 'pending',
                'reception_date' => Carbon::now(),
                'created_at' => Carbon::now(),
                'estimated_delivery_date' => Carbon::now()->addDays(5),
                'actual_delivery_date' => null,
                'notes' => 'Acolchado extra grande con estampado de flores, camperas de temporada',
                'items' => [
                    ['subcategory' => 'Una Plaza', 'quantity' => 1, 'notes' => null],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 5, 'notes' => null],
                ]
            ],
            // Order 87 - Daniel Acosta - Fourth Order - Pending
            [
                'client_index' => 13,
                'order_number' => 'ORD-2026-087',
                'status' => 'pending',
                'reception_date' => Carbon::now()->subDays(1),
                'created_at' => Carbon::now()->subDays(1),
                'estimated_delivery_date' => Carbon::now()->addDays(4),
                'actual_delivery_date' => null,
                'notes' => 'Camperas de trail running, ropa técnica deportiva',
                'items' => [
                    ['subcategory' => 'Camperas', 'quantity' => 2, 'notes' => null],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 9, 'notes' => null],
                ]
            ],
            // Order 88 - Mónica Flores - Fourth Order - Pending
            [
                'client_index' => 14,
                'order_number' => 'ORD-2026-088',
                'status' => 'pending',
                'reception_date' => Carbon::now(),
                'created_at' => Carbon::now(),
                'estimated_delivery_date' => Carbon::now()->addDays(5),
                'actual_delivery_date' => null,
                'notes' => 'Acolchados decorativos con volados, fundas de encaje',
                'items' => [
                    ['subcategory' => 'Dos Plazas', 'quantity' => 1, 'notes' => 'Acolchado celeste'],
                    ['subcategory' => 'Dos Plazas', 'quantity' => 1, 'notes' => 'Funda celeste'],
                ]
            ],
            // Order 89 - Alejandro Ruiz - Fourth Order - Pending
            [
                'client_index' => 15,
                'order_number' => 'ORD-2026-089',
                'status' => 'pending',
                'reception_date' => Carbon::now()->subDays(1),
                'created_at' => Carbon::now()->subDays(1),
                'estimated_delivery_date' => Carbon::now()->addDays(4),
                'actual_delivery_date' => null,
                'notes' => 'Ropa de gimnasio con sudor, campera deportiva de marca',
                'items' => [
                    ['subcategory' => 'Ropa Variada', 'quantity' => 16, 'notes' => 'Lavado completo'],
                ]
            ],
            // Order 90 - Susana Gutiérrez - Fourth Order - Pending
            [
                'client_index' => 16,
                'order_number' => 'ORD-2026-090',
                'status' => 'pending',
                'reception_date' => Carbon::now(),
                'created_at' => Carbon::now(),
                'estimated_delivery_date' => Carbon::now()->addDays(5),
                'actual_delivery_date' => null,
                'notes' => 'Acolchados y fundas infantiles con personajes, ropa escolar',
                'items' => [
                    ['subcategory' => 'Una Plaza', 'quantity' => 2, 'notes' => 'Acolchados'],
                    ['subcategory' => 'Camperas', 'quantity' => 1, 'notes' => null],
                ]
            ],
            // Order 91 - Pablo Herrera - Fourth Order - Pending
            [
                'client_index' => 17,
                'order_number' => 'ORD-2026-091',
                'status' => 'pending',
                'reception_date' => Carbon::now()->subDays(1),
                'created_at' => Carbon::now()->subDays(1),
                'estimated_delivery_date' => Carbon::now()->addDays(4),
                'actual_delivery_date' => null,
                'notes' => 'Acolchado de lujo king size, funda de seda con bordados',
                'items' => [
                    ['subcategory' => 'Dos Plazas y Media', 'quantity' => 1, 'notes' => 'Acolchado pluma gris'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 4, 'notes' => null],
                ]
            ],
            // Order 92 - Beatriz Méndez - Fourth Order - Pending
            [
                'client_index' => 18,
                'order_number' => 'ORD-2026-092',
                'status' => 'pending',
                'reception_date' => Carbon::now(),
                'created_at' => Carbon::now(),
                'estimated_delivery_date' => Carbon::now()->addDays(5),
                'actual_delivery_date' => null,
                'notes' => 'Ropa de toda la familia, camperas variadas de diferentes talles',
                'items' => [
                    ['subcategory' => 'Una Plaza y Media', 'quantity' => 2, 'notes' => null],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 7, 'notes' => null],
                ]
            ],
            // Order 93 - Raúl Jiménez - Third Order - Pending
            [
                'client_index' => 19,
                'order_number' => 'ORD-2026-093',
                'status' => 'pending',
                'reception_date' => Carbon::now()->subDays(1),
                'created_at' => Carbon::now()->subDays(1),
                'estimated_delivery_date' => Carbon::now()->addDays(4),
                'actual_delivery_date' => null,
                'notes' => 'Acolchado pesado con manchas, necesita lavado intensivo',
                'items' => [
                    ['subcategory' => 'Camperas', 'quantity' => 3, 'notes' => 'Camperas'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 10, 'notes' => null],
                ]
            ],
            // Order 94 - Elena Navarro - Third Order - Pending
            [
                'client_index' => 20,
                'order_number' => 'ORD-2026-094',
                'status' => 'pending',
                'reception_date' => Carbon::now(),
                'created_at' => Carbon::now(),
                'estimated_delivery_date' => Carbon::now()->addDays(5),
                'actual_delivery_date' => null,
                'notes' => 'Ropa variada de verano, camisolas y vestidos livianos',
                'items' => [
                    ['subcategory' => 'Dos Plazas', 'quantity' => 1, 'notes' => null],
                    ['subcategory' => 'Dos Plazas', 'quantity' => 1, 'notes' => 'Funda'],
                ]
            ],
            // Order 95 - Gustavo Pereyra - Third Order - Pending
            [
                'client_index' => 21,
                'order_number' => 'ORD-2026-095',
                'status' => 'pending',
                'reception_date' => Carbon::now()->subDays(1),
                'created_at' => Carbon::now()->subDays(1),
                'estimated_delivery_date' => Carbon::now()->addDays(4),
                'actual_delivery_date' => null,
                'notes' => 'Ropa de trabajo con manchas de pintura, campera de taller',
                'items' => [
                    ['subcategory' => 'Ropa Variada', 'quantity' => 12, 'notes' => 'Ropa variada'],
                ]
            ],
            // Order 96 - Mariana Domínguez - Third Order - Pending
            [
                'client_index' => 22,
                'order_number' => 'ORD-2026-096',
                'status' => 'pending',
                'reception_date' => Carbon::now(),
                'created_at' => Carbon::now(),
                'estimated_delivery_date' => Carbon::now()->addDays(5),
                'actual_delivery_date' => null,
                'notes' => 'Acolchados gemelos, fundas coordinadas, ropa infantil variada',
                'items' => [
                    ['subcategory' => 'Una Plaza', 'quantity' => 1, 'notes' => 'Acolchado rosa'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 8, 'notes' => 'Ropa niños'],
                ]
            ],
            // Order 97 - Sergio Villalba - Third Order - Pending
            [
                'client_index' => 23,
                'order_number' => 'ORD-2026-097',
                'status' => 'pending',
                'reception_date' => Carbon::now()->subDays(1),
                'created_at' => Carbon::now()->subDays(1),
                'estimated_delivery_date' => Carbon::now()->addDays(4),
                'actual_delivery_date' => null,
                'notes' => 'Acolchado grueso de invierno, camperas de pluma',
                'items' => [
                    ['subcategory' => 'Dos Plazas y Media', 'quantity' => 1, 'notes' => 'Acolchado pluma'],
                    ['subcategory' => 'Camperas', 'quantity' => 2, 'notes' => null],
                ]
            ],
            // Order 98 - Cecilia Rojas - Third Order - Pending
            [
                'client_index' => 24,
                'order_number' => 'ORD-2026-098',
                'status' => 'pending',
                'reception_date' => Carbon::now(),
                'created_at' => Carbon::now(),
                'estimated_delivery_date' => Carbon::now()->addDays(5),
                'actual_delivery_date' => null,
                'notes' => 'Ropa de fiesta delicada, vestidos de gala y accesorios',
                'items' => [
                    ['subcategory' => 'Una Plaza y Media', 'quantity' => 1, 'notes' => null],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 6, 'notes' => null],
                ]
            ],
            // Order 99 - Martín Molina - Third Order - Pending
            [
                'client_index' => 25,
                'order_number' => 'ORD-2026-099',
                'status' => 'pending',
                'reception_date' => Carbon::now()->subDays(1),
                'created_at' => Carbon::now()->subDays(1),
                'estimated_delivery_date' => Carbon::now()->addDays(4),
                'actual_delivery_date' => null,
                'notes' => 'Acolchado queen, ropa de uso diario completa',
                'items' => [
                    ['subcategory' => 'Dos Plazas', 'quantity' => 2, 'notes' => 'Acolchados'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 15, 'notes' => 'Lavado completo'],
                ]
            ],
            // Order 100 - Andrea Silva - Third Order - Pending
            [
                'client_index' => 26,
                'order_number' => 'ORD-2026-100',
                'status' => 'pending',
                'reception_date' => Carbon::now(),
                'created_at' => Carbon::now(),
                'estimated_delivery_date' => Carbon::now()->addDays(5),
                'actual_delivery_date' => null,
                'notes' => 'Ropa de bebé, prendas delicadas, acolchado cuna',
                'items' => [
                    ['subcategory' => 'Camperas', 'quantity' => 1, 'notes' => 'Campera'],
                    ['subcategory' => 'Ropa Variada', 'quantity' => 9, 'notes' => null],
                ]
            ],
        ];

        foreach ($orders as $orderData) {
            $items = $orderData['items'];
            unset($orderData['items']);
            
            // Get the actual client ID from the index
            $clientIndex = $orderData['client_index'];
            unset($orderData['client_index']);
            $clientId = $getClientId($clientIndex);
            
            if (!$clientId) {
                continue; // Skip if client doesn't exist
            }
            
            // Add random payment type if not specified
            if (!isset($orderData['payment_type'])) {
                $orderData['payment_type'] = $faker->randomElement(['cash', 'transfer']);
            }
            
            // Create the order without total first
            $order = Order::create(array_merge($orderData, ['client_id' => $clientId, 'total' => 0]));
            
            // Create order items
            foreach ($items as $itemData) {
                $subcategory = $getSubcategory($itemData['subcategory']);
                
                if ($subcategory) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'subcategory_id' => $subcategory->id,
                        'quantity' => $itemData['quantity'],
                        'unit_price' => $subcategory->price,
                        'subtotal' => $itemData['quantity'] * $subcategory->price,
                        'notes' => $itemData['notes'] ?? null,
                    ]);
                }
            }
            
            // Update order total
            $order->total = $order->calculateTotal();
            $order->save();
        }
    }
}
