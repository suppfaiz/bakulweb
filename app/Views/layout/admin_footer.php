            </main>
        </div>
    </div>

    <!-- Toast Notification System -->
    <div id="toastContainer" class="fixed top-20 right-4 z-[9999] flex flex-col gap-3 pointer-events-none" style="max-width:380px;"></div>
    
    <style>
        @keyframes toastIn { 
            from { opacity:0; transform:translateX(100px) scale(0.95); } 
            to   { opacity:1; transform:translateX(0) scale(1); } 
        }
        @keyframes toastOut { 
            from { opacity:1; transform:translateX(0) scale(1); } 
            to   { opacity:0; transform:translateX(100px) scale(0.95); } 
        }
        .toast-enter { animation: toastIn 0.4s cubic-bezier(0.16,1,0.3,1) forwards; }
        .toast-exit  { animation: toastOut 0.3s cubic-bezier(0.16,1,0.3,1) forwards; }
        .toast-progress { animation: toastShrink linear forwards; }
        @keyframes toastShrink { from { width:100%; } to { width:0%; } }
    </style>

    <script>
    function showToast(message, type = 'success', duration = 4000) {
        const container = document.getElementById('toastContainer');
        if (!container) return;

        const config = {
            success: { icon: 'fa-check-circle',       bg: 'bg-white dark:bg-gray-900',  border: 'border-green-200 dark:border-green-800', iconColor: 'text-green-500', bar: 'bg-green-500' },
            error:   { icon: 'fa-times-circle',        bg: 'bg-white dark:bg-gray-900',  border: 'border-red-200 dark:border-red-800',     iconColor: 'text-red-500',   bar: 'bg-red-500' },
            warning: { icon: 'fa-exclamation-triangle', bg: 'bg-white dark:bg-gray-900', border: 'border-yellow-200 dark:border-yellow-800', iconColor: 'text-yellow-500', bar: 'bg-yellow-500' },
            info:    { icon: 'fa-info-circle',          bg: 'bg-white dark:bg-gray-900', border: 'border-blue-200 dark:border-blue-800',   iconColor: 'text-blue-500',  bar: 'bg-blue-500' },
        };
        const c = config[type] || config.success;

        const toast = document.createElement('div');
        toast.className = `pointer-events-auto ${c.bg} border ${c.border} rounded-2xl shadow-2xl overflow-hidden toast-enter`;
        toast.innerHTML = `
            <div class="flex items-start gap-3 px-4 pt-4 pb-3">
                <div class="flex-shrink-0 mt-0.5">
                    <i class="fas ${c.icon} ${c.iconColor} text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white leading-snug">${escapeToastHtml(message)}</p>
                </div>
                <button onclick="dismissToast(this.closest('.toast-enter, .toast-exit'))" class="flex-shrink-0 text-gray-300 hover:text-gray-600 dark:hover:text-gray-300 transition -mt-0.5 -mr-1">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
            <div class="h-1 w-full bg-gray-100 dark:bg-gray-800">
                <div class="h-full ${c.bar} toast-progress rounded-full" style="animation-duration:${duration}ms"></div>
            </div>
        `;

        container.appendChild(toast);

        // Auto-dismiss
        const timer = setTimeout(() => dismissToast(toast), duration);
        toast._timer = timer;
    }

    function dismissToast(el) {
        if (!el || el._dismissed) return;
        el._dismissed = true;
        if (el._timer) clearTimeout(el._timer);
        el.classList.remove('toast-enter');
        el.classList.add('toast-exit');
        setTimeout(() => el.remove(), 300);
    }

    function escapeToastHtml(text) {
        if (!text) return '';
        const d = document.createElement('div');
        d.textContent = text;
        return d.innerHTML;
    }

    // Override native alert() globally
    window._nativeAlert = window.alert;
    window.alert = function(msg) {
        showToast(msg, 'info');
    };
    </script>

    <?php Flasher::flash(); ?>
</body>
</html>
