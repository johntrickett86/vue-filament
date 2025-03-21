@php
    $appName = config('app.name');
@endphp

<div class="mr-5 flex items-center space-x-2 gap-x-3 px-2 py-4">
    <div class="flex aspect-square h-8 w-8 items-center justify-center rounded-md bg-primary-500 dark:bg-primary-500">
        <x-app-logo-icon class="h-5 w-5 fill-current text-white dark:text-gray-800" />
    </div>
    <div class="ml-1 grid flex-1 text-left text-sm">
        <div class="grid flex-1 text-left text-sm leading-tight">
            <span class="truncate font-semibold leading-none">Stitch Digital</span>
            <span class="truncate text-xs">{{ $appName }}</span>
        </div>
    </div>
</div>
