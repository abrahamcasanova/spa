<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{

    use RefreshDatabase;

    protected $adminToken;
    protected $adminUser;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();

        $this->adminUser = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin'
        ]);

        $this->adminToken = $this->getAuthToken('admin@example.com', 'admin123');
    }

    protected function getAuthToken($email, $password)
    {
        $loginResponse = $this->postJson('api/login', [
            'email' => $email,
            'password' => $password
        ]);

        $loginResponse->assertStatus(200);
        return $loginResponse->json('token');
    }


    /**
     * Test a user can be created.
     *
     * @return void
     */
    public function testCreateUser()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'newuser@example.com',
            'password' => 'password',
            'role' => 'user',
            'status' => 'active',
            'password_confirmation' => 'password'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->adminToken,
        ])->postJson('api/users', $userData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'newuser@example.com',
        ]);
    }

    /**
     * Test a user can be updated.
     *
     * @return void
     */

    public function testUpdateUserStatus()
    {
        $user = User::factory()->create([
            'name' => 'Regular User',
            'email' => 'regular@example.com',
            'status' => 'active'
        ]);

        $this->assertEquals('active', $user->status);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->adminToken,
        ])->putJson('api/users/' . $user->id, array_merge($user->toArray(), [
            'status' => 'inactive',
        ]));

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'User updated successfully',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => 'inactive',
            'name' => 'Regular User',
            'email' => 'regular@example.com'
        ]);

        $user->refresh();
        $this->assertEquals('inactive', $user->status);
    }

}

