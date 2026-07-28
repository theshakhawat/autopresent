    <header class="h-16 shrink-0 flex items-center gap-3 px-4 sm:px-6 border-b border-ink-100 dark:border-ink-700 bg-white/80 dark:bg-ink-850/80 backdrop-blur sticky top-0 z-20">
      <button id="openSidebar" class="lg:hidden text-ink-500 dark:text-ink-300 hover:text-brand-500">
        <i class="fa-solid fa-bars text-lg"></i>
      </button>

      <div class="relative hidden sm:block w-full max-w-xs">
        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-ink-300 text-sm"></i>
        <input type="text" placeholder="Search anything..." class="w-full bg-ink-50 dark:bg-ink-800 border border-transparent focus:border-brand-400 focus:bg-white dark:focus:bg-ink-900 rounded-xl pl-9 pr-3 py-2 text-sm outline-none transition placeholder:text-ink-300">
      </div>

      <div class="ml-auto flex items-center gap-1.5 sm:gap-3">
        <button class="sm:hidden w-9 h-9 rounded-lg hover:bg-ink-50 dark:hover:bg-ink-700/50 flex items-center justify-center text-ink-500 dark:text-ink-300">
          <i class="fa-solid fa-magnifying-glass"></i>
        </button>

        <a href='{{ route('home') }}' target="_blank" class="w-9 h-9 rounded-lg hover:bg-ink-50 dark:hover:bg-red-700/50 flex items-center justify-center text-green-500 dark:text-blue-300 relative">
          <i class="fa-solid fa-home text-amber-400 dark:block"></i>
        </a>

        <button id="themeToggle" class="w-9 h-9 rounded-lg hover:bg-ink-50 dark:hover:bg-ink-700/50 flex items-center justify-center text-ink-500 dark:text-ink-300 relative">
          <i class="fa-solid fa-sun text-amber-400 hidden dark:block"></i>
          <i class="fa-solid fa-moon block dark:hidden"></i>
        </button>

        <div class="w-px h-6 bg-ink-100 dark:bg-ink-700 mx-1 hidden sm:block"></div>

        <div class="relative">
          <button id="profileBtn" class="flex items-center gap-2 pl-1 pr-1 sm:pr-2 py-1 rounded-xl hover:bg-ink-50 dark:hover:bg-ink-700/50">
            <img src="{{ Auth::user()->photo_url }}" class="w-8 h-8 rounded-lg object-cover" alt="avatar">
            <span class="hidden sm:block text-sm font-medium text-left leading-tight">{{ Auth::user()->name }}<br><span class="text-[11px] font-normal text-ink-400">Admin</span></span>
            <i class="fa-solid fa-chevron-down text-[10px] text-ink-400 hidden sm:block"></i>
          </button>
          <div id="profileMenu" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-ink-800 border border-ink-100 dark:border-ink-700 rounded-xl shadow-xl overflow-hidden py-1 fade-in">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-ink-600 dark:text-ink-200 hover:bg-ink-50 dark:hover:bg-ink-700"><i class="fa-solid fa-user w-4"></i>My Profile</a>
            <a href="{{ route('registration-settings.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-ink-600 dark:text-ink-200 hover:bg-ink-50 dark:hover:bg-ink-700"><i class="fa-solid fa-gear w-4"></i>Settings</a>
            <div class="h-px bg-ink-100 dark:bg-ink-700 my-1"></div>
            <a href="{{ route('admin.logout') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10"><i class="fa-solid fa-right-from-bracket w-4"></i>Log Out</a>
          </div>
        </div>
      </div>
    </header>
