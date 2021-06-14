<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @return void
     */
    public function test_token_generation(): void
    {
        User::factory()->create([
            'email'    => $email = $this->faker->safeEmail(),
            'password' => bcrypt($password = Str::random())
        ]);

        $this->postJson(route('api.login'), [
            'email'    => $email,
            'password' => $password
        ])->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type'
            ]);
    }
}
