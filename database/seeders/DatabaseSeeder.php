<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $image_dir = 'public/images/companies';

        if (Storage::exists($image_dir)) {
            Storage::deleteDirectory($image_dir);
        }

        Storage::makeDirectory($image_dir);

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CompanySeeder::class,
            PositionSeeder::class,
            PlaceSeeder::class,
            ScheduleSeeder::class,
            SkillSeeder::class,
            AttendanceSeeder::class,
            OfferSeeder::class,
        ]);
    }
}
