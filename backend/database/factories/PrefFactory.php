<?php

namespace Database\Factories;

use App\Models\Pref;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrefFactory extends Factory
{
    protected $model = Pref::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
        ];
    }
}
