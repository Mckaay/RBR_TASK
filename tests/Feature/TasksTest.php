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


    public function test_authenticated_user_can_see_edit_form(): void
    {
        $user = User::firstWhere('email', 'test@example.com');
        $this->actingAs($user);

        $task = Task::where('user_id', $user->id)->first();

        $response = $this->get(route('task.edit', $task->id));

        $response->assertStatus(200);
        $response->assertViewIs('task.edit');
        $response->assertViewHas('task', $task);
    }

    public function test_user_cannot_edit_other_users_task(): void
    {
        $firstUser = User::firstWhere('email', 'test@example.com');
        $secondUser = User::firstWhere('email', 'test@test.com');

        $this->actingAs($firstUser);
        $firstUserTask = Task::where('user_id', $firstUser->id)->first();

        $this->actingAs($secondUser);
        $response = $this->get(route('task.edit', $firstUserTask->id));

        $response->assertStatus(404);
    }

    public function test_authenticated_user_can_update_task(): void
    {
        $user = User::firstWhere('email', 'test@example.com');
        $this->actingAs($user);

        $task = Task::where('user_id', $user->id)->first();

        $updatedData = [
            'user_id' => $user->id,
            'name' => 'Updated Task Name',
            'description' => 'This is an updated description',
            'status' => Status::IN_PROGRESS->value,
            'priority' => Priority::HIGH->value,
            'due_date' => now()->addDays(14)->format('Y-m-d'),
        ];

        $response = $this->patch(route('task.update', $task->id), $updatedData);

        $response->assertRedirect(route('task.index'));
        $response->assertSessionHas('success', 'Task Updated successfully!');

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'name' => 'Updated Task Name',
            'description' => 'This is an updated description',
            'status' => Status::IN_PROGRESS->value,
            'priority' => Priority::HIGH->value,
        ]);
    }

    public function test_task_update_fails_with_invalid_data(): void
    {
        $user = User::firstWhere('email', 'test@example.com');
        $this->actingAs($user);

        $task = Task::where('user_id', $user->id)->first();

        $invalidData = [
            'user_id' => $user->id,
            'name' => '',
            'description' => 'Description is valid',
            'status' => Status::DONE->value,
            'priority' => Priority::LOW->value,
            'due_date' => now()->format('Y-m-d'),
        ];

        $response = $this->patch(route('task.update', $task->id), $invalidData);

        $response->assertSessionHasErrors(['name']);
        $response->assertRedirect();
    }

    public function test_user_cannot_update_other_users_task(): void
    {
        $firstUser = User::firstWhere('email', 'test@example.com');
        $secondUser = User::firstWhere('email', 'test@test.com');

        $this->actingAs($firstUser);
        $firstUserTask = Task::where('user_id', $firstUser->id)->first();

        $this->actingAs($secondUser);

        $updatedData = [
            'user_id' => $secondUser->id,
            'name' => 'Trying to update someone else\'s task',
            'description' => 'This should not work',
            'status' => Status::DONE->value,
            'priority' => Priority::LOW->value,
            'due_date' => now()->format('Y-m-d'),
        ];

        $response = $this->patch(route('task.update', $firstUserTask->id), $updatedData);

        $response->assertStatus(404);

        $this->assertDatabaseMissing('tasks', [
            'id' => $firstUserTask->id,
            'name' => 'Trying to update someone else\'s task',
        ]);
    }

    public function test_authenticated_user_can_delete_task(): void
    {
        $user = User::firstWhere('email', 'test@example.com');
        $this->actingAs($user);

        $task = Task::where('user_id', $user->id)->first();
        $taskId = $task->id;

        $response = $this->delete(route('task.delete', $task->id));

        $response->assertRedirect(route('task.index'));
        $response->assertSessionHas('success', 'Task deleted successfully!');

        $this->assertDatabaseMissing('tasks', [
            'id' => $taskId,
        ]);
    }

    public function test_user_cannot_delete_other_users_task(): void
    {
        $firstUser = User::firstWhere('email', 'test@example.com');
        $secondUser = User::firstWhere('email', 'test@test.com');

        $this->actingAs($firstUser);
        $firstUserTask = Task::where('user_id', $firstUser->id)->first();
        $taskId = $firstUserTask->id;

        $this->actingAs($secondUser);

        $response = $this->delete(route('task.delete', $firstUserTask->id));

        $response->assertStatus(404);

        $this->assertDatabaseHas('tasks', [
            'id' => $taskId,
        ]);
    }

    public function test_status_filtering(): void
    {
        $user = User::firstWhere('email', 'test@example.com');
        $this->actingAs($user);

        $tasks = Task::query()->where('status', Status::IN_PROGRESS->value)->get();

        $response = $this->get(route('task.index', ['status' => Status::IN_PROGRESS->value]));

        $response->assertStatus(200);
        $response->assertViewIs('task.index');
        $response->assertViewHas('tasks', $tasks);
    }

    public function test_priority_filtering(): void
    {
        $user = User::firstWhere('email', 'test@example.com');
        $this->actingAs($user);

        $tasks = Task::query()->where('priority', Priority::MEDIUM->value)->get();

        $response = $this->get(route('task.index', ['priority' => Priority::MEDIUM->value]));

        $response->assertStatus(200);
        $response->assertViewIs('task.index');
        $response->assertViewHas('tasks', $tasks);
    }
}
