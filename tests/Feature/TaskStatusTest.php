<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskStatusTest extends TestCase
{
    use RefreshDatabase;

    public function testTaskStatusesPageIsDisplayedForAuthenticatedUser(): void
    {
        $user = User::factory()->create();
        $status = TaskStatus::query()->create(['name' => 'new']);

        $response = $this->actingAs($user)->get(route('task_statuses.index'));

        $response->assertOk();
        $response->assertSeeText($status->name);
    }

    public function testGuestsCannotManageTaskStatuses(): void
    {
        $taskStatus = TaskStatus::query()->create(['name' => 'new']);

        $this->get(route('task_statuses.index'))->assertRedirect(route('login'));
        $this->get(route('task_statuses.create'))->assertRedirect(route('login'));
        $this->post(route('task_statuses.store'), ['name' => 'draft'])->assertRedirect(route('login'));
        $this->get(route('task_statuses.edit', $taskStatus))->assertRedirect(route('login'));
        $this->patch(route('task_statuses.update', $taskStatus), ['name' => 'updated'])->assertRedirect(route('login'));
        $this->delete(route('task_statuses.destroy', $taskStatus))->assertRedirect(route('login'));
    }

    public function testTaskStatusCanBeCreated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('task_statuses.store'), [
            'name' => 'new',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('task_statuses.index'));

        $this->assertDatabaseHas('task_statuses', ['name' => 'new']);
    }

    public function testTaskStatusCreationValidatesName(): void
    {
        $user = User::factory()->create();
        TaskStatus::query()->create(['name' => 'existing']);

        $this->actingAs($user)
            ->from(route('task_statuses.create'))
            ->post(route('task_statuses.store'), ['name' => ''])
            ->assertSessionHasErrors(['name'])
            ->assertRedirect(route('task_statuses.create'));

        $this->actingAs($user)
            ->from(route('task_statuses.create'))
            ->post(route('task_statuses.store'), ['name' => 'existing'])
            ->assertSessionHasErrors(['name'])
            ->assertRedirect(route('task_statuses.create'));
    }

    public function testTaskStatusCanBeUpdated(): void
    {
        $user = User::factory()->create();
        $taskStatus = TaskStatus::query()->create(['name' => 'old']);

        $response = $this->actingAs($user)->patch(route('task_statuses.update', $taskStatus), [
            'name' => 'updated',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('task_statuses.index'));

        $this->assertDatabaseHas('task_statuses', [
            'id' => $taskStatus->id,
            'name' => 'updated',
        ]);
    }

    public function testTaskStatusCanBeDeletedIfUnused(): void
    {
        $user = User::factory()->create();
        $taskStatus = TaskStatus::query()->create(['name' => 'unused']);

        $response = $this->actingAs($user)->delete(route('task_statuses.destroy', $taskStatus));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('task_statuses.index'));

        $this->assertDatabaseMissing('task_statuses', ['id' => $taskStatus->id]);
    }

    public function testTaskStatusCannotBeDeletedIfUsedByTask(): void
    {
        $user = User::factory()->create();
        $taskStatus = TaskStatus::query()->create(['name' => 'in use']);

        Task::query()->create([
            'name' => 'Task 1',
            'task_status_id' => $taskStatus->id,
        ]);

        $response = $this->actingAs($user)->delete(route('task_statuses.destroy', $taskStatus));

        $response->assertRedirect(route('task_statuses.index'));
        $response->assertSessionHasErrors();
        $this->assertDatabaseHas('task_statuses', ['id' => $taskStatus->id]);
    }
}

