<?php

namespace Database\Seeders;

use App\Models\Place;
use Illuminate\Database\Seeder;

class PlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Place::create(['name' => 'Albacete']);
        Place::create(['name' => 'Alacant']);
        Place::create(['name' => 'Almería']);
        Place::create(['name' => 'Araba']);
        Place::create(['name' => 'Asturias']);
        Place::create(['name' => 'Ávila']);
        Place::create(['name' => 'Badajoz']);
        Place::create(['name' => 'Illes Balears']);
        Place::create(['name' => 'Barcelona']);
        Place::create(['name' => 'Bizkaia']);
        Place::create(['name' => 'Burgos']);
        Place::create(['name' => 'Cáceres']);
        Place::create(['name' => 'Cádiz']);
        Place::create(['name' => 'Cantabria']);
        Place::create(['name' => 'Castelló']);
        Place::create(['name' => 'Ciudad Real']);
        Place::create(['name' => 'Córdoba']);
        Place::create(['name' => 'A Coruña']);
        Place::create(['name' => 'Cuenca']);
        Place::create(['name' => 'Gipuzkoa']);
        Place::create(['name' => 'Girona']);
        Place::create(['name' => 'Granada']);
        Place::create(['name' => 'Guadalajara']);
        Place::create(['name' => 'Huelva']);
        Place::create(['name' => 'Huesca']);
        Place::create(['name' => 'Jaén']);
        Place::create(['name' => 'León']);
        Place::create(['name' => 'Lleida']);
        Place::create(['name' => 'Lugo']);
        Place::create(['name' => 'Madrid']);
        Place::create(['name' => 'Málaga']);
        Place::create(['name' => 'Murcia']);
        Place::create(['name' => 'Navarra']);
        Place::create(['name' => 'Ourense']);
        Place::create(['name' => 'Palencia']);
        Place::create(['name' => 'Las Palmas']);
        Place::create(['name' => 'Pontevedra']);
        Place::create(['name' => 'La Rioja']);
        Place::create(['name' => 'Salamanca']);
        Place::create(['name' => 'Santa Cruz de Tenerife']);
        Place::create(['name' => 'Segovia']);
        Place::create(['name' => 'Sevilla']);
        Place::create(['name' => 'Soria']);
        Place::create(['name' => 'Tarragona']);
        Place::create(['name' => 'Teruel']);
        Place::create(['name' => 'Toledo']);
        Place::create(['name' => 'València']);
        Place::create(['name' => 'Valladolid']);
        Place::create(['name' => 'Zamora']);
        Place::create(['name' => 'Zaragoza']);
        Place::create(['name' => 'Ceuta']);
        Place::create(['name' => 'Melilla']);
        Place::create(['name' => 'Cualquier ciudad']);
    }
}
