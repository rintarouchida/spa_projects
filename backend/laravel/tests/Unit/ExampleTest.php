<?php

namespace Tests\Unit;

use App\Models\Tag;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        Tag::factory()->create();
        Tag::factory()->create();
        Tag::factory()->create();
        $actual = Tag::all()->pluck('id');
        $this->assertCount(3, $actual);
    }
}
