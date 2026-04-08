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

                    <form method="GET" action="{{ route('tasks.index') }}" class="mb-6">
                        <div class="flex flex-wrap gap-4 items-end">
                            <div>
                                <x-input-label for="filter_status_id" :value="__('tasks.form.status')" />
                                <select id="filter_status_id" name="filter[status_id]"
                                        class="block mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">---</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->id }}"
                                                @selected(request('filter.status_id') == $status->id)>
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-input-label for="filter_assigned_to_id" :value="__('tasks.form.assigned_to')" />
                                <select id="filter_assigned_to_id" name="filter[assigned_to_id]"
                                        class="block mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">---</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                                @selected(request('filter.assigned_to_id') == $user->id)>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-input-label for="filter_label_id" :value="__('tasks.form.labels')" />
                                <select id="filter_label_id" name="filter[label_id]"
                                        class="block mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">---</option>
                                    @foreach ($labels as $label)
                                        <option value="{{ $label->id }}"
                                                @selected(request('filter.label_id') == $label->id)>
                                            {{ $label->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-primary-button type="submit">{{ __('tasks.filter.submit') }}</x-primary-button>
                            </div>
                        </div>
                    </form>

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
