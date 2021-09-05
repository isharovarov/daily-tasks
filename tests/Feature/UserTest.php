<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private $testUserEmail    = 'test@test.com';
    private $testUserPassword = 'password123';


    /**
     * @return void
     */
    public function test_user_can_register()
    {
        $response = $this->json('POST', '/api/register', [
            'email'                 => $this->testUserEmail,
            'password'              => $this->testUserPassword,
            'password_confirmation' => $this->testUserPassword
        ]);

        $user = User::first();

        $response->assertStatus(201)
            ->assertJsonPath('user.email', 'test@test.com')
            ->assertJsonStructure([
                'user' => [
                    'email',
                    'updated_at',
                    'created_at',
                    'id'
                ],
                'token'
            ]);

        $this->assertEquals($this->testUserEmail, $user->email);
    }

    /**
     * @return void
     */
    public function test_user_can_login()
    {
        $this->createTestUser();

        $response = $this->json('POST', '/api/login', [
            'email'    => $this->testUserEmail,
            'password' => $this->testUserPassword
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'token'
            ]);
    }

    private function createTestUser(): Model
    {
        return User::factory()->create([
            'email'    => $this->testUserEmail,
            'password' => Hash::make($this->testUserPassword)
        ]);
    }
}
