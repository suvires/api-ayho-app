<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Skill::create(['name' => 'Articulate Storyline']);
        Skill::create(['name' => 'Articulate Rise']);
        Skill::create(['name' => 'Camtasia']);
        Skill::create(['name' => 'eXeLearning']);
        Skill::create(['name' => 'iSpring']);
        Skill::create(['name' => 'Adobe Captivate']);
        Skill::create(['name' => 'Moodle']);
        Skill::create(['name' => 'Blackboard Learn']);
        Skill::create(['name' => 'Audacity']);
        Skill::create(['name' => 'Genially']);
    }
}
