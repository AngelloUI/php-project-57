<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('task_statuses.title.index') }}
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

                    @if ($errors->any())
                        <div class="mb-4 text-sm text-red-600 dark:text-red-400">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    @auth
                        <div class="mb-4">
                            <a href="{{ route('task_statuses.create') }}" class="underline">
                                {{ __('task_statuses.actions.create') }}
                            </a>
                        </div>
                    @endauth

                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                                    <th class="py-2 pe-4">ID</th>
                                    <th class="py-2 pe-4">{{ __('task_statuses.form.name') }}</th>
                                    @auth
                                        <th class="py-2 pe-4">{{ __('task_statuses.actions.actions') }}</th>
                                    @endauth
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($taskStatuses as $taskStatus)
                                    <tr class="border-b border-gray-100 dark:border-gray-700">
                                        <td class="py-2 pe-4">{{ $taskStatus->id }}</td>
                                        <td class="py-2 pe-4">{{ $taskStatus->name }}</td>
                                        @auth
                                            <td class="py-2 pe-4">
                                                <a href="{{ route('task_statuses.edit', $taskStatus) }}" class="underline me-3">
                                                    {{ __('task_statuses.actions.edit') }}
                                                </a>

                                                <form action="{{ route('task_statuses.destroy', $taskStatus) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="underline text-red-600 dark:text-red-400">
                                                        {{ __('task_statuses.actions.delete') }}
                                                    </button>
                                                </form>
                                            </td>
                                        @endauth
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

