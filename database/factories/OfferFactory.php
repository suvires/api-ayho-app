<?php

namespace Database\Factories;

use App\Models\Offer;
use App\Models\Company;
use App\Models\Skill;
use App\Models\Position;
use App\Models\Place;
use App\Models\Schedule;
use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->jobTitle,
            'description' => $this->faker->paragraph,
            'company_id' => Company::inRandomOrder()->first()->id,
            'salary' => $this->faker->numberBetween(12000, 100000),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Offer $offer) {
            $skills = Skill::inRandomOrder()->take(rand(3, 7))->pluck('id');
            $positions = Position::inRandomOrder()->take(rand(1, 2))->pluck('id');
            $places = Place::inRandomOrder()->take(rand(1, 2))->pluck('id');
            $schedules = Schedule::inRandomOrder()->take(rand(1, 2))->pluck('id');
            $attendances = Attendance::inRandomOrder()->take(rand(1, 2))->pluck('id');

            $offer->skills()->sync($skills);
            $offer->positions()->sync($positions);
            $offer->places()->sync($places);
            $offer->schedules()->sync($schedules);
            $offer->attendances()->sync($attendances);
        });
    }
}