<?php

namespace Database\Seeders;

use App\Models\AboutPage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AboutPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AboutPage::firstOrCreate([], [
            'content' => "S-Food is ontstaan uit een eenvoudige passie: lekker, huisbereid eten delen met buurtgenoten en vrienden. Wat begon als koken voor familie, groeide uit tot een kleine thuiskeuken waar elke dag met liefde verse gerechten worden bereid.\n\nElk gerecht op onze menukaart wordt met verse, lokale ingrediënten gemaakt, precies zoals je het thuis zou klaarmaken. Geen bewerkte producten, geen poespas — gewoon eerlijk, huisgemaakt eten.\n\nBestel je maaltijd online en kies zelf of je ze komt afhalen of laat leveren. We doen ons best om van elke bestelling een klein feestje te maken.",
        ]);
    }
}
