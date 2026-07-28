<aside id="sidebar"
    class="fixed lg:static z-40 inset-y-0 left-0 w-64 -translate-x-full lg:translate-x-0 transition-transform duration-300 bg-white dark:bg-ink-850 border-r border-ink-100 dark:border-ink-700 flex flex-col">
    <a href="{{ route('admin.dashboard') }}"
        class="h-16 flex items-center gap-2 px-5 border-b border-ink-100 dark:border-ink-700 shrink-0">
        <div
            class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-500 to-teal-400 flex items-center justify-center shadow-lg shadow-brand-500/30">
            <i class="fa-solid fa-bolt text-white text-sm"></i>
        </div>
        <span class="font-display font-bold text-lg tracking-tight">Auto<span class="text-brand-500">Present</span></span>
        <button id="closeSidebar" class="ml-auto lg:hidden text-ink-400 hover:text-brand-500">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </a>

    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-6 no-scrollbar">
        <div>
            <p class="px-3 text-[11px] font-semibold uppercase tracking-wider text-ink-400 mb-2">Main</p>
            <ul class="space-y-1">
                <li><a href="{{ route('admin.dashboard') }}"
                        class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl {{ Route::is('admin.dashboard') ? 'bg-brand-500/10 text-brand-600 dark:text-brand-300 font-medium' : 'text-ink-500 dark:text-ink-300 hover:bg-ink-50 dark:hover:bg-ink-700/50' }} text-sm"><i
                            class="fa-solid fa-grip w-5 text-center"></i>Dashboard</a></li>
                <li><a href="{{ route('students.index') }}"
                        class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl {{ Route::is('students.*') ? 'bg-brand-500/10 text-brand-600 dark:text-brand-300 font-medium' : 'text-ink-500 dark:text-ink-300 hover:bg-ink-50 dark:hover:bg-ink-700/50' }} text-sm"><i
                            class="fa-solid fa-users w-5 text-center"></i>Students</a></li>
                <li><a href="{{ route('subjects.index') }}"
                        class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl {{ Route::is('subjects.*') ? 'bg-brand-500/10 text-brand-600 dark:text-brand-300 font-medium' : 'text-ink-500 dark:text-ink-300 hover:bg-ink-50 dark:hover:bg-ink-700/50' }} text-sm"><i
                            class="fa-solid fa-book w-5 text-center"></i>Subjects</a></li>
                </ul>
        </div>

        <div>
            <p class="px-3 text-[11px] font-semibold uppercase tracking-wider text-ink-400 mb-2">Attendance</p>
            <ul class="space-y-1">
                <li><a href="{{ route('attendance-sessions.index') }}"
                        class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl {{ Route::is('attendance-sessions.*') ? 'bg-brand-500/10 text-brand-600 dark:text-brand-300 font-medium' : 'text-ink-500 dark:text-ink-300 hover:bg-ink-50 dark:hover:bg-ink-700/50' }} text-sm"><i
                            class="fa-solid fa-cookie w-5 text-center"></i>Sessions</a></li>
                <li><a href="{{ route('attendances.index') }}"
                        class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl {{ Route::is('attendances.*') ? 'bg-brand-500/10 text-brand-600 dark:text-brand-300 font-medium' : 'text-ink-500 dark:text-ink-300 hover:bg-ink-50 dark:hover:bg-ink-700/50' }} text-sm"><i
                            class="fa-solid fa-clipboard-list w-5 text-center"></i>Attendance</a></li>
            </ul>
        </div>

        <div>
            <p class="px-3 text-[11px] font-semibold uppercase tracking-wider text-ink-400 mb-2">Settings</p>
            <ul class="space-y-1">
            <li><a href="{{ route('registration-settings.index') }}"
                        class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl {{ Route::is('registration-settings.*') ? 'bg-brand-500/10 text-brand-600 dark:text-brand-300 font-medium' : 'text-ink-500 dark:text-ink-300 hover:bg-ink-50 dark:hover:bg-ink-700/50' }} text-sm"><i
                            class="fa-solid fa-cog w-5 text-center"></i>Settings</a></li>
            <li><a href="{{ route('admin.change-password') }}"
                        class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl {{ Route::is('admin.change-password') ? 'bg-brand-500/10 text-brand-600 dark:text-brand-300 font-medium' : 'text-ink-500 dark:text-ink-300 hover:bg-ink-50 dark:hover:bg-ink-700/50' }} text-sm"><i
                            class="fa-solid fa-key w-5 text-center"></i>Change Password</a></li>
            </ul>
        </div>
        
                <small class="text-xs text-center block text-gray-400">Your Public IP: {{request()->ip()}}</small>
    </nav>

</aside>
