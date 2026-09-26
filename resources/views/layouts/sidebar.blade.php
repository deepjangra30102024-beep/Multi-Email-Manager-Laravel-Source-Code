<div class="hidden md:flex md:flex-shrink-0">
    <div class="flex flex-col w-64 bg-indigo-900 border-r border-gray-200">
        <div class="flex flex-col h-0 flex-1">
            <div class="flex items-center h-16 flex-shrink-0 px-4 bg-indigo-950">
                <span class="text-white text-lg font-bold tracking-wider">MultiEmail<span class="text-indigo-400">Pro</span></span>
            </div>
            <div class="flex-1 flex flex-col overflow-y-auto">
                <nav class="flex-1 px-3 py-6 space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-700' }} group flex items-center px-3 py-3 text-sm font-medium rounded-md transition-colors duration-150">
                        <svg class="{{ request()->routeIs('dashboard') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }} flex-shrink-0 -ml-1 mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        Dashboard
                    </a>

                    <!-- Inbox (Coming Soon) -->
                    <a href="#" class="text-indigo-100 hover:bg-indigo-700 group flex items-center px-3 py-3 text-sm font-medium rounded-md transition-colors duration-150">
                        <svg class="text-indigo-300 group-hover:text-white flex-shrink-0 -ml-1 mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Inbox
                    </a>

                    <!-- Templates -->
                    <a href="{{ route('templates.index') }}" class="{{ request()->routeIs('templates.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-700' }} group flex items-center px-3 py-3 text-sm font-medium rounded-md transition-colors duration-150">
                        <svg class="{{ request()->routeIs('templates.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }} flex-shrink-0 -ml-1 mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Templates
                    </a>

                    <!-- Accounts -->
                    <a href="#" class="text-indigo-100 hover:bg-indigo-700 group flex items-center px-3 py-3 text-sm font-medium rounded-md transition-colors duration-150">
                        <svg class="text-indigo-300 group-hover:text-white flex-shrink-0 -ml-1 mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Connected Accounts
                    </a>
                </nav>
            </div>
            
            <div class="flex-shrink-0 flex bg-indigo-950 p-4">
                <a href="{{ route('profile.edit') }}" class="flex-shrink-0 w-full group block">
                    <div class="flex items-center">
                        <div class="inline-flex items-center justify-center h-9 w-9 rounded-full bg-indigo-600">
                            <span class="text-sm font-medium leading-none text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-white group-hover:text-indigo-300 truncate">
                                {{ Auth::user()->name }}
                            </p>
                            <p class="text-xs font-medium text-indigo-300 group-hover:text-indigo-100 truncate">
                                View Profile
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
