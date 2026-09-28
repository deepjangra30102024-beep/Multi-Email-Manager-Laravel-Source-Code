<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold mb-6">Google Application Settings</h2>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="google_client_id" class="block text-sm font-medium text-gray-700">Google Client ID</label>
                            <input type="text" name="google_client_id" id="google_client_id" 
                                value="{{ old('google_client_id', $googleClientId) }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border" 
                                required>
                            @error('google_client_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1">Get this from your Google Cloud Console.</p>
                        </div>

                        <div class="mb-4">
                            <label for="google_client_secret" class="block text-sm font-medium text-gray-700">Google Client Secret</label>
                            <input type="text" name="google_client_secret" id="google_client_secret" 
                                value="{{ old('google_client_secret', $googleClientSecret) }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border" 
                                required>
                            @error('google_client_secret')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="google_redirect_uri" class="block text-sm font-medium text-gray-700">Google Redirect URI</label>
                            <input type="text" name="google_redirect_uri" id="google_redirect_uri" 
                                value="{{ old('google_redirect_uri', $googleRedirectUri) }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border" 
                                required>
                            @error('google_redirect_uri')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1">Usually: {{ url('/oauth/google/callback') }}</p>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
