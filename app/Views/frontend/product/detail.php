<?php require_once __DIR__ . '/../../layout/header.php'; ?>

<div class="bg-white min-h-screen pt-24 pb-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="flex text-sm text-gray-500 mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li><a href="<?= BASEURL; ?>" class="hover:text-black transition">Home</a></li>
                <li><span class="mx-2">/</span></li>
                <li><a href="<?= BASEURL; ?>/catalog" class="hover:text-black transition">Katalog</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-900 font-medium" aria-current="page"><?= htmlspecialchars($data['product']['name']); ?></li>
            </ol>
        </nav>

        <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 xl:gap-x-16">
            <!-- Product Images Gallery -->
            <div class="flex flex-col-reverse lg:flex-row gap-4">
                <!-- Thumbnails (Vertical on desktop) -->
                <div class="flex lg:flex-col gap-4 overflow-x-auto lg:overflow-visible w-full lg:w-24 flex-shrink-0 hidden sm:flex">
                    <?php if(!empty($data['images'])): foreach($data['images'] as $img): ?>
                        <button class="w-20 h-20 bg-gray-100 rounded-lg border-2 border-transparent hover:border-black focus:border-black overflow-hidden flex-shrink-0 transition-all">
                            <img src="<?= BASEURL; ?>/<?= $img['image_path']; ?>" alt="Thumbnail" class="w-full h-full object-cover object-center">
                        </button>
                    <?php endforeach; else: ?>
                        <div class="w-20 h-20 bg-gray-100 rounded-lg border-2 border-transparent border-black"></div>
                    <?php endif; ?>
                </div>
                
                <!-- Main Image -->
                <div class="w-full aspect-w-1 aspect-h-1 bg-gray-50 rounded-2xl overflow-hidden border border-gray-200 lg:aspect-none lg:h-[600px] flex items-center justify-center">
                    <?php if(!empty($data['images'])): ?>
                        <img src="<?= BASEURL; ?>/<?= $data['images'][0]['image_path']; ?>" alt="<?= htmlspecialchars($data['product']['name']); ?>" class="w-full h-full object-cover object-center sm:rounded-lg">
                    <?php else: ?>
                        <span class="text-gray-400">No Image Available</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Product Info -->
            <div class="mt-10 px-4 sm:px-0 lg:mt-0">
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2"><?= $data['product']['brand_name']; ?></p>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight sm:text-4xl mb-2"><?= htmlspecialchars($data['product']['name']); ?></h1>
                
                <!-- Rating Info -->
                <?php if ($data['rating_info']['review_count'] > 0): ?>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="flex items-center text-amber-400 text-sm">
                            <?php 
                            $rating = round($data['rating_info']['avg_rating']); 
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $rating) {
                                    echo '<i class="fas fa-star animate-pulse"></i>';
                                } else {
                                    echo '<i class="far fa-star text-gray-300"></i>';
                                }
                            }
                            ?>
                        </div>
                        <span class="text-sm font-medium text-gray-500">
                            <?= number_format($data['rating_info']['avg_rating'], 1); ?> (<?= $data['rating_info']['review_count']; ?> Ulasan)
                        </span>
                    </div>
                <?php endif; ?>

                <div class="mb-6" id="price-wrapper">
                    <?php 
                        $has_active_fs = false;
                        $fs_end = null;
                        $original_price = null;
                        $active_price = null;
                        
                        if(!empty($data['variants'])) {
                            // Find if any variant has an active flash sale
                            foreach ($data['variants'] as $v) {
                                if (!empty($v['is_flash_sale'])) {
                                    $has_active_fs = true;
                                    $fs_end = $v['flash_sale_end'];
                                    $original_price = $v['original_price'];
                                    $active_price = $v['price'];
                                    break;
                                }
                            }
                            if (!$has_active_fs) {
                                $active_price = min(array_column($data['variants'], 'price'));
                            }
                        }
                    ?>
                    
                    <?php if ($has_active_fs): ?>
                        <div class="space-y-2">
                            <div class="flex items-baseline gap-2.5">
                                <span class="text-3xl font-black text-red-600">
                                    Rp <?= number_format($active_price, 0, ',', '.'); ?>
                                </span>
                                <del class="text-lg text-gray-400" style="text-decoration: line-through;">
                                    Rp <?= number_format($original_price, 0, ',', '.'); ?>
                                </del>
                            </div>
                            <div class="inline-flex items-center gap-2 bg-red-50 text-red-600 rounded-full px-4 py-1.5 text-xs font-black border border-red-100">
                                <i class="fas fa-bolt animate-pulse text-amber-500"></i> FLASH SALE berakhir dalam: 
                                <span data-countdown="<?= $fs_end; ?>" class="font-mono text-sm">--:--:--</span>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-3xl font-bold text-gray-900">
                            <?= $active_price ? 'Rp ' . number_format($active_price, 0, ',', '.') : 'Harga Belum Tersedia'; ?>
                        </p>
                    <?php endif; ?>
                </div>

                <div class="mt-6 border-t border-gray-200 pt-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-4">Pilih Varian (Kapasitas / Warna)</h3>
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                        <?php if(!empty($data['variants'])): 
                            $is_first = true;
                            foreach($data['variants'] as $var): 
                        ?>
                        <label for="variant-<?= $var['id']; ?>" class="group relative border border-gray-300 rounded-lg py-3 px-4 flex items-center justify-center text-sm font-medium uppercase hover:bg-gray-50 focus:outline-none sm:flex-1 cursor-pointer transition-all">
                            <input type="radio" id="variant-<?= $var['id']; ?>" name="variant_id" value="<?= $var['id']; ?>" class="peer sr-only" <?= $is_first ? 'checked' : ''; ?>>
                            <span><?= htmlspecialchars($var['storage']); ?> - <?= htmlspecialchars($var['color']); ?></span>
                            <!-- Active ring -->
                            <span class="absolute -inset-px rounded-lg border-2 border-transparent pointer-events-none peer-checked:border-black" aria-hidden="true"></span>
                        </label>
                        <?php 
                            $is_first = false;
                            endforeach; 
                        else: ?>
                            <p class="text-sm text-gray-500">Tidak ada varian tersedia.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mt-10 flex flex-col sm:flex-row gap-4">
                    <button type="button" onclick="addToCart()" class="flex-1 bg-black dark:bg-white text-white dark:text-black border border-transparent rounded-full py-4 px-8 flex items-center justify-center text-base font-bold hover:bg-gray-800 dark:hover:bg-gray-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition-all shadow-sm">
                        Tambahkan ke Keranjang
                    </button>
                    <button type="button" onclick="openLiveChat(<?= $data['product']['id']; ?>)" class="flex-1 bg-primary hover:opacity-90 border border-transparent rounded-full py-4 px-8 flex items-center justify-center text-base font-bold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all shadow-sm">
                        <i class="fas fa-comments text-xl mr-2"></i> Tanya Penjual
                    </button>
                    <button type="button" class="py-4 px-4 rounded-full border border-gray-300 dark:border-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-red-500 hover:border-red-500 hover:bg-red-50 dark:hover:bg-red-950/20 transition-all">
                        <i class="far fa-heart text-xl"></i>
                    </button>
                </div>

                <div class="mt-10 border-t border-gray-200 pt-8">
                    <h2 class="text-lg font-medium text-gray-900">Deskripsi Produk</h2>
                    <div class="mt-4 prose prose-sm text-gray-600">
                        <?= nl2br(htmlspecialchars($data['product']['description'])); ?>
                    </div>
                </div>

                <!-- Customer Reviews -->
                <div class="mt-10 border-t border-gray-200 pt-8">
                    <h2 class="text-lg font-medium text-gray-900 mb-6">Ulasan Pembeli</h2>
                    
                    <?php if (!empty($data['reviews'])): ?>
                        <div class="space-y-6">
                            <?php foreach ($data['reviews'] as $rev): ?>
                                <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <p class="font-bold text-sm text-gray-900"><?= htmlspecialchars($rev['customer_name']); ?></p>
                                            <p class="text-xs text-gray-400"><?= date('d M Y, H:i', strtotime($rev['created_at'])); ?></p>
                                        </div>
                                        <div class="flex items-center text-amber-400 text-xs">
                                            <?php 
                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= $rev['rating']) {
                                                    echo '<i class="fas fa-star mr-0.5"></i>';
                                                } else {
                                                    echo '<i class="far fa-star text-gray-300 mr-0.5"></i>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed"><?= nl2br(htmlspecialchars($rev['comment'])); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-10 border border-dashed border-gray-200 rounded-2xl">
                            <i class="far fa-star text-3xl text-gray-300 mb-2 block"></i>
                            <p class="text-sm text-gray-500">Belum ada ulasan untuk produk ini.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function addToCart() {
        const selectedVariant = document.querySelector('input[name="variant_id"]:checked');
        if(!selectedVariant) {
            showToast('Mohon pilih varian terlebih dahulu!', 'warning');
            return;
        }
        
        // Simulasikan AJAX POST
        const variantId = selectedVariant.value;
        const formData = new FormData();
        formData.append('variant_id', variantId);
        formData.append('qty', 1);

        fetch('<?= BASEURL; ?>/cart/add', {
            method: 'POST',
            body: formData
        }).then(res => res.json())
          .then(data => {
              if(data.success) {
                  showToast('Berhasil ditambahkan ke keranjang!', 'success');
                  window.location.reload(); // Untuk update angka di navbar
              } else {
                  showToast('Gagal: ' + data.message, 'error');
              }
          }).catch(err => {
              console.error('Error adding to cart:', err);
              showToast('Terjadi kesalahan koneksi atau server.', 'error');
          });
    }

    // Live Chat Client Logic
    let chatInterval = null;
    let currentUserId = <?= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>;

    function openLiveChat(productId) {
        const widget = document.getElementById('liveChatWidget');
        if (!widget) return;
        
        widget.classList.remove('hidden');
        
        if (currentUserId) {
            document.getElementById('chatActiveArea').classList.remove('hidden');
            document.getElementById('chatAuthArea').classList.add('hidden');
            loadChatMessages();
            
            // Scroll to bottom on open
            setTimeout(() => {
                const chatContainer = document.getElementById('chatMessagesContainer');
                if (chatContainer) {
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                }
            }, 100);

            // Poll messages list every 3 seconds
            clearInterval(chatInterval);
            chatInterval = setInterval(loadChatMessages, 3000);
        } else {
            document.getElementById('chatActiveArea').classList.add('hidden');
            document.getElementById('chatAuthArea').classList.remove('hidden');
            switchAuthPanel('login');
        }
    }

    function closeLiveChat() {
        const widget = document.getElementById('liveChatWidget');
        if (widget) {
            widget.classList.add('hidden');
        }
        clearInterval(chatInterval);
    }

    function switchAuthPanel(mode) {
        const loginPanel = document.getElementById('authLoginPanel');
        const registerPanel = document.getElementById('authRegisterPanel');
        if (!loginPanel || !registerPanel) return;

        if (mode === 'login') {
            loginPanel.classList.remove('hidden');
            registerPanel.classList.add('hidden');
        } else {
            loginPanel.classList.add('hidden');
            registerPanel.classList.remove('hidden');
        }
    }

    function loadChatMessages() {
        if (!currentUserId || document.visibilityState === 'hidden') return;
        fetch('<?= BASEURL; ?>/chat/get_messages')
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    renderChatMessages(data.messages);
                }
            })
            .catch(err => console.error('Error fetching chat messages:', err));
    }

    function renderChatMessages(messages) {
        const chatContainer = document.getElementById('chatMessagesContainer');
        if (!chatContainer) return;

        if (!messages || messages.length === 0) {
            chatContainer.innerHTML = `<p class="text-xs text-gray-400 text-center py-12">Mulai percakapan dengan admin toko di sini...</p>`;
            return;
        }

        let html = '';
        messages.forEach(msg => {
            const isMe = msg.is_admin == 0;
            const bubbleBg = isMe 
                ? 'bg-black text-white dark:bg-white dark:text-black rounded-br-none' 
                : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200 rounded-bl-none';
            const align = isMe ? 'justify-end' : 'justify-start';
            const dateStr = new Date(msg.created_at).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'});
            
            let productPreview = '';
            if (msg.product_name) {
                productPreview = `
                    <div class="text-[9px] border-b pb-1 mb-1.5 opacity-85 flex items-center gap-1.5 ${isMe ? 'border-white/20' : 'border-gray-200 dark:border-gray-700'}">
                        <i class="fas fa-shopping-bag"></i> ${msg.product_name}
                    </div>
                `;
            }

            html += `
                <div class="flex ${align}">
                    <div class="max-w-[80%] rounded-2xl px-3.5 py-2.5 text-xs shadow-sm ${bubbleBg}">
                        ${productPreview}
                        <p class="leading-relaxed break-words">${escapeHtml(msg.message)}</p>
                        <p class="text-[9px] text-right mt-1 opacity-60">${dateStr}</p>
                    </div>
                </div>
            `;
        });

        const isScrolledToBottom = chatContainer.scrollHeight - chatContainer.clientHeight <= chatContainer.scrollTop + 60;

        chatContainer.innerHTML = html;

        if (isScrolledToBottom || chatContainer.innerHTML.includes('Mulai percakapan')) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
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

    // Auto-initialize chat on page load and register form handler
    document.addEventListener("DOMContentLoaded", function() {
        const chatContainer = document.getElementById('chatMessagesContainer');
        const chatForm = document.getElementById('chatSendForm');
        const chatInput = document.getElementById('chatMessageInput');

        const loginForm = document.getElementById('chatLoginForm');
        const registerForm = document.getElementById('chatRegisterForm');

        // Handle inline AJAX Login
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const email = document.getElementById('chatLoginEmail').value.trim();
                const password = document.getElementById('chatLoginPassword').value;

                if (!email || !password) return;

                fetch('<?= BASEURL; ?>/auth/login_ajax', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ email: email, password: password })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast('Berhasil masuk! Melanjutkan ke chat...', 'success');
                        currentUserId = data.user.id;
                        
                        // Switch panel and start chatting
                        document.getElementById('chatAuthArea').classList.add('hidden');
                        document.getElementById('chatActiveArea').classList.remove('hidden');
                        
                        loadChatMessages();
                        clearInterval(chatInterval);
                        chatInterval = setInterval(loadChatMessages, 3000);
                    } else {
                        showToast(data.message || 'Login gagal.', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    showToast('Terjadi kesalahan koneksi.', 'error');
                });
            });
        }

        // Handle inline AJAX Register
        if (registerForm) {
            registerForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const username = document.getElementById('chatRegisterUsername').value.trim();
                const email = document.getElementById('chatRegisterEmail').value.trim();
                const password = document.getElementById('chatRegisterPassword').value;

                if (!username || !email || !password) return;

                fetch('<?= BASEURL; ?>/auth/register_ajax', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username: username, email: email, password: password })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast('Pendaftaran berhasil! Melanjutkan ke chat...', 'success');
                        currentUserId = data.user.id;
                        
                        // Switch panel and start chatting
                        document.getElementById('chatAuthArea').classList.add('hidden');
                        document.getElementById('chatActiveArea').classList.remove('hidden');
                        
                        loadChatMessages();
                        clearInterval(chatInterval);
                        chatInterval = setInterval(loadChatMessages, 3000);
                    } else {
                        showToast(data.message || 'Registrasi gagal.', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    showToast('Terjadi kesalahan koneksi.', 'error');
                });
            });
        }

        if (chatForm) {
            chatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const message = chatInput.value.trim();
                const productId = document.getElementById('chatProductId').value;

                if (!message) return;

                // Optimistic render
                const now = new Date();
                const dateStr = now.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'});
                const tempHtml = `
                    <div class="flex justify-end">
                        <div class="max-w-[80%] rounded-2xl px-3.5 py-2.5 text-xs bg-black text-white dark:bg-white dark:text-black rounded-br-none shadow-sm opacity-60">
                            <p class="leading-relaxed break-words">${escapeHtml(message)}</p>
                            <p class="text-[9px] text-right mt-1 opacity-60">${dateStr} (mengirim...)</p>
                        </div>
                    </div>
                `;
                
                if (chatContainer.querySelector('.py-12')) {
                    chatContainer.innerHTML = tempHtml;
                } else {
                    chatContainer.innerHTML += tempHtml;
                }
                chatContainer.scrollTop = chatContainer.scrollHeight;
                chatInput.value = '';

                fetch('<?= BASEURL; ?>/chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        message: message,
                        product_id: productId ? parseInt(productId) : null
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        loadChatMessages();
                    } else {
                        showToast('Gagal mengirim: ' + data.message, 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    showToast('Kesalahan jaringan.', 'error');
                });
            });
        }

        if (currentUserId) {
            loadChatMessages();
            // Scroll to bottom
            setTimeout(() => {
                if (chatContainer) {
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                }
            }, 300);
            // Start polling
            clearInterval(chatInterval);
            chatInterval = setInterval(loadChatMessages, 3000);
        }
    });

    // Flash Sale Countdown Script
    document.addEventListener("DOMContentLoaded", function() {
        function updateCountdowns() {
            const now = new Date().getTime();
            document.querySelectorAll("[data-countdown]").forEach(el => {
                const endTimeStr = el.getAttribute("data-countdown");
                const endTime = new Date(endTimeStr.replace(/-/g, "/")).getTime();
                const distance = endTime - now;
                
                if (distance < 0) {
                    el.parentElement.style.display = 'none';
                    // Optional: reload page to refresh regular pricing
                    setTimeout(() => { window.location.reload(); }, 1000);
                    return;
                }
                
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                el.innerHTML = `${String(hours).padStart(2, '0')}j : ${String(minutes).padStart(2, '0')}m : ${String(seconds).padStart(2, '0')}d`;
            });
        }
        
        updateCountdowns();
        setInterval(updateCountdowns, 1000);
    });

    // Dynamic price updating on variant selection
    const productVariants = <?= json_encode($data['variants']); ?>;
    document.addEventListener("DOMContentLoaded", function() {
        const radios = document.querySelectorAll('input[name="variant_id"]');
        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                const variantId = this.value;
                const selected = productVariants.find(v => v.id == variantId);
                if (selected) {
                    const priceWrapper = document.getElementById('price-wrapper');
                    if (selected.is_flash_sale) {
                        const activePriceFormatted = new Intl.NumberFormat('id-ID').format(selected.price);
                        const originalPriceFormatted = new Intl.NumberFormat('id-ID').format(selected.original_price);
                        priceWrapper.innerHTML = `
                            <div class="space-y-2">
                                <div class="flex items-baseline gap-2.5">
                                    <span class="text-3xl font-black text-red-600">
                                        Rp ${activePriceFormatted}
                                    </span>
                                    <del class="text-lg text-gray-400" style="text-decoration: line-through;">
                                        Rp ${originalPriceFormatted}
                                    </del>
                                </div>
                                <div class="inline-flex items-center gap-2 bg-red-50 text-red-600 rounded-full px-4 py-1.5 text-xs font-black border border-red-100">
                                    <i class="fas fa-bolt animate-pulse text-amber-500"></i> FLASH SALE berakhir dalam: 
                                    <span data-countdown="${selected.flash_sale_end}" class="font-mono text-sm">--:--:--</span>
                                </div>
                            </div>
                        `;
                    } else {
                        const priceFormatted = new Intl.NumberFormat('id-ID').format(selected.price);
                        priceWrapper.innerHTML = `
                            <p class="text-3xl font-bold text-gray-900">
                                Rp ${priceFormatted}
                            </p>
                        `;
                    }
                }
            });
        });
    });
