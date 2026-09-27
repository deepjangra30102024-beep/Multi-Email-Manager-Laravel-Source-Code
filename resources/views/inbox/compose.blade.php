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
                <div class="bg-gray-50/50 border-b border-gray-100 p-6">
                    <h1 class="text-2xl font-bold text-gray-900 leading-tight">Compose Email</h1>
                    <p class="text-sm text-gray-500 mt-1">Sending from: <span class="font-medium text-gray-700">{{ $emailAccount->email_address }}</span></p>
                </div>
                
                <div class="p-6 bg-white">
                    <form action="{{ route('inbox.send', $emailAccount) }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="to" class="block text-sm font-medium text-gray-700">To</label>
                            <input type="email" name="to" id="to" value="{{ old('to') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="recipient@example.com">
                            @error('to')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                            <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Email subject">
                            @error('subject')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        @if($templates->isNotEmpty())
                        <div class="mb-4">
                            <label for="template" class="block text-sm font-medium text-gray-700">Insert Template (Optional)</label>
                            <select id="template" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" onchange="insertTemplate(this.value)">
                                <option value="">Select a template...</option>
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="mb-6">
                            <label for="body" class="block text-sm font-medium text-gray-700">Body</label>
                            <textarea name="body" id="body" rows="10" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Write your email here...">{{ old('body') }}</textarea>
                            @error('body')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Send Email
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @if($templates->isNotEmpty())
    <script>
        const templates = @json($templates->keyBy('id'));
        
        function insertTemplate(id) {
            if (!id) return;
            const template = templates[id];
            if (template) {
                const bodyField = document.getElementById('body');
                const subjectField = document.getElementById('subject');
                
                if (template.subject && !subjectField.value) {
                    subjectField.value = template.subject;
                }
                
                const currentText = bodyField.value;
                if (currentText) {
                    bodyField.value = currentText + '\n\n' + template.body;
                } else {
                    bodyField.value = template.body;
                }
            }
        }
    </script>
    @endif
</x-app-layout>
