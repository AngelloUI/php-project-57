<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('task_statuses.title.edit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 max-w-xl">
                    <form method="POST" action="{{ route('task_statuses.update', $taskStatus) }}">
                        @csrf
                        @method('PATCH')
                        @include('task_statuses.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

