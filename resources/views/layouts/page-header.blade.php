<div class="mb-6 mt-6">
    {{-- Title --}}
    @isset($title)
        <h1 class="text-2xl font-bold text-gray-800 mb-2">
            {{ $title }}
        </h1>
    @endisset

    {{-- Breadcrumb --}}
    @isset($breadcrumbs)
        <x-breadcrumb :items="$breadcrumbs" />
    @endisset


</div>
