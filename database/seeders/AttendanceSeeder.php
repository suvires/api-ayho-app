<?php

namespace Database\Seeders;

use App\Models\Attendance;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Attendance::create(['name' => 'Presencial']);
        Attendance::create(['name' => 'Remoto']);
        Attendance::create(['name' => 'Híbrido']);
    }
}
