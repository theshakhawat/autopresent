<!-- Custom SweetAlert Overlay & Card Container -->
<script>
    let alertRedirectUrl = null;
</script>

<div id="custom-alert-backdrop"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300">

    <div id="custom-alert-card"
        class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-2xl max-w-sm w-full text-center transform scale-95 opacity-0 transition-all duration-300">

        <!-- Icon Container -->
        <div id="alert-icon-wrapper"
            class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
            <i id="alert-icon" class="fa-solid"></i>
        </div>

        <!-- Title & Message -->
        <h3 id="alert-title" class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-2"></h3>
        <p id="alert-message" class="text-sm text-slate-600 dark:text-slate-400 mb-6 leading-relaxed"></p>

        <!-- Action Button -->
        <button onclick="closeCustomAlert()"
            class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium rounded-xl transition shadow-lg shadow-indigo-500/20 active:scale-95">
            OK
        </button>
    </div>
</div>

<script>
    function showAlert(title, message, type = 'success', url = null) {

        alertRedirectUrl = url;

        const backdrop = document.getElementById('custom-alert-backdrop');
        const card = document.getElementById('custom-alert-card');
        const iconWrapper = document.getElementById('alert-icon-wrapper');
        const icon = document.getElementById('alert-icon');
        const titleEl = document.getElementById('alert-title');
        const messageEl = document.getElementById('alert-message');

        // Set Text Content
        titleEl.textContent = title;
        messageEl.textContent = message;

        // Type Configurations (Explicit light & dark colors)
        const config = {
            success: {
                wrapper: 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800',
                icon: 'fa-circle-check'
            },
            error: {
                wrapper: 'bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800',
                icon: 'fa-circle-xmark'
            },
            warning: {
                wrapper: 'bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800',
                icon: 'fa-triangle-exclamation'
            },
            info: {
                wrapper: 'bg-sky-100 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 border border-sky-200 dark:border-sky-800',
                icon: 'fa-circle-info'
            }
        };

        const currentConfig = config[type] || config.success;

        // Reset and Apply Icon Classes
        iconWrapper.className =
            `w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl ${currentConfig.wrapper}`;
        icon.className = `fa-solid ${currentConfig.icon}`;

        // Show Overlay & Trigger Animation
        backdrop.classList.remove('pointer-events-none', 'opacity-0');
        backdrop.classList.add('opacity-100');

        setTimeout(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeCustomAlert() {
        const backdrop = document.getElementById('custom-alert-backdrop');
        const card = document.getElementById('custom-alert-card');

        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            backdrop.classList.remove('opacity-100');
            backdrop.classList.add('opacity-0', 'pointer-events-none');

            // Redirect if URL exists
            if (alertRedirectUrl) {
                window.location.href = alertRedirectUrl;
            }

            // Reset
            alertRedirectUrl = null;

        }, 150);



    }
</script>
