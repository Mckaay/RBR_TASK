<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class TasksTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }


    public function test_if_user_can_see_his_tasks(): void
    {
        $testingUser = User::firstWhere('email', 'test@example.com');
        $this->actingAs($testingUser);
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('tasks.index');
        $response->assertViewHas('tasks', Task::where('user_id', $testingUser->id)->get());
    }

    public function test_if_user_cant_see_other_tasks(): void
    {
        $firstTestingUser = User::firstWhere('email', 'test@example.com');
        $secondTestingUser = User::firstWhere('email', 'test@test.com');


        $this->actingAs($firstTestingUser);
        $firstTestingUserTasks = Task::all();
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('tasks.index');
        $response->assertViewHas('tasks', $firstTestingUserTasks);

        $this->actingAs($secondTestingUser);
        $secondTestingUserTasks = Task::all();
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('tasks.index');
        $response->assertViewHas('tasks', $secondTestingUserTasks);
    }
}
