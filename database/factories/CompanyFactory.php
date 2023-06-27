<?php

namespace Database\Factories;

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

        $path = $this->faker->image(storage_path('app/public/images/companies'), 400, 300, null, false, true, $name);             
        $image_url = URL::to('/') . Storage::url('images/companies/'.$path);  

        return [
            'name' => $name,
            'description' => $this->faker->paragraph(),
            'image_url' => $image_url,
            'image_width' => 400,
            'image_height' => 300,
        ];
    }
}
