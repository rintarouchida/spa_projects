<?php

namespace Database\Factories;

use App\Models\Party;
use App\Models\Pref;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PartyFactory extends Factory
{
    protected $model = Party::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'theme' => $this->faker->name(),
            'place' => $this->faker->name(),
            'due_max' => $this->faker->randomDigit(),
            'due_date' => now(),
            'introduction' => $this->faker->name(),
            'pref_id' => Pref::factory()->create()->id,
            'leader_id' => User::factory()->create()->id,
        ];
    }
}
