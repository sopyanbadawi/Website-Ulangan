<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-100 px-3 sm:px-0">

    <div class="w-full sm:max-w-md bg-white rounded-xl shadow-lg p-6 sm:p-8 mx-auto">

        {{-- Logo --}}
        <div class="flex justify-center mb-3">
            <img src="{{ url('exam.png') }}" alt="Exam" class="h-12 sm:h-10 w-auto">
        </div>

        {{-- Welcome Text --}}
        <div class="text-center mb-4">
            <h1 class="text-xl font-semibold text-gray-800">
                Selamat Datang
            </h1>
            <p class="text-sm text-gray-500 mt-1 leading-relaxed">
                Silakan masukkan username dan password Anda
            </p>
        </div>

        {{-- Session Status --}}
        @if (session('status'))
            <div class="mb-4 text-sm text-green-600 text-center">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Username --}}
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">
                    Username
                </label>
                <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus
                    autocomplete="username"
                    class="mt-1 block w-full rounded-lg border-gray-300
                              focus:border-indigo-500 focus:ring-indigo-500
                              py-2.5 px-3">

                @error('username')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mt-4">
                <label for="password" class="block text-sm font-medium text-gray-700">
                    Password
                </label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="mt-1 block w-full rounded-lg border-gray-300
                              focus:border-indigo-500 focus:ring-indigo-500
                              py-2.5 px-3">

                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember Me --}}
            {{-- <div class="mt-4 flex items-center">
                <input id="remember_me" type="checkbox" name="remember"
                    class="rounded border-gray-300 text-indigo-600
                              focus:ring-indigo-500">

                <label for="remember_me" class="ms-2 text-sm text-gray-600">
                    Remember me
                </label>
            </div> --}}

            {{-- Action --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3 mt-6">
                {{-- @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="underline text-sm text-gray-600 hover:text-gray-900 text-center sm:text-left">
                        Forgot your password?
                    </a>
                @endif --}}

                <button type="submit"
                    class="inline-flex justify-center items-center px-4 py-2.5
                               bg-indigo-600 border border-transparent
                               rounded-lg font-semibold text-sm text-white
                               uppercase tracking-widest
                               hover:bg-indigo-700 focus:outline-none
                               focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Log in
                </button>
            </div>

        </form>
    </div>

</body>

</html>
