@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'p-2 mb-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500']) }}>
