<?php

namespace Database\Factories;

use App\Models\MessageGroup;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'message_group_id' => MessageGroup::factory()->create()->id,
            'user_id' => User::factory()->create()->id,
            'content' => Str::random(10),
        ];
    }
}
