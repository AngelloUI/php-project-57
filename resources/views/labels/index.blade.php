<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('labels.title.index') }}
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

                    @if (session('error'))
                        <div class="mb-4 text-sm text-red-600 dark:text-red-400">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <a href="{{ route('labels.create') }}" class="underline">{{ __('labels.actions.create') }}</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                                    <th class="py-2 pe-4">ID</th>
                                    <th class="py-2 pe-4">{{ __('labels.form.name') }}</th>
                                    <th class="py-2 pe-4">{{ __('labels.form.description') }}</th>
                                    <th class="py-2 pe-4">{{ __('labels.actions.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($labels as $label)
                                    <tr class="border-b border-gray-100 dark:border-gray-700">
                                        <td class="py-2 pe-4">{{ $label->id }}</td>
                                        <td class="py-2 pe-4">{{ $label->name }}</td>
                                        <td class="py-2 pe-4">{{ $label->description }}</td>
                                        <td class="py-2 pe-4">
                                            <a href="{{ route('labels.edit', $label) }}" class="underline me-3">{{ __('labels.actions.edit') }}</a>
                                            <form action="{{ route('labels.destroy', $label) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="underline text-red-600 dark:text-red-400">{{ __('labels.actions.delete') }}</button>
                                            </form>
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

