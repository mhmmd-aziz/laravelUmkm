@props(['value', 'name', 'id'])

<input 
    type="radio" 
    name="{{ $name }}" 
    value="{{ $value }}" 
    id="{{ $id }}"
    {{ $attributes->merge(['class' => 'h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800']) }}
>
