<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BuildTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_create_a_build()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('builds.store'), [
            'name' => 'My Awesome Build',
            'description' => 'This is a build of an awesome house.',
            'coordinates' => '100, 200, 300',
            'items' => [
                ['identifier' => 'minecraft:stone', 'amount' => 64],
                ['identifier' => 'minecraft:wood', 'amount' => 32],
            ],
        ]);

        $response->assertRedirect(route('builds.index'));
        $this->assertDatabaseHas('builds', [
            'name' => 'My Awesome Build',
        ]);
        $this->assertDatabaseHas('build_items', [
            'identifier' => 'minecraft:stone',
            'amount' => 64,
        ]);
    }
}
