<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script>
  tailwind.config = {
    darkMode: 'class',
    theme: {
      extend: {
        fontFamily: { body: ['Inter', 'sans-serif'] },
        colors: {
          brand: { 400:'#8b7bff',500:'#6d5dfc',600:'#5a47e6' },
          ink: { 50:'#f6f7f9',100:'#eceef2',300:'#aab2c0',400:'#7c8698',500:'#565f72',700:'#262b38',800:'#171a21',850:'#12141a',900:'#0f1115' }
        }
      }
    }
  }
</script>
<style>
  body { font-family: 'Inter', sans-serif; }
  .shake { animation: shake 0.4s ease; }
  @keyframes shake {
    0%,100% { transform: translateX(0); }
    20%,60% { transform: translateX(-6px); }
    40%,80% { transform: translateX(6px); }
  }
  .fade-in { animation: fadeIn 0.3s ease both; }
  @keyframes fadeIn { from { opacity:0; transform: translateY(-4px);} to { opacity:1; transform: translateY(0);} }
</style>
</head>
<body class="bg-ink-50 dark:bg-ink-900 text-ink-700 dark:text-ink-100 min-h-screen flex items-center justify-center p-5 transition-colors duration-300">

  <button id="themeToggle" class="fixed top-5 right-5 w-9 h-9 rounded-lg hover:bg-ink-100 dark:hover:bg-ink-800 flex items-center justify-center text-ink-500 dark:text-ink-300">
    <i class="fa-solid fa-sun text-amber-400 hidden dark:block"></i>
    <i class="fa-solid fa-moon block dark:hidden"></i>
  </button>

  <div class="w-full max-w-sm">
    <div class="text-center mb-6">
      <div class="w-11 h-11 mx-auto rounded-xl bg-brand-500 flex items-center justify-center mb-3 shadow-lg shadow-brand-500/25">
        <i class="fa-solid fa-lock text-white"></i>
      </div>
      <h1 class="text-xl font-semibold">Sign in to your account</h1>
      <p class="text-sm text-ink-400 mt-1">Enter your details below to continue</p>
    </div>

    <div id="card" class="bg-white dark:bg-ink-850 border border-ink-100 dark:border-ink-700 rounded-2xl p-6 shadow-sm">

      @if ($errors->any())

      <div id="errorBox" class="items-start gap-2.5 bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-500/20 text-red-600 dark:text-red-400 text-sm rounded-xl px-4 py-3 mb-5 fade-in">
        @foreach ($errors->all() as $error)
            <div><i class="fa-solid fa-circle-exclamation mt-0.5"></i>
            <span id="errorText">{{ $error }}</span></div>
        @endforeach
      </div>
      @endif



      <form id="loginForm" class="space-y-4" method="POST" action="{{ route('loginPost') }}">
        @csrf
        <div>
          <label class="text-xs font-medium text-ink-500 dark:text-ink-300 mb-1.5 block">Email</label>
          <input type="email" id="email" name="email" required placeholder="you@example.com" value="{{ old('email') }}"
            class="w-full bg-ink-50 dark:bg-ink-800 border border-ink-100 dark:border-ink-700 focus:border-brand-400 focus:ring-4 focus:ring-brand-500/10 rounded-xl px-4 py-2.5 text-sm outline-none transition placeholder:text-ink-300">
        </div>

        <div>
          <label class="text-xs font-medium text-ink-500 dark:text-ink-300 mb-1.5 block">Password</label>
          <div class="relative">
            <input type="password" id="password" name="password" required placeholder="********"
              class="w-full bg-ink-50 dark:bg-ink-800 border border-ink-100 dark:border-ink-700 focus:border-brand-400 focus:ring-4 focus:ring-brand-500/10 rounded-xl px-4 py-2.5 pr-10 text-sm outline-none transition placeholder:text-ink-300">
            <button type="button" id="togglePass" class="absolute right-3 top-1/2 -translate-y-1/2 text-ink-300 hover:text-brand-500 text-sm">
              <i class="fa-solid fa-eye" id="eyeIcon"></i>
            </button>
          </div>
        </div>

        <button type="submit" id="submitBtn" class="w-full bg-brand-500 hover:bg-brand-600 text-white font-medium text-sm py-2.5 rounded-xl transition flex items-center justify-center gap-2">
          Sign In
        </button>
      </form>

    </div>
  </div>

<script>
  const root = document.documentElement;
  if (window.matchMedia('(prefers-color-scheme: dark)').matches) root.classList.add('dark');
  document.getElementById('themeToggle').addEventListener('click', () => root.classList.toggle('dark'));

  const passInput = document.getElementById('password');
  const eyeIcon = document.getElementById('eyeIcon');
  document.getElementById('togglePass').addEventListener('click', () => {
    const isPass = passInput.type === 'password';
    passInput.type = isPass ? 'text' : 'password';
    eyeIcon.classList.toggle('fa-eye');
    eyeIcon.classList.toggle('fa-eye-slash');
  });

  const form = document.getElementById('loginForm');
  const errorBox = document.getElementById('errorBox');
  const errorText = document.getElementById('errorText');
  const card = document.getElementById('card');
  const submitBtn = document.getElementById('submitBtn');
  const emailInput = document.getElementById('email');

  function showError(msg) {
    errorText.textContent = msg;
    errorBox.classList.remove('hidden');
    errorBox.classList.add('flex');
    card.classList.remove('shake');
    void card.offsetWidth; // restart animation
    card.classList.add('shake');
  }

  function hideError() {
    errorBox.classList.add('hidden');
    errorBox.classList.remove('flex');
  }


</script>
</body>
</html>
