@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @for ($i = 0; $i < 3; $i++)
        <div class="flex flex-col bg-white border border-gray-200 shadow-2xs rounded-xl">
            <div class="p-4 md:p-5">
                <h3 class="text-lg font-bold text-gray-800">
                    Card title
                </h3>
                <p class="mt-2 text-gray-500">
                    With supporting text below as a natural lead-in to additional content.
                </p>
                <a class="mt-3 inline-flex items-center gap-x-1 text-sm font-semibold text-blue-600 hover:text-blue-700 hover:underline"
                    href="#">
                    Card link
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m9 18 6-6-6-6" />
                    </svg>
                </a>
            </div>
        </div>
        @endfor
    </div>

    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Admin Dashboard</h1>
        <p class="mt-2 text-gray-600">
            Welcome to the admin dashboard. Here you can manage the application.
        </p>
    </div>

</div>
@endsection
