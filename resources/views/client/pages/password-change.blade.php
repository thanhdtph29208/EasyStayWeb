@extends('client.layouts.master1')

@section('content')
    <div class="container mx-auto py-32">
        <div class="w-full max-w-md mx-auto">
            <div class=" bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <h2 class="text-xl font-bold mb-6">{{ __('Change Password') }}</h2>

                <!-- Form -->
                <form method="POST" action="{{ route('client.pages.password-update') }}" class="max-w-sm mx-auto">
                    @csrf
                    @method('PUT')

                    <div class="mb-5">
                        <label for="current_password"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Current Password') }}</label>
                        <div class="relative">
                            <input id="current_password" type="password"
                                class="form-input pr-10 @error('current_password') border-red-500 @enderror"
                                name="current_password" required>
                            <button type="button" class="absolute inset-y-0 right-0 px-3 py-2 focus:outline-none"
                                onclick="togglePasswordVisibility('current_password')">
                                <svg class="h-6 w-6 text-gray-500 hover:text-gray-700 transition duration-300"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.536 8.464A9.956 9.956 0 0112 10c-1.695 0-3.291-.416-4.736-1.152m7.906 4.792C18.701 14.803 20 12.972 20 11c0-3.313-3.134-6-8-6s-8 2.687-8 6c0 1.972 1.299 3.803 3.83 5.104M4 11a6 6 0 1112 0 6 6 0 01-12 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 14l9-5-9-5-9 5 9 5z" />
                                </svg>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label for="new_password"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('New Password') }}</label>
                        <input id="new_password" type="password"
                            class="form-input @error('new_password') border-red-500 @enderror" name="new_password" required>
                        @error('new_password')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label for="new_password_confirmation"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Confirm New Password') }}</label>
                        <input id="new_password_confirmation" type="password" class="form-input"
                            name="new_password_confirmation" required>
                    </div>

                    <button type="submit" class="btn">{{ __('Save Changes') }}</button>
                </form>
                <!-- End Form -->

            </div>
        </div>
    </div>
@endsection
