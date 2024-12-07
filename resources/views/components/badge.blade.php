@props(['color'])

<?php
    $colors = [
        'green' => "mr-4 p-2 bg-green-100 text-green-800 text-sm font-bold me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-green-400 border border-green-400 flex items-center",
        'blue' => "mr-4 p-2 bg-blue-100 text-blue-800 text-sm font-bold me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400 flex items-center",
        'red' => "mr-4 p-2 bg-red-100 text-red-800 text-sm font-bold me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-red-400 border border-red-400 flex items-center",
        'black' => "mr-4 p-2 bg-gray-100 text-gray-800 text-sm font-bold me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-400 border border-gray-500 flex items-center",
    ]
?>

<div class="{{ $colors[$color] }}">
    {{ $slot }}
</div>
