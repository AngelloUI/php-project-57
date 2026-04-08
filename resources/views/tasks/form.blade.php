<div>
    <x-input-label for="name" :value="__('tasks.form.name')" />
    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $task->name ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="description" :value="__('tasks.form.description')" />
    <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description', $task->description ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="status_id" :value="__('tasks.form.status')" />
    <select id="status_id" name="status_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
        <option value=""></option>
        @foreach ($statuses as $status)
            <option value="{{ $status->id }}" @selected((int) old('status_id', $task->status_id ?? 0) === $status->id)>{{ $status->name }}</option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('status_id')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="assigned_to_id" :value="__('tasks.form.assigned_to')" />
    <select id="assigned_to_id" name="assigned_to_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
        <option value=""></option>
        @foreach ($users as $user)
            <option value="{{ $user->id }}" @selected((int) old('assigned_to_id', $task->assigned_to_id ?? 0) === $user->id)>{{ $user->name }}</option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('assigned_to_id')" class="mt-2" />
</div>

<div class="mt-4">
    <x-primary-button>{{ __('tasks.actions.save') }}</x-primary-button>
</div>

