<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestsCannotManageTasks(): void
    {
        $creator = User::factory()->create();
        $status = TaskStatus::query()->create(['name' => 'new']);
        $task = Task::query()->create([
            'name' => 'Task 1',
            'description' => 'Description',
            'status_id' => $status->id,
            'created_by_id' => $creator->id,
        ]);

        $this->get(route('tasks.index'))->assertRedirect(route('login'));
        $this->get(route('tasks.create'))->assertRedirect(route('login'));
        $this->post(route('tasks.store'), [])->assertRedirect(route('login'));
        $this->get(route('tasks.show', $task))->assertRedirect(route('login'));
        $this->get(route('tasks.edit', $task))->assertRedirect(route('login'));
        $this->patch(route('tasks.update', $task), [])->assertRedirect(route('login'));
        $this->delete(route('tasks.destroy', $task))->assertRedirect(route('login'));
    }

    public function testTaskCanBeCreated(): void
    {
        $creator = User::factory()->create();
        $assignee = User::factory()->create();
        $status = TaskStatus::query()->create(['name' => 'new']);

        $response = $this->actingAs($creator)->post(route('tasks.store'), [
            'name' => 'My task',
            'description' => 'Task description',
            'status_id' => $status->id,
            'assigned_to_id' => $assignee->id,
        ]);

        $response->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', [
            'name' => 'My task',
            'description' => 'Task description',
            'status_id' => $status->id,
            'created_by_id' => $creator->id,
            'assigned_to_id' => $assignee->id,
        ]);
    }

    public function testTaskPagesAreDisplayed(): void
    {
        $creator = User::factory()->create();
        $status = TaskStatus::query()->create(['name' => 'new']);
        $task = Task::query()->create([
            'name' => 'Task 1',
            'description' => 'Description',
            'status_id' => $status->id,
            'created_by_id' => $creator->id,
        ]);

        $this->actingAs($creator)
            ->get(route('tasks.index'))
            ->assertOk()
            ->assertSeeText('Task 1');

        $this->actingAs($creator)
            ->get(route('tasks.show', $task))
            ->assertOk()
            ->assertSeeText('Task 1');

        $this->actingAs($creator)
            ->get(route('tasks.edit', $task))
            ->assertOk();
    }

    public function testTaskCanBeUpdated(): void
    {
        $creator = User::factory()->create();
        $status = TaskStatus::query()->create(['name' => 'new']);
        $updatedStatus = TaskStatus::query()->create(['name' => 'in progress']);

        $task = Task::query()->create([
            'name' => 'Task 1',
            'description' => 'Description',
            'status_id' => $status->id,
            'created_by_id' => $creator->id,
        ]);

        $response = $this->actingAs($creator)->patch(route('tasks.update', $task), [
            'name' => 'Updated task',
            'description' => 'Updated description',
            'status_id' => $updatedStatus->id,
            'assigned_to_id' => null,
        ]);

        $response->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'name' => 'Updated task',
            'description' => 'Updated description',
            'status_id' => $updatedStatus->id,
        ]);
    }

    public function testOnlyCreatorCanDeleteTask(): void
    {
        $creator = User::factory()->create();
        $otherUser = User::factory()->create();
        $status = TaskStatus::query()->create(['name' => 'new']);

        $task = Task::query()->create([
            'name' => 'Task 1',
            'description' => 'Description',
            'status_id' => $status->id,
            'created_by_id' => $creator->id,
        ]);

        $this->actingAs($otherUser)
            ->delete(route('tasks.destroy', $task))
            ->assertForbidden();

        $this->assertDatabaseHas('tasks', ['id' => $task->id]);

        $this->actingAs($creator)
            ->delete(route('tasks.destroy', $task))
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
