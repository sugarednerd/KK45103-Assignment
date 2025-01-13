<?php

namespace Database\Factories;

use App\Models\SupportMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupportMessageFactory extends Factory
{
    protected $model = SupportMessage::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'message' => $this->faker->sentence(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
