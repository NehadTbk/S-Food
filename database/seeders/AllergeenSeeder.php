<?php

namespace Database\Seeders;

use App\Models\Allergeen;
use Illuminate\Database\Seeder;

class AllergeenSeeder extends Seeder
{
    public function run(): void
    {
        $allergeens = [
            'Gluten',
            'Lactose',
            'Noten',
            'Ei',
            'Soja',
            'Vis',
            'Schaaldieren',
            'Selderij',
            'Mosterd',
            'Sesam',
        ];

        foreach ($allergeens as $name) {
            Allergeen::create(['name' => $name]);
        }
    }
}
