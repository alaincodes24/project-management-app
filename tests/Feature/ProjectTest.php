<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        return $user;
    }

    /** @test */
    public function user_can_create_project()
    {
        $this->authenticate();

        $response = $this->postJson('/api/projects', [
            'name' => 'New Project',
            'description' => 'Project description',
        ]);

        $response->assertCreated()
            ->assertJson([
                'status' => 'success',
                'message' => 'Project created successfully',
            ]);

        $this->assertDatabaseHas('projects', ['name' => 'New Project']);
    }

    /** @test */
    public function user_can_get_own_projects()
    {
        $user = $this->authenticate();
        Project::factory()->count(2)->for($user)->create();

        $response = $this->getJson('/api/projects');

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
            ])
            ->assertJsonCount(2, 'projects');
    }

    /** @test */
    public function user_can_update_own_project()
    {
        $user = $this->authenticate();
        $project = Project::factory()->for($user)->create();

        $response = $this->patchJson("/api/projects/{$project->id}", [
            'name' => 'Updated Title',
            'description' => $project->description,
        ]);

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
                'message' => 'Project updated',
            ]);

        $this->assertDatabaseHas('projects', ['id' => $project->id, 'name' => 'Updated Title']);
    }

    /** @test */
    public function user_can_delete_own_project()
    {
        $user = $this->authenticate();
        $project = Project::factory()->for($user)->create();

        $response = $this->deleteJson("/api/projects/{$project->id}");

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
                'message' => 'Project deleted',
            ]);

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }
}
