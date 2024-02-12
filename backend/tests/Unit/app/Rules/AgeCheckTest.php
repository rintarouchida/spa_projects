<?php

namespace Tests\Unit\Rules;

use App\Rules\AgeCheck;
use Carbon\Carbon;
use Tests\TestCase;

class AgeCheckTest extends TestCase
{
    /**
     * passes
     *
     * @test
     * @return void
     */
    public function testPasses()
    {
        $rule = new AgeCheck();
        $birthdate = Carbon::now()->subYears(18)->format('Y-m-d');  // 18歳の生年月日を設定

        $this->assertTrue($rule->passes('birthdate', $birthdate));
    }

    /**
     * passes
     *
     * @test
     * @return void
     */
    public function testPassesIfNotOverEighteen()
    {
        $rule = new AgeCheck();
        $birthdate = Carbon::now()->subYears(17)->format('Y-m-d');  // 17歳の生年月日を設定

        $this->assertFalse($rule->passes('birthdate', $birthdate));
    }
}
