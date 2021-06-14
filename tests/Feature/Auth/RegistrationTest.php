<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_it_allows_to_create_new_users(): void
    {
        $this->postJson(route('api.register'), [
            'email'                 => $email = $this->faker->safeEmail(),
            'name'                  => $this->faker->name(),
            'password'              => $password = Str::random(10),
            'password_confirmation' => $password
        ])->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type'
            ]);

        $this
            ->assertDatabaseHas('users', ['email' => $email])
            ->assertDatabaseCount('users', 1);
    }

    public function test_it_does_not_allow_duplicate_emails(): void
    {
        $email = 'test_email@mail.com';
        User::factory()->create(['email' => $email]);

        $this->postJson(route('api.register'), [
            'email'                 => $email,
            'name'                  => $this->faker->name(),
            'password'              => $password = Str::random(10),
            'password_confirmation' => $password
        ])->assertJsonFragment([
            'email'   => ['The email has already been taken.'],
            'message' => 'The given data was invalid.'
        ]);

        $this
            ->assertDatabaseHas('users', ['email' => $email])
            ->assertDatabaseCount('users', 1);
    }
}
