@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-teal-600 dark:text-teal-600']) }}>
    {{ $value ?? $slot }}
</label>
