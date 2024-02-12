<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class AgeCheck implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $birthday = Carbon::parse($value);
        $now = Carbon::now();
        $age = $now->diffInYears($birthday);

        return $age >= 18;
    }


    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '18歳未満の方は登録できません。';
    }
}
