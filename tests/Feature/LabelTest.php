<?php

namespace Tests\Feature;

use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LabelTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestsCannotManageLabels(): void
    {
        $label = Label::query()->create(['name' => 'bug']);

        $this->get(route('labels.index'))->assertRedirect(route('login'));
        $this->get(route('labels.create'))->assertRedirect(route('login'));
        $this->post(route('labels.store'), ['name' => 'feature'])->assertRedirect(route('login'));
        $this->get(route('labels.edit', $label))->assertRedirect(route('login'));
        $this->patch(route('labels.update', $label), ['name' => 'updated'])->assertRedirect(route('login'));
        $this->delete(route('labels.destroy', $label))->assertRedirect(route('login'));
    }

    public function testLabelsPageIsDisplayed(): void
    {
        $user = User::factory()->create();
        $label = Label::query()->create(['name' => 'bug', 'description' => 'A bug']);

        $this->actingAs($user)
            ->get(route('labels.index'))
            ->assertOk()
            ->assertSeeText('bug');
    }

    public function testLabelCanBeCreated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('labels.store'), [
            'name' => 'feature',
            'description' => 'A new feature',
        ]);

        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseHas('labels', ['name' => 'feature', 'description' => 'A new feature']);
    }

    public function testLabelCreationValidatesName(): void
    {
        $user = User::factory()->create();
        Label::query()->create(['name' => 'existing']);

        $this->actingAs($user)
            ->from(route('labels.create'))
            ->post(route('labels.store'), ['name' => ''])
            ->assertSessionHasErrors(['name'])
            ->assertRedirect(route('labels.create'));

        $this->actingAs($user)
            ->from(route('labels.create'))
            ->post(route('labels.store'), ['name' => 'existing'])
            ->assertSessionHasErrors(['name'])
            ->assertRedirect(route('labels.create'));
    }

    public function testLabelCanBeUpdated(): void
    {
        $user = User::factory()->create();
        $label = Label::query()->create(['name' => 'old']);

        $response = $this->actingAs($user)->patch(route('labels.update', $label), [
            'name' => 'updated',
            'description' => 'Updated description',
        ]);

        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseHas('labels', ['id' => $label->id, 'name' => 'updated']);
    }

    public function testLabelCanBeDeletedIfUnused(): void
    {
        $user = User::factory()->create();
        $label = Label::query()->create(['name' => 'unused']);

        $this->actingAs($user)
            ->delete(route('labels.destroy', $label))
            ->assertRedirect(route('labels.index'));

        $this->assertDatabaseMissing('labels', ['id' => $label->id]);
    }

    public function testLabelCannotBeDeletedIfUsedByTask(): void
    {
        $user = User::factory()->create();
        $status = TaskStatus::query()->create(['name' => 'new']);
        $label = Label::query()->create(['name' => 'in-use']);

        $task = Task::query()->create([
            'name' => 'Task 1',
            'status_id' => $status->id,
            'created_by_id' => $user->id,
        ]);
        $task->labels()->attach($label->id);

        $response = $this->actingAs($user)->delete(route('labels.destroy', $label));

        $response->assertRedirect(route('labels.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('labels', ['id' => $label->id]);
    }

    public function testLabelsCanBeAttachedToTaskOnCreate(): void
    {
        $user = User::factory()->create();
        $status = TaskStatus::query()->create(['name' => 'new']);
        $label1 = Label::query()->create(['name' => 'bug']);
        $label2 = Label::query()->create(['name' => 'feature']);

        $this->actingAs($user)->post(route('tasks.store'), [
            'name' => 'Task with labels',
            'status_id' => $status->id,
            'labels' => [$label1->id, $label2->id],
        ]);

        $task = Task::query()->where('name', 'Task with labels')->first();
        $this->assertCount(2, $task->labels);
        $this->assertTrue($task->labels->contains($label1));
        $this->assertTrue($task->labels->contains($label2));
    }

    public function testLabelsAreShownOnTaskShowPage(): void
    {
        $user = User::factory()->create();
        $status = TaskStatus::query()->create(['name' => 'new']);
        $label = Label::query()->create(['name' => 'my-label']);

        $task = Task::query()->create([
            'name' => 'Task 1',
            'status_id' => $status->id,
            'created_by_id' => $user->id,
        ]);
        $task->labels()->attach($label->id);

        $this->actingAs($user)
            ->get(route('tasks.show', $task))
            ->assertOk()
            ->assertSeeText('my-label');
    }
}

