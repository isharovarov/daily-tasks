<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use JWTAuth;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    private $testUserEmail    = 'test@test.com';
    private $testUserPassword = 'password123';


    /**
     * @return void
     */
    public function test_user_can_get_tasks_list()
    {
        $user  = $this->createTestUser();
        $this->fillTasksTable($user->id);
        $token = JWTAuth::fromUser($user);

        $response = $this->json('GET', '/api/tasks?token=' . $token);

        $response->assertStatus(200)
            ->assertJsonPath('0.user_id', $user->id)
            ->assertJsonPath('0.user_id', $user->id)
            ->assertJsonPath('0.title', 'Test task 2')
            ->assertJsonPath('0.body', 'Test task 2 body')
            ->assertJsonPath('0.category', 'Fundamentals')
            ->assertJsonPath('0.solved', 0);
    }

    /**
     * @return void
     */
    public function test_user_can_set_task_as_done()
    {
        $user  = $this->createTestUser();
        $this->fillTasksTable($user->id);
        $token = JWTAuth::fromUser($user);

        $oldTask       = $user->tasks()->first();
        $oldTaskSolved = $oldTask->solved;

        $response = $this->json('POST', '/api/tasks/'. $oldTask->id .'/solved?token=' . $token);

        $updatedTask       = $user->tasks()->first();
        $updatedTaskSolved = $updatedTask->solved;

        $response->assertStatus(200)
            ->assertJsonPath('status', true);

        $this->assertEquals(0, $oldTaskSolved);
        $this->assertEquals(1, $updatedTaskSolved);
    }

    private function createTestUser(): Model
    {
        return User::factory()->create([
            'email'    => $this->testUserEmail,
            'password' => Hash::make($this->testUserPassword)
        ]);
    }

    private function fillTasksTable($testUserId)
    {
        $anotherTestUser = User::factory()->create();

        Task::insert([
            [
                'user_id'   => $anotherTestUser->id,
                'title'     => 'Test task 1',
                'body'      => 'Test task 1 body',
                'category'  => 'Fundamentals',
                'solved'    => 0,
            ],
            [
                'user_id'   => $testUserId,
                'title'     => 'Test task 2',
                'body'      => 'Test task 2 body',
                'category'  => 'Fundamentals',
                'solved'    => 0,
            ],
        ]);
    }
}
