@props(['items' => []])

<ol class="flex items-center whitespace-nowrap">
    @foreach ($items as $index => $item)
        @if (!$loop->last)
            <li class="inline-flex items-center">
                <a href="{{ $item['url'] ?? '#' }}"
                    class="flex items-center text-sm text-gray-500 hover:text-blue-600 focus:outline-none dark:text-neutral-500 dark:hover:text-blue-500">
                    {{ $item['label'] }}
                </a>

                <svg class="shrink-0 mx-2 size-4 text-gray-400 dark:text-neutral-600" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6" />
                </svg>
            </li>
        @else
            <li class="inline-flex items-center text-sm font-semibold text-gray-800 truncate dark:text-neutral-200"
                aria-current="page">
                {{ $item['label'] }}
            </li>
        @endif
    @endforeach
</ol>
