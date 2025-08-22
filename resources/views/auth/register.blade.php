<x-guest-layout>
    <form method="POST" action="{{ route('register') }}"class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
        @csrf

        <div class="flexflex-col space-y-2">
            <h1 class="text-center font-semibold text-2xl tracking-tight">Register your account</h1>
            <p class="text-center text-gray-400">Enter your credentials</p>
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-center mt-4">
            <x-primary-button class="ms-3 w-1/2 h-10 flex items-center justify-center text-center">
                {{ __('Register') }}
            </x-primary-button>
        </div>

        <!-- Divider -->
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <span class="w-full border-t"></span>
            </div>
            <div class="relative flex justify-center text-xs">
                <span class="bg-white px-2 text-gray-500">Or continue with</span>
            </div>
        </div>

        <!-- Social Buttons -->
        <div class="flex space-x-2">
            <!-- Google -->
            <a href="/auth/google/redirect"
                class="inline-flex items-center justify-center w-1/2 border border-gray-300 rounded-lg px-4 py-2 text-sm hover:bg-gray-100 transition gap-2">
                <svg class="h-4 w-4" viewBox="0 0 48 48">
                    <path fill="#EA4335" d="M24 9.5c3.5 0 6.4 1.3 8.4 3.2l6.3-6.3C34.4 2.7 29.6 0 24 0 14.8 0 7.1 5.9 3.7 14.1l7.4 5.7C12.9 14.3 17.9 9.5 24 9.5z" />
                    <path fill="#4285F4" d="M46.5 24.5c0-1.7-.1-3-.4-4.5H24v9h12.7c-.5 2.5-1.9 4.5-3.8 5.9l7.5 5.8c4.3-4 6.8-9.8 6.8-16.2z" />
                    <path fill="#FBBC05" d="M10.7 28.6c-.6-1.5-1-3.1-1-4.6s.4-3.1 1-4.6L3.3 14c-1.6 3.2-2.6 6.8-2.6 10.6s.9 7.4 2.6 10.6l7.4-5.6z" />
                    <path fill="#34A853" d="M24 48c5.6 0 10.3-1.8 13.7-5l-7.5-5.8c-2 1.4-4.5 2.3-7.2 2.3-6.1 0-11.1-4.8-12.9-11.2l-7.4 5.7C7.1 42.1 14.8 48 24 48z" />
                    <path fill="none" d="M0 0h48v48H0z" />
                </svg>
                Google
            </a>

            <!-- GitHub -->
            <a href="/auth/google/redirect"
                class="inline-flex items-center justify-center w-1/2 border border-gray-300 rounded-lg px-4 py-2 text-sm hover:bg-gray-100 transition gap-2">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 0C5.37 0 0 5.373 0 12c0 5.303 3.438 9.8 8.207 11.387.6.113.793-.258.793-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61-.546-1.385-1.333-1.754-1.333-1.754-1.089-.745.084-.729.084-.729 1.205.084 1.84 1.236 1.84 1.236 1.07 1.835 2.809 1.305 3.495.997.108-.776.418-1.305.76-1.605-2.665-.305-5.467-1.335-5.467-5.932 0-1.31.469-2.38 1.236-3.22-.124-.304-.536-1.523.117-3.176 0 0 1.008-.322 3.3 1.23a11.5 11.5 0 013.003-.403c1.02.005 2.045.138 3.003.403 2.292-1.552 3.297-1.23 3.297-1.23.655 1.653.243 2.872.12 3.176.77.84 1.233 1.91 1.233 3.22 0 4.61-2.807 5.624-5.48 5.92.43.37.81 1.102.81 2.222 0 1.604-.015 2.896-.015 3.29 0 .32.192.694.8.576C20.565 21.796 24 17.3 24 12c0-6.627-5.373-12-12-12z" />
                </svg>
                GitHub
            </a>
        </div>

        <!-- Login Link -->
        <div class="flex justify-center mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}">
                {{ __('Already have an account? Login') }}
            </a>
        </div>
    </form>
</x-guest-layout>
