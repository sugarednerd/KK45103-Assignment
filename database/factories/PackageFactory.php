<?php

namespace Database\Factories;

use App\Models\Package;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;

class PackageFactory extends Factory
{
    protected $model = Package::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 100, 1000),
            'start_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'end_date' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
            'location' => $this->faker->city,
            'cover_image' => $this->faker->imageUrl(),
            'featured' => $this->faker->boolean,
            'user_id' => User::factory(),
        ];
    }
}
