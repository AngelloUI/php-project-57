<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('tasks.title.show') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-3">{{ $task->name }}</h3>
                    <p class="mb-3">{{ $task->description }}</p>
                    <p class="mb-2"><strong>{{ __('tasks.form.status') }}:</strong> {{ $task->status->name }}</p>
                    <p class="mb-2"><strong>{{ __('tasks.form.created_by') }}:</strong> {{ $task->creator->name }}</p>
                    <p class="mb-4"><strong>{{ __('tasks.form.assigned_to') }}:</strong> {{ $task->assignee?->name ?? '' }}</p>

                    <a href="{{ route('tasks.edit', $task) }}" class="underline me-3">{{ __('tasks.actions.edit') }}</a>

                    @if ((int) auth()->id() === (int) $task->created_by_id)
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="underline text-red-600 dark:text-red-400">{{ __('tasks.actions.delete') }}</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

