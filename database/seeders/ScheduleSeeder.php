<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schedule::create(['name' => 'Jornada completa']);
        Schedule::create(['name' => 'Jornada parcial']);
        Schedule::create(['name' => 'Freelance']);
        Schedule::create(['name' => 'Beca']);
    }
}
