<div>
    <x-input-label for="name" :value="__('task_statuses.form.name')" />
    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $taskStatus->name ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div class="flex items-center gap-4 mt-4">
    <x-primary-button>{{ __('task_statuses.actions.save') }}</x-primary-button>
</div>

