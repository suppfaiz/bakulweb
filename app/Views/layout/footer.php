    <!-- Footer -->
    <footer class="bg-white dark:bg-darkcard border-t border-gray-200 dark:border-gray-800 mt-20 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Branding -->
                <div class="col-span-1 md:col-span-1">
                    <a href="<?= BASEURL; ?>" class="inline-block">
                        <img src="<?= BASEURL; ?>/images/logo.jpg" alt="BAKUL Logo" class="h-28 md:h-36 w-auto object-contain">
                    </a>
                    <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                        Platform e-commerce enterprise untuk gadget, smartphone, dan aksesoris terlengkap dan terpercaya.
                    </p>
                    <div class="mt-6 flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-primary transition"><i class="fab fa-facebook-f text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-primary transition"><i class="fab fa-instagram text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-primary transition"><i class="fab fa-twitter text-xl"></i></a>
                    </div>
                </div>
                
                <!-- Links -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Perusahaan</h3>
                    <ul class="mt-4 space-y-3 text-sm text-gray-500 dark:text-gray-400">
                        <li><a href="#" class="hover:text-primary transition">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-primary transition">Karir</a></li>
                        <li><a href="#" class="hover:text-primary transition">Blog</a></li>
                        <li><a href="#" class="hover:text-primary transition">Kontak</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Bantuan</h3>
                    <ul class="mt-4 space-y-3 text-sm text-gray-500 dark:text-gray-400">
                        <li><a href="<?= BASEURL; ?>/help" class="hover:text-primary transition">Pusat Bantuan</a></li>
                        <li><a href="<?= BASEURL; ?>/help/terms" class="hover:text-primary transition">Syarat & Ketentuan</a></li>
                        <li><a href="<?= BASEURL; ?>/help/privacy" class="hover:text-primary transition">Kebijakan Privasi</a></li>
                        <li><a href="<?= BASEURL; ?>/help/refund" class="hover:text-primary transition">Pengembalian Dana</a></li>
                    </ul>
                </div>
                
                <!-- Newsletter -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Newsletter</h3>
                    <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">Dapatkan update promo dan produk terbaru langsung ke inbox Anda.</p>
                    <form class="mt-4 flex">
                        <input type="email" placeholder="Email Anda" class="w-full min-w-0 px-4 py-2 text-sm text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-800 border border-transparent rounded-l-md focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                        <button type="submit" class="flex-shrink-0 px-4 py-2 border border-transparent text-sm font-medium rounded-r-md text-white bg-primary hover:bg-sky-600 transition">Subscribe</button>
                    </form>
                </div>
            </div>
            <div class="mt-12 border-t border-gray-200 dark:border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-base text-gray-400 xl:text-center">&copy; <?= date('Y'); ?> BAKUL Enterprise. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Force Light Theme Script -->
    <script>
        document.documentElement.classList.remove('dark');
        localStorage.theme = 'light';
        
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if(window.scrollY > 10) {
                nav.classList.add('shadow-md');
            } else {
                nav.classList.remove('shadow-md');
            }
        });

        // Register Service Worker for PWA (Standalone mode to hide browser tab)
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('<?= BASEURL; ?>/sw.js')
                    .then(reg => console.log('Service Worker registered successfully with scope:', reg.scope))
                    .catch(err => console.error('Service Worker registration failed:', err));
            });
        }

        // Notification Client (Dynamic Bell & Dropdown)
        <?php if (isset($_SESSION['user_id'])): ?>
        (function() {
            const notificationBtn = document.getElementById('notificationBtn');
            const notificationDropdown = document.getElementById('notificationDropdown');
            const notificationBadge = document.getElementById('notificationBadge');
            const notificationList = document.getElementById('notificationList');
            const markAllReadBtn = document.getElementById('markAllReadBtn');
            const BASEURL = '<?= BASEURL; ?>';

            if (notificationBtn && notificationDropdown) {
                function loadNotifications() {
                    fetch(`${BASEURL}/notification/get`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                const count = data.unread_count;
                                if (count > 0) {
                                    notificationBadge.innerText = count;
                                    notificationBadge.classList.remove('hidden');
                                } else {
                                    notificationBadge.classList.add('hidden');
                                }
                                
                                let html = '';
                                if (data.notifications.length === 0) {
                                    html = '<p class="text-xs text-gray-400 text-center py-6">Tidak ada notifikasi baru.</p>';
                                } else {
                                    data.notifications.forEach(notif => {
                                        const unreadBg = notif.is_read == 0 ? 'bg-gray-50/70 dark:bg-gray-800/30' : '';
                                        const dot = notif.is_read == 0 ? '<span class="h-2 w-2 rounded-full bg-black dark:bg-white flex-shrink-0 mt-1.5"></span>' : '';
                                        
                                        html += `
                                            <div class="p-3.5 flex items-start gap-2.5 transition hover:bg-gray-50 dark:hover:bg-gray-800/50 cursor-pointer ${unreadBg}" onclick="markNotifRead(${notif.id}, this, event)">
                                                ${dot}
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-xs font-bold text-gray-900 dark:text-white">${escapeHtml(notif.title)}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 leading-relaxed">${escapeHtml(notif.message)}</p>
                                                    <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">${new Date(notif.created_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit'})}</p>
                                                </div>
                                            </div>
                                        `;
                                    });
                                }
                                notificationList.innerHTML = html;
                            }
                        })
                        .catch(err => console.error('Error fetching notifications:', err));
                }

                window.markNotifRead = function(id, element, event) {
                    event.stopPropagation();
                    fetch(`${BASEURL}/notification/read`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: id })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            element.classList.remove('bg-gray-50/70', 'dark:bg-gray-800/30');
                            const dot = element.querySelector('span.bg-black, span.bg-white');
                            if (dot) dot.remove();
                            
                            let count = parseInt(notificationBadge.innerText) || 0;
                            count = Math.max(0, count - 1);
                            if (count > 0) {
                                notificationBadge.innerText = count;
                            } else {
                                notificationBadge.classList.add('hidden');
                            }
                            
                            if (element.innerText.toLowerCase().includes('invoice') || element.innerText.toLowerCase().includes('pesanan')) {
                                window.location.href = `${BASEURL}/account?tab=orders`;
                            }
                        }
                    })
                    .catch(err => console.error(err));
                };

                notificationBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const isHidden = notificationDropdown.classList.contains('hidden');
                    if (isHidden) {
                        loadNotifications();
                        notificationDropdown.classList.remove('hidden');
                        setTimeout(() => {
                            notificationDropdown.classList.remove('scale-95', 'opacity-0');
                            notificationDropdown.classList.add('scale-100', 'opacity-100');
                        }, 10);
                    } else {
                        hideDropdown();
                    }
                });

                function hideDropdown() {
                    notificationDropdown.classList.remove('scale-100', 'opacity-100');
                    notificationDropdown.classList.add('scale-95', 'opacity-0');
                    setTimeout(() => {
                        notificationDropdown.classList.add('hidden');
                    }, 200);
                }

                document.addEventListener('click', (e) => {
                    if (!notificationDropdown.contains(e.target) && !notificationBtn.contains(e.target)) {
                        hideDropdown();
                    }
                });

                markAllReadBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    fetch(`${BASEURL}/notification/read_all`, {
                        method: 'POST'
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            loadNotifications();
                        }
                    })
                    .catch(err => console.error(err));
                });

                loadNotifications();
                setInterval(loadNotifications, 15000); // Poll every 15 seconds
            }

            function escapeHtml(text) {
                if (!text) return '';
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return text.replace(/[&<>"']/g, function(m) { return map[m]; });
            }
        })();
        <?php endif; ?>
    </script>

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
