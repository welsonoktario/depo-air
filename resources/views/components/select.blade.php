@props(['disabled' => false])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' =>
        'w-full rounded-md shadow-sm border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50',
]) !!}>
  {{ $slot }}
</select>
