<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        return $user;
    }

    /** @test */
    public function user_can_create_task()
    {
        $user = $this->authenticate();
        $project = Project::factory()->for($user)->create();

        $response = $this->postJson('/api/tasks', [
            'title' => 'Test Task',
            'description' => 'Some details',
            'status' => 'pending',
            'priority' => 'high',
            'due_date' => now()->addDays(3)->toDateString(),
            'project_id' => $project->id,
        ]);

        $response->assertCreated()
            ->assertJson([
                'status' => 'success',
                'message' => 'Task created successfully',
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'project_id' => $project->id,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function user_can_get_their_tasks()
    {
        $user = $this->authenticate();
        $project = Project::factory()->for($user)->create();

        // Create tasks explicitly linked to this user and project
        Task::factory()->count(3)->for($user, 'user')->create([
            'project_id' => $project->id,
        ]);

        $response = $this->getJson('/api/tasks');

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
            ])
            ->assertJsonCount(3, 'tasks');
    }

    /** @test */
    public function user_can_update_their_task()
    {
        $user = $this->authenticate();
        $project = Project::factory()->for($user)->create();

        $task = Task::factory()->for($user, 'user')->create([
            'project_id' => $project->id,
        ]);

        $response = $this->patchJson("/api/tasks/{$task->id}", [
            'title' => 'Updated Task Title',
            'description' => $task->description,
            'status' => 'completed',
            'priority' => $task->priority,
            'due_date' => $task->due_date->toDateString(),
            // no user_id here either
        ]);

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
                'message' => 'Task updated',
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task Title',
        ]);
    }

    /** @test */
    public function user_can_delete_their_task()
    {
        $user = $this->authenticate();
        $project = Project::factory()->for($user)->create();

        $task = Task::factory()->for($user, 'user')->create([
            'project_id' => $project->id,
        ]);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
                'message' => 'Task deleted',
            ]);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
