<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\Priority;
use App\Enums\Status;
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


    public function test_if_user_can_see_his_tasks_in_listing(): void
    {
        $testingUser = User::firstWhere('email', 'test@example.com');
        $this->actingAs($testingUser);
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('task.index');
        $response->assertViewHas('tasks', Task::where('user_id', $testingUser->id)->get());
    }

    public function test_if_user_cant_see_other_tasks_in_listing(): void
    {
        $firstTestingUser = User::firstWhere('email', 'test@example.com');
        $secondTestingUser = User::firstWhere('email', 'test@test.com');


        $this->actingAs($firstTestingUser);
        $firstTestingUserTasks = Task::all();
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('task.index');
        $response->assertViewHas('tasks', $firstTestingUserTasks);

        $this->actingAs($secondTestingUser);
        $secondTestingUserTasks = Task::all();
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('task.index');
        $response->assertViewHas('tasks', $secondTestingUserTasks);
    }

    public function test_if_user_can_view_his_task(): void
    {
        $firstUser = User::firstWhere('email', 'test@example.com');
        $this->actingAs($firstUser);
        $tasks = Task::all();

        $response = $this->get('/task/' . $tasks->first()->id);

        $response->assertStatus(200);
        $response->assertViewIs('task.show');
        $response->assertViewHas('task', $tasks->first());
    }

    public function test_if_user_cant_view_others_task(): void
    {
        $firstUser = User::firstWhere('email', 'test@example.com');
        $this->actingAs($firstUser);
        $firstUserTasks = Task::all();

        $secondUser = User::firstWhere('email', 'test@test.com');
        $this->actingAs($secondUser);
        $response = $this->get('/task/' . $firstUserTasks->first()->id);
        $response->assertStatus(404);
    }

    public function unauthenticated_user_cant_visit_form_page(): void
    {
        $this->assertGuest();
        $response = $this->get('/task/create');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_create_a_task(): void
    {
        $user = User::firstWhere('email', 'test@example.com');
        $this->actingAs($user);

        $taskData = [
            'user_id' => $user->id,
            'name' => 'Test Task',
            'description' => 'This is a test task description',
            'status' => Status::TO_DO->value,
            'priority' => Priority::MEDIUM->value,
            'due_date' => now()->addDays(7)->format('Y-m-d'),
        ];

        $response = $this->post(route('task.store'), $taskData);

        $response->assertRedirect(route('task.index'));
        $response->assertSessionHas('success', 'Task created successfully!');

        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'name' => 'Test Task',
            'description' => 'This is a test task description',
            'status' => Status::TO_DO->value,
            'priority' => Priority::MEDIUM->value,
        ]);
    }

    public function test_task_creation_fails_with_invalid_data(): void
    {
        $user = User::firstWhere('email', 'test@example.com');
        $this->actingAs($user);

        $invalidTaskData = [
            'user_id' => $user->id,
            'name' => '',
            'description' => 'This is a test task description',
            'status' => '',
            'priority' => '',
            'due_date' => '',
        ];

        $response = $this->post(route('task.store'), $invalidTaskData);

        $response->assertSessionHasErrors(['name', 'status', 'priority', 'due_date']);
        $response->assertRedirect();

        $response->assertSessionHasErrors([
            'name' => 'The task name is required.',
            'status' => 'The task status is required.',
            'priority' => 'The task priority is required.',
            'due_date' => 'The due date is required.',
        ]);
    }

    public function test_task_creation_fails_with_invalid_status_and_priority(): void
    {
        $user = User::firstWhere('email', 'test@example.com');
        $this->actingAs($user);

        $invalidEnumTaskData = [
            'user_id' => $user->id,
            'name' => 'Test Task',
            'description' => 'This is a test task description',
            'status' => 'INVALID_STATUS', // Invalid status value
            'priority' => 'INVALID_PRIORITY', // Invalid priority value
            'due_date' => now()->addDays(7)->format('Y-m-d'),
        ];

        $response = $this->post(route('task.store'), $invalidEnumTaskData);

        $response->assertSessionHasErrors(['status', 'priority']);
        $response->assertRedirect();

        $response->assertSessionHasErrors([
            'status' => 'The selected status is invalid.',
            'priority' => 'The selected priority is invalid.',
        ]);
    }

    public function test_unauthenticated_user_cannot_create_task(): void
    {
        $this->assertGuest();

        $taskData = [
            'user_id' => 1,
            'name' => 'Test Task',
            'description' => 'This is a test task description',
            'status' => Status::TO_DO->value,
            'priority' => Priority::MEDIUM->value,
            'due_date' => now()->addDays(7)->format('Y-m-d'),
        ];

        $response = $this->post(route('task.store'), $taskData);

        $response->assertRedirect('/login');

        $this->assertDatabaseMissing('tasks', [
            'name' => 'Test Task',
        ]);
    }
}
