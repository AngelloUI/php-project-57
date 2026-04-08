<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(): View
    {
        $tasks = Task::query()
            ->with(['status', 'creator', 'assignee', 'labels'])
            ->orderBy('id')
            ->get();

        return view('tasks.index', compact('tasks'));
    }

    public function show(Task $task): View
    {
        $task->load(['status', 'creator', 'assignee', 'labels']);

        return view('tasks.show', compact('task'));
    }

    public function create(): View
    {
        $task = new Task();
        $statuses = TaskStatus::query()->orderBy('name')->get();
        $users = User::query()->orderBy('name')->get();
        $labels = Label::query()->orderBy('name')->get();

        return view('tasks.create', compact('task', 'statuses', 'users', 'labels'));
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $labelIds = $data['labels'] ?? [];
        unset($data['labels']);
        $data['created_by_id'] = (int) $request->user()->id;

        $task = Task::query()->create($data);
        $task->labels()->sync($labelIds);

        return Redirect::route('tasks.index')
            ->with('status', __('tasks.flash.created'));
    }

    public function edit(Task $task): View
    {
        $statuses = TaskStatus::query()->orderBy('name')->get();
        $users = User::query()->orderBy('name')->get();
        $labels = Label::query()->orderBy('name')->get();
        $task->load('labels');

        return view('tasks.edit', compact('task', 'statuses', 'users', 'labels'));
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $data = $request->validated();
        $labelIds = $data['labels'] ?? [];
        unset($data['labels']);

        $task->update($data);
        $task->labels()->sync($labelIds);

        return Redirect::route('tasks.index')
            ->with('status', __('tasks.flash.updated'));
    }

    public function destroy(Task $task): RedirectResponse
    {
        abort_unless((int) auth()->id() === (int) $task->created_by_id, 403);

        $task->delete();

        return Redirect::route('tasks.index')
            ->with('status', __('tasks.flash.deleted'));
    }
}

