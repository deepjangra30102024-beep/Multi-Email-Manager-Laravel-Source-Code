<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Navigation & Actions -->
            <div class="mb-6 flex justify-between items-center">
                <a href="{{ route('inbox.index', $emailAccount) }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-indigo-600 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Inbox
                </a>
            </div>

            @if(session('error'))
                <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                
                <!-- Email Header Section -->
                <div class="bg-gray-50/50 border-b border-gray-100 p-8">
                    <h1 class="text-2xl font-bold text-gray-900 mb-6 leading-tight">{{ $email['subject'] }}</h1>
                    
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                        <div class="flex items-start space-x-4">
                            <!-- Avatar Placeholder -->
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center border border-indigo-200">
                                    <span class="text-indigo-700 font-bold text-lg">
                                        {{ strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $email['from']), 0, 1) ?: '@') }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Sender Details -->
                            <div class="flex flex-col">
                                <p class="text-base font-semibold text-gray-900 truncate max-w-lg">
                                    {{ $email['from'] }}
                                </p>
                                <p class="text-sm text-gray-500 mt-0.5 truncate max-w-lg">
                                    <span class="font-medium text-gray-400">To:</span> {{ $email['to'] }}
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-col items-end">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 mb-2">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $email['date'] }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Email Body Section -->
                <div class="p-0 bg-white">
                    <!-- 
                        Using an iframe with srcdoc to completely isolate the email's HTML/CSS 
                        from the application's Tailwind CSS. This prevents layout breaking.
                    -->
                    <iframe 
                        id="email-iframe"
                        srcdoc="{{ $email['body'] }}"
                        class="w-full border-none transition-all duration-300"
                        style="min-height: 600px; height: 100%;"
                        sandbox="allow-same-origin allow-popups allow-popups-to-escape-sandbox"
                        onload="resizeIframe(this)"
                    ></iframe>
                </div>
            </div>

        </div>
    </div>

    <!-- Script to auto-resize iframe based on its content -->
    <script>
        function resizeIframe(obj) {
            try {
                // Wait a tiny bit for images to load before resizing
                setTimeout(() => {
                    obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
                }, 100);
            } catch (e) {
                console.log('Could not auto-resize iframe due to cross-origin or content restrictions.');
            }
        }
    </script>
</x-app-layout>
