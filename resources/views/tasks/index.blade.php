<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('tasks.title.index') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('status'))
                        <div class="mb-4 text-sm text-green-600 dark:text-green-400">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <a href="{{ route('tasks.create') }}" class="underline">{{ __('tasks.actions.create') }}</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                                    <th class="py-2 pe-4">ID</th>
                                    <th class="py-2 pe-4">{{ __('tasks.form.name') }}</th>
                                    <th class="py-2 pe-4">{{ __('tasks.form.status') }}</th>
                                    <th class="py-2 pe-4">{{ __('tasks.form.created_by') }}</th>
                                    <th class="py-2 pe-4">{{ __('tasks.form.assigned_to') }}</th>
                                    <th class="py-2 pe-4">{{ __('tasks.actions.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tasks as $task)
                                    <tr class="border-b border-gray-100 dark:border-gray-700">
                                        <td class="py-2 pe-4">{{ $task->id }}</td>
                                        <td class="py-2 pe-4">
                                            <a href="{{ route('tasks.show', $task) }}" class="underline">{{ $task->name }}</a>
                                        </td>
                                        <td class="py-2 pe-4">{{ $task->status->name }}</td>
                                        <td class="py-2 pe-4">{{ $task->creator->name }}</td>
                                        <td class="py-2 pe-4">{{ $task->assignee?->name ?? '' }}</td>
                                        <td class="py-2 pe-4">
                                            <a href="{{ route('tasks.edit', $task) }}" class="underline me-3">{{ __('tasks.actions.edit') }}</a>
                                            @if ((int) auth()->id() === (int) $task->created_by_id)
                                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="underline text-red-600 dark:text-red-400">{{ __('tasks.actions.delete') }}</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

