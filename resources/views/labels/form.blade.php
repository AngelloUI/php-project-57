<div>
    <x-input-label for="name" :value="__('labels.form.name')" />
    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $label->name ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="description" :value="__('labels.form.description')" />
    <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description', $label->description ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

<div class="mt-4">
    <x-primary-button>{{ __('labels.actions.save') }}</x-primary-button>
</div>

