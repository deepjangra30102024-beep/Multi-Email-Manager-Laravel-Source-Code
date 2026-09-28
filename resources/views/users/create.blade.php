<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('users.index') }}" class="mr-3 text-gray-400 hover:text-indigo-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Create New User') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                
                <div class="bg-indigo-50/50 border-b border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800">User Information</h3>
                    <p class="text-sm text-gray-500 mt-1">Enter the details to create a new system user.</p>
                </div>

                <div class="p-8">
                    <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
                        @csrf

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" value="Full Name" class="text-gray-700 font-medium" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <x-text-input id="name" class="pl-10 block w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm transition-colors" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div>
                            <x-input-label for="email" value="Email Address" class="text-gray-700 font-medium" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                </div>
                                <x-text-input id="email" class="pl-10 block w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm transition-colors" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="john@example.com" />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Password -->
                            <div>
                                <x-input-label for="password" value="Password" class="text-gray-700 font-medium" />
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <x-text-input id="password" class="pl-10 block w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm transition-colors"
                                                    type="password"
                                                    name="password"
                                                    required autocomplete="new-password" placeholder="••••••••" />
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <x-input-label for="password_confirmation" value="Confirm Password" class="text-gray-700 font-medium" />
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2-2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <x-text-input id="password_confirmation" class="pl-10 block w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm transition-colors"
                                                    type="password"
                                                    name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                                </div>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Is Admin -->
                        <div class="pt-4 border-t border-gray-100">
                            <label for="is_admin" class="inline-flex items-center group cursor-pointer">
                                <div class="relative flex items-center">
                                    <input id="is_admin" type="checkbox" class="peer h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 focus:ring-2 focus:ring-offset-2 transition-all cursor-pointer" name="is_admin" value="1">
                                </div>
                                <div class="ml-3 flex flex-col">
                                    <span class="text-sm font-medium text-gray-800 group-hover:text-indigo-600 transition-colors">Administrator Access</span>
                                    <span class="text-xs text-gray-500">Grant this user full access to the system</span>
                                </div>
                            </label>
                        </div>

                        <div class="flex items-center justify-end pt-6 border-t border-gray-100 mt-6 gap-3">
                            <a href="{{ route('users.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-sm">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md hover:shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
