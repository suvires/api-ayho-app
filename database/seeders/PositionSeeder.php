<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Position::create(['name' => 'Diseño instruccional']);
        Position::create(['name' => 'Gestión de proyectos']);
        Position::create(['name' => 'Edición de vídeo']);
        Position::create(['name' => 'Diseño gráfico']);
        Position::create(['name' => 'Gestión de formación']);
        Position::create(['name' => 'Gestión de LMS']);
    }
}
