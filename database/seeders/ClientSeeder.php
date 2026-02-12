<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            [
                'forename' => 'María',
                'surname' => 'González',
                'phone' => '3874567890',
                'address' => 'Av. Belgrano 1234, Salta Capital',
                'latitude' => -24.788910,
                'longitude' => -65.410720,
                'active' => true
            ],
            [
                'forename' => 'Carlos',
                'surname' => 'Rodríguez',
                'phone' => '3872345678',
                'address' => 'Calle Caseros 567, Salta Capital',
                'latitude' => -24.793456,
                'longitude' => -65.413890,
                'active' => true
            ],
            [
                'forename' => 'Ana',
                'surname' => 'Martínez',
                'phone' => '3878765432',
                'address' => 'Av. Entre Ríos 890, Salta Capital',
                'latitude' => -24.782345,
                'longitude' => -65.419876,
                'active' => true
            ],
            [
                'forename' => 'Roberto',
                'surname' => 'López',
                'phone' => '3873456789',
                'address' => 'Calle Buenos Aires 2345, Salta Capital',
                'latitude' => -24.796543,
                'longitude' => -65.407654,
                'active' => true
            ],
            [
                'forename' => 'Laura',
                'surname' => 'Fernández',
                'phone' => '3875678901',
                'address' => 'Av. San Martín 1567, Salta Capital',
                'latitude' => -24.786789,
                'longitude' => -65.415432,
                'active' => true
            ],
            [
                'forename' => 'Jorge',
                'surname' => 'Sánchez',
                'phone' => '3876789012',
                'address' => 'Calle Urquiza 678, Salta Capital',
                'latitude' => -24.791234,
                'longitude' => -65.412345,
                'active' => true
            ],
            [
                'forename' => 'Patricia',
                'surname' => 'Díaz',
                'phone' => '3877890123',
                'address' => 'Av. Sarmiento 1890, Salta Capital',
                'latitude' => -24.784567,
                'longitude' => -65.408765,
                'active' => true
            ],
            [
                'forename' => 'Miguel',
                'surname' => 'Torres',
                'phone' => '3879012345',
                'address' => 'Calle España 3456, Salta Capital',
                'latitude' => -24.799876,
                'longitude' => -65.416789,
                'active' => true
            ],
            [
                'forename' => 'Silvia',
                'surname' => 'Ramírez',
                'phone' => '3870123456',
                'address' => 'Av. Virrey Toledo 567, Salta Capital',
                'latitude' => -24.787654,
                'longitude' => -65.420123,
                'active' => true
            ],
            [
                'forename' => 'Fernando',
                'surname' => 'Moreno',
                'phone' => '3871234567',
                'address' => 'Calle Alvarado 2345, Salta Capital',
                'latitude' => -24.795432,
                'longitude' => -65.411987,
                'active' => true
            ],
            [
                'forename' => 'Gabriela',
                'surname' => 'Castro',
                'phone' => '3872468135',
                'address' => 'Av. Paraguay 789, Salta Capital',
                'latitude' => -24.789012,
                'longitude' => -65.418654,
                'active' => true
            ],
            [
                'forename' => 'Ricardo',
                'surname' => 'Romero',
                'phone' => '3873579246',
                'address' => 'Calle Mitre 1567, Salta Capital',
                'latitude' => -24.792345,
                'longitude' => -65.414321,
                'active' => false
            ],
            [
                'forename' => 'Claudia',
                'surname' => 'Vargas',
                'phone' => '3874681357',
                'address' => 'Av. Hipólito Yrigoyen 2890, Salta Capital',
                'latitude' => -24.783210,
                'longitude' => -65.406789,
                'active' => true
            ],
            [
                'forename' => 'Daniel',
                'surname' => 'Acosta',
                'phone' => '3875792468',
                'address' => 'Calle Jujuy 456, Salta Capital',
                'latitude' => -24.797890,
                'longitude' => -65.409876,
                'active' => true
            ],
            [
                'forename' => 'Mónica',
                'surname' => 'Flores',
                'phone' => '3876803579',
                'address' => 'Av. Reyes Católicos 1234, Salta Capital',
                'latitude' => -24.781234,
                'longitude' => -65.421345,
                'active' => true
            ],
            [
                'forename' => 'Alejandro',
                'surname' => 'Ruiz',
                'phone' => '3877914680',
                'address' => 'Calle Tucumán 890, Salta Capital',
                'latitude' => -24.794567,
                'longitude' => -65.405678,
                'active' => true
            ],
            [
                'forename' => 'Susana',
                'surname' => 'Gutiérrez',
                'phone' => '3878025791',
                'address' => 'Av. Tavella 2345, Salta Capital',
                'latitude' => -24.790123,
                'longitude' => -65.413456,
                'active' => true
            ],
            [
                'forename' => 'Pablo',
                'surname' => 'Herrera',
                'phone' => '3879136802',
                'address' => 'Calle Zuviría 567, Salta Capital',
                'latitude' => -24.786543,
                'longitude' => -65.417890,
                'active' => true
            ],
            [
                'forename' => 'Beatriz',
                'surname' => 'Méndez',
                'phone' => '3870247913',
                'address' => 'Av. Arenales 1890, Salta Capital',
                'latitude' => -24.798765,
                'longitude' => -65.412109,
                'active' => true
            ],
            [
                'forename' => 'Raúl',
                'surname' => 'Jiménez',
                'phone' => '3871358024',
                'address' => 'Calle Pueyrredón 3456, Salta Capital',
                'latitude' => -24.785678,
                'longitude' => -65.408321,
                'active' => true
            ],
            [
                'forename' => 'Elena',
                'surname' => 'Navarro',
                'phone' => '3872469135',
                'address' => 'Av. Ameghino 678, Salta Capital',
                'latitude' => -24.793210,
                'longitude' => -65.419432,
                'active' => true
            ],
            [
                'forename' => 'Gustavo',
                'surname' => 'Pereyra',
                'phone' => '3873570246',
                'address' => 'Calle Rivadavia 2123, Salta Capital',
                'latitude' => -24.788901,
                'longitude' => -65.415876,
                'active' => true
            ],
            [
                'forename' => 'Mariana',
                'surname' => 'Domínguez',
                'phone' => '3874681246',
                'address' => 'Av. Los Incas 1456, Salta Capital',
                'latitude' => -24.791876,
                'longitude' => -65.410234,
                'active' => true
            ],
            [
                'forename' => 'Sergio',
                'surname' => 'Villalba',
                'phone' => '3875792357',
                'address' => 'Calle San Juan 789, Salta Capital',
                'latitude' => -24.796210,
                'longitude' => -65.406543,
                'active' => true
            ],
            [
                'forename' => 'Cecilia',
                'surname' => 'Rojas',
                'phone' => '3876903468',
                'address' => 'Av. Ejercito del Norte 3210, Salta Capital',
                'latitude' => -24.780987,
                'longitude' => -65.422109,
                'active' => true
            ],
            [
                'forename' => 'Martín',
                'surname' => 'Molina',
                'phone' => '3877014579',
                'address' => 'Calle Santa Fe 1678, Salta Capital',
                'latitude' => -24.789654,
                'longitude' => -65.414567,
                'active' => true
            ],
            [
                'forename' => 'Andrea',
                'surname' => 'Silva',
                'phone' => '3878125680',
                'address' => 'Av. Del Bicentenario 2890, Salta Capital',
                'latitude' => -24.794321,
                'longitude' => -65.407890,
                'active' => true
            ],
            [
                'forename' => 'Diego',
                'surname' => 'Arias',
                'phone' => '3879236791',
                'address' => 'Calle Lerma 456, Salta Capital',
                'latitude' => -24.787123,
                'longitude' => -65.416234,
                'active' => true
            ],
            [
                'forename' => 'Valeria',
                'surname' => 'Cabrera',
                'phone' => '3870347802',
                'address' => 'Av. San Lorenzo 1234, Salta Capital',
                'latitude' => -24.799234,
                'longitude' => -65.411678,
                'active' => true
            ],
            [
                'forename' => 'Javier',
                'surname' => 'Gómez',
                'phone' => '3871458913',
                'address' => 'Calle Córdoba 3567, Salta Capital',
                'latitude' => -24.782109,
                'longitude' => -65.420987,
                'active' => false
            ]
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }
    }
}
