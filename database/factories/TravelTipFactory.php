<?php

namespace Database\Factories;

use App\Models\TravelTip;
use Illuminate\Database\Eloquent\Factories\Factory;

class TravelTipFactory extends Factory
{
    protected $model = TravelTip::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'categories' => $this->faker->word(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}

