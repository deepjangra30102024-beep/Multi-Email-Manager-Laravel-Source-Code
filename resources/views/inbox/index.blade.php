<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        Inbox: {{ $emailAccount->email_address }}
                    </h2>
                    <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm inline-block mt-1">
                        &larr; Back to Dashboard
                    </a>
                </div>

                <form method="GET" action="{{ route('inbox.index', $emailAccount) }}" class="flex">
                    <input type="text" name="q" value="{{ $query ?? '' }}" placeholder="Search emails..." class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-l-md shadow-sm sm:text-sm w-64">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-r-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Search
                    </button>
                    @if(isset($query) && $query !== '')
                        <a href="{{ route('inbox.index', $emailAccount) }}" class="inline-flex items-center ml-2 px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition ease-in-out duration-150">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(isset($emails) && count($emails) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($emails as $email)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ Str::limit($email['from'], 30) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                <div class="font-medium text-gray-900">{{ $email['subject'] }}</div>
                                                <div class="text-gray-500 text-xs mt-1">{{ Str::limit($email['snippet'], 80) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $email['date'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('inbox.show', ['emailAccount' => $emailAccount->id, 'messageId' => $email['id']]) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if(isset($nextPageToken) && $nextPageToken)
                            <div class="mt-6 flex justify-center">
                                <a href="{{ route('inbox.index', ['emailAccount' => $emailAccount->id, 'page_token' => $nextPageToken, 'q' => $query ?? '']) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                    Load More Emails &rarr;
                                </a>
                            </div>
                        @endif

                    @else
                        <div class="text-center py-12 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No emails found</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                @if(isset($query) && $query !== '')
                                    No emails match your search for "{{ $query }}".
                                @else
                                    We couldn't find any emails for this account.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