</script>

<!-- Live Chat Widget Overlay -->
<div id="liveChatWidget" class="fixed bottom-20 left-4 right-4 sm:left-auto sm:right-6 sm:w-[380px] bg-white dark:bg-darkcard rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-gray-200 dark:border-gray-800 z-[9990] flex flex-col hidden overflow-hidden transition-all duration-300 max-h-[480px] h-[480px]">
    <!-- Header -->
    <div class="bg-black dark:bg-white text-white dark:text-black px-5 py-4 flex items-center justify-between border-b border-gray-100 dark:border-gray-800">
        <div class="flex items-center gap-3">
            <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse"></span>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider">Live Chat Penjual</p>
                <p class="text-[9px] opacity-75">Biasanya membalas dalam beberapa menit</p>
            </div>
        </div>
        <button onclick="closeLiveChat()" class="text-white/80 dark:text-black/80 hover:text-white dark:hover:text-black transition">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>

    <!-- Product Reference Bar -->
    <div class="bg-gray-50 dark:bg-gray-800/50 px-4 py-2.5 border-b border-gray-100 dark:border-gray-800 flex items-center gap-3">
        <?php if (!empty($data['images'])): ?>
            <img src="<?= BASEURL; ?>/<?= $data['images'][0]['image_path']; ?>" alt="Product" class="w-10 h-10 object-cover rounded-lg border border-gray-200 dark:border-gray-700">
        <?php endif; ?>
        <div class="flex-1 min-w-0">
            <p class="text-[9px] text-gray-400 dark:text-gray-500 font-bold uppercase">Menanyakan tentang:</p>
            <p class="text-xs font-bold text-gray-900 dark:text-gray-200 truncate"><?= htmlspecialchars($data['product']['name']); ?></p>
        </div>
    </div>

    <!-- Chat Active Area -->
    <div id="chatActiveArea" class="flex-1 flex flex-col overflow-hidden <?= isset($_SESSION['user_id']) ? '' : 'hidden'; ?>">
        <!-- Messages Container -->
        <div id="chatMessagesContainer" class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50/20 dark:bg-darkbg/10 flex flex-col">
            <p class="text-xs text-gray-400 text-center py-12">Memuat percakapan...</p>
        </div>

        <!-- Input Box -->
        <form id="chatSendForm" class="p-3 border-t border-gray-200 dark:border-gray-800 flex gap-2 bg-white dark:bg-darkcard">
            <input type="hidden" id="chatProductId" value="<?= $data['product']['id']; ?>">
            <input type="text" id="chatMessageInput" class="flex-1 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-xs rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white transition" placeholder="Tulis pesan Anda..." required autocomplete="off">
            <button type="submit" class="bg-black dark:bg-white text-white dark:text-black w-9 h-9 rounded-xl flex items-center justify-center hover:scale-105 transition-all">
                <i class="fas fa-paper-plane text-xs"></i>
            </button>
        </form>
    </div>

    <!-- Guest Authentication Area -->
    <div id="chatAuthArea" class="flex-1 flex flex-col overflow-y-auto p-5 bg-gray-50 dark:bg-darkbg <?= isset($_SESSION['user_id']) ? 'hidden' : ''; ?>">
        <!-- Login Sub-Panel -->
        <div id="authLoginPanel" class="space-y-4 my-auto">
            <div class="text-center">
                <i class="fas fa-user-lock text-3xl text-primary mb-2"></i>
                <h4 class="text-xs font-bold text-gray-900 dark:text-gray-200 uppercase tracking-wide">Silakan Masuk</h4>
                <p class="text-[10px] text-gray-500 mt-1">Masuk untuk dapat berkonsultasi langsung dengan penjual</p>
            </div>
            
            <form id="chatLoginForm" class="space-y-3">
                <div>
                    <label class="block text-[9px] font-bold uppercase tracking-wider text-gray-500 mb-1">Email</label>
                    <input type="email" id="chatLoginEmail" required class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-xs rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary transition" placeholder="email@contoh.com">
                </div>
                <div>
                    <label class="block text-[9px] font-bold uppercase tracking-wider text-gray-500 mb-1">Password</label>
                    <input type="password" id="chatLoginPassword" required class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-xs rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary transition" placeholder="••••••••">
                </div>
                <button type="submit" class="w-full bg-black dark:bg-white text-white dark:text-black font-bold text-xs py-3 rounded-xl hover:opacity-90 transition flex items-center justify-center gap-2 mt-3 shadow-md active:scale-95">
                    <span>Masuk & Lanjutkan</span>
                    <i class="fas fa-sign-in-alt text-[10px]"></i>
                </button>
            </form>
            
            <p class="text-[10px] text-center text-gray-500 mt-4">
                Belum punya akun? 
                <button type="button" onclick="switchAuthPanel('register')" class="text-primary font-bold hover:underline">Daftar Sekarang</button>
            </p>
        </div>

        <!-- Register Sub-Panel -->
        <div id="authRegisterPanel" class="space-y-4 my-auto hidden">
            <div class="text-center">
                <i class="fas fa-user-plus text-3xl text-primary mb-2"></i>
                <h4 class="text-xs font-bold text-gray-900 dark:text-gray-200 uppercase tracking-wide">Daftar Akun Baru</h4>
                <p class="text-[10px] text-gray-500 mt-1">Buat akun dengan cepat untuk mulai chatting</p>
            </div>
            
            <form id="chatRegisterForm" class="space-y-3">
                <div>
                    <label class="block text-[9px] font-bold uppercase tracking-wider text-gray-500 mb-1">Username</label>
                    <input type="text" id="chatRegisterUsername" required class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-xs rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary transition" placeholder="username">
                </div>
                <div>
                    <label class="block text-[9px] font-bold uppercase tracking-wider text-gray-500 mb-1">Email</label>
                    <input type="email" id="chatRegisterEmail" required class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-xs rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary transition" placeholder="email@contoh.com">
                </div>
                <div>
                    <label class="block text-[9px] font-bold uppercase tracking-wider text-gray-500 mb-1">Password</label>
                    <input type="password" id="chatRegisterPassword" required class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-xs rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary transition" placeholder="••••••••">
                </div>
                <button type="submit" class="w-full bg-black dark:bg-white text-white dark:text-black font-bold text-xs py-3 rounded-xl hover:opacity-90 transition flex items-center justify-center gap-2 mt-3 shadow-md active:scale-95">
                    <span>Daftar & Lanjutkan</span>
                    <i class="fas fa-user-check text-[10px]"></i>
                </button>
            </form>
            
            <p class="text-[10px] text-center text-gray-500 mt-4">
                Sudah punya akun? 
                <button type="button" onclick="switchAuthPanel('login')" class="text-primary font-bold hover:underline">Masuk</button>
            </p>
        </div>
    </div>
</div>


<!-- Floating Live Chat Button (Mobile & Desktop) -->
<div class="fixed bottom-20 right-6 z-40 md:bottom-6">
    <button type="button" onclick="openLiveChat(<?= $data['product']['id']; ?>)" class="flex items-center gap-2 bg-primary hover:opacity-90 text-white font-bold px-4 py-3 rounded-full shadow-[0_8px_30px_rgba(27,96,204,0.3)] hover:shadow-[0_8px_30px_rgba(27,96,204,0.5)] transition-all duration-350 hover:-translate-y-1 text-sm group">
        <i class="fas fa-comments text-xl"></i>
        <span class="max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-500 ease-in-out whitespace-nowrap">Chat Penjual</span>
    </button>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
