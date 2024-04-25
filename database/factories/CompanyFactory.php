<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;

class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->company();

        $image_dir = 'public/images/companies';

        $path = $this->faker->image(storage_path('app/'.$image_dir), 400, 300, null, false, true, $name);
        $image_url = URL::to('/') . Storage::url('images/companies/'.$path);

        return [
            'name' => $name,
            'description' => $this->faker->paragraph(),
            'image_url' => $image_url,
            'image_width' => 400,
            'image_height' => 300,
            'user_id' => User::inRandomOrder()->first()->id,
        ];
    }
}
