<?php

// write test for ProjectController@index
use App\Models\Project;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('can retrieve projects with filters', function () {
    Project::factory()->count(3)->create();

    $response = $this->actingAs($this->user)->getJson('/api/projects');

    $response->assertStatus(200)
        ->assertJsonCount(3, 'data');
});

it('can retrieve projects with search', function () {
    Project::factory()->count(3)->create();

    $response = $this->actingAs($this->user)->getJson('/api/projects?search=project');

    $response->assertStatus(200)
        ->assertJsonCount(3, 'data');
});

//it('can retrieve projects with pagination', function () {
//    Project::factory()->count(3)->create();
//
//    $response = $this->actingAs($this->user)->getJson('/api/projects?per_page=1');
//
//    $response->assertStatus(200)
//        ->assertJsonCount(1, 'data');
//});

it('can retrieve projects with sorting', function () {
    Project::factory()->create(['name' => 'Project 1']);
    Project::factory()->create(['name' => 'Project 2']);
    Project::factory()->create(['name' => 'Project 3']);

    $response = $this->actingAs($this->user)->getJson('/api/projects?sort=name');

    $response->assertStatus(200)
        ->assertJsonPath('data.0.name', 'Project 1')
        ->assertJsonPath('data.1.name', 'Project 2')
        ->assertJsonPath('data.2.name', 'Project 3');
});

it('can retrieve a project', function () {
    $project = Project::factory()->create();

    $response = $this->actingAs($this->user)->getJson("/api/projects/{$project->id}");

    $response->assertStatus(200)
        ->assertJsonFragment(['name' => $project->name]);
});

it('can create a project', function () {
    $project = Project::factory()->make([
        'name' => 'Project 1',
        'description' => 'Project 1 description',
    ]);
    $response = $this->actingAs($this->user)->postJson('/api/projects/store', $project->toArray());

    $response->assertStatus(201)
        ->assertJsonPath('data.name', 'Project 1')
        ->assertJsonPath('data.description', 'Project 1 description');
});

it('can update a project', function () {
    $project = Project::factory()->create([
        'name' => 'Project 2',
        'description' => 'Project 2 description',
        'owner_id' => $this->user->id,
    ]);

    $response = $this->actingAs($this->user)->putJson("/api/projects/{$project->id}", $project->toArray());

    $response->assertStatus(200)
        ->assertJsonPath('data.name', 'Project 2')
        ->assertJsonPath('data.description', 'Project 2 description');
});

it('can delete a project', function () {
    $project = Project::factory()->create(['owner_id' => $this->user->id]);

    $response = $this->actingAs($this->user)->deleteJson("/api/projects/{$project->id}");

    $response->assertStatus(200)
        ->assertJson(['message' => 'Project deleted successfully']);
});
