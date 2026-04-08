<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskStatusRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Models\TaskStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TaskStatusController extends Controller
{
    public function index(): View
    {
        $taskStatuses = TaskStatus::query()
            ->orderBy('id')
            ->withCount('tasks')
            ->get();

        return view('task_statuses.index', compact('taskStatuses'));
    }

    public function create(): View
    {
        return view('task_statuses.create');
    }

    public function store(StoreTaskStatusRequest $request): RedirectResponse
    {
        TaskStatus::query()->create($request->validated());

        return Redirect::route('task_statuses.index')
            ->with('status', __('task_statuses.flash.created'));
    }

    public function edit(TaskStatus $taskStatus): View
    {
        return view('task_statuses.edit', compact('taskStatus'));
    }

    public function update(UpdateTaskStatusRequest $request, TaskStatus $taskStatus): RedirectResponse
    {
        $taskStatus->update($request->validated());

        return Redirect::route('task_statuses.index')
            ->with('status', __('task_statuses.flash.updated'));
    }

    public function destroy(TaskStatus $taskStatus): RedirectResponse
    {
        if ($taskStatus->tasks()->exists()) {
            return Redirect::route('task_statuses.index')
                ->with('error', __('task_statuses.flash.delete_forbidden'));
        }

        $taskStatus->delete();

        return Redirect::route('task_statuses.index')
            ->with('status', __('task_statuses.flash.deleted'));
    }
}


