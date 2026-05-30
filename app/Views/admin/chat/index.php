<?php require_once __DIR__ . '/../../layout/admin_header.php'; ?>

<div class="h-[calc(100vh-7rem)] flex bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
    <!-- Left Pane: Conversations List -->
    <div class="w-80 border-r border-gray-200 flex flex-col bg-gray-50/50">
        <div class="p-4 border-b border-gray-200 bg-white">
            <h2 class="text-lg font-bold text-gray-900">Pesan Pelanggan</h2>
            <p class="text-xs text-gray-500 mt-1">Kelola live chat dari pembeli</p>
        </div>
        
        <!-- Search threads -->
        <div class="p-3 border-b border-gray-200 bg-white">
            <div class="relative">
                <input type="text" id="threadSearch" onkeyup="filterThreads()" placeholder="Cari pelanggan..." class="w-full bg-gray-50 border border-gray-200 text-xs rounded-xl pl-8 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition-all">
                <div class="absolute left-3 top-2.5 text-gray-400">
                    <i class="fas fa-search text-xs"></i>
                </div>
            </div>
        </div>

        <!-- Threads Container -->
        <div id="threadsContainer" class="flex-1 overflow-y-auto divide-y divide-gray-100">
            <div class="p-6 text-center text-gray-400 text-xs">
                <i class="fas fa-comments text-2xl mb-2 block text-gray-300 animate-pulse"></i>
                Memuat percakapan...
            </div>
        </div>
    </div>

    <!-- Right Pane: Active Chat Window -->
    <div class="flex-1 flex flex-col bg-white" id="chatWindowPlaceholder">
        <div class="flex-1 flex flex-col items-center justify-center text-gray-400 p-8">
            <i class="fas fa-comments text-5xl mb-4 text-gray-200"></i>
            <h3 class="text-base font-bold text-gray-800">Pilih Percakapan</h3>
            <p class="text-xs text-gray-500 mt-1 text-center max-w-xs">Pilih salah satu pelanggan di panel kiri untuk mulai membalas pesan.</p>
        </div>
    </div>

    <!-- Active Chat Box (Hidden by default, shown when thread selected) -->
    <div class="flex-1 flex flex-col bg-white hidden" id="activeChatWindow">
        <!-- Header -->
        <div class="p-4 border-b border-gray-200 flex items-center justify-between bg-white">
            <div>
                <h3 class="font-bold text-gray-900" id="activeCustomerName">Pelanggan</h3>
                <p class="text-[10px] text-gray-500 font-semibold" id="activeCustomerEmail">email@example.com</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse"></span>
                <span class="text-xs text-gray-500 font-bold uppercase tracking-wider">Aktif</span>
            </div>
        </div>

        <!-- Messages Container -->
        <div id="adminChatMessages" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50/50">
            <!-- Messages load here -->
        </div>

        <!-- Input Box -->
        <form id="adminChatSendForm" class="p-4 border-t border-gray-200 bg-white flex gap-3">
            <input type="hidden" id="activeUserId">
            <input type="hidden" id="activeProductId">
            <input type="text" id="adminChatMessageInput" placeholder="Tulis balasan Anda..." class="flex-1 bg-gray-50 border border-gray-200 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition-all" required autocomplete="off">
            <button type="submit" class="bg-black hover:bg-gray-800 text-white font-bold px-6 py-3 rounded-xl flex items-center justify-center transition-all shadow-sm">
                Kirim <i class="fas fa-paper-plane ml-2 text-xs"></i>
            </button>
        </form>
    </div>
</div>

<script>
    const BASEURL = '<?= BASEURL; ?>';
    let currentSelectedUserId = null;
    let threadsInterval = null;
    let messagesInterval = null;

    // Load active threads list
    function loadThreads() {
        fetch(`${BASEURL}/chat/admin_get_threads`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    renderThreads(data.threads);
                }
            })
            .catch(err => console.error('Error loading threads:', err));
    }

    function renderThreads(threads) {
        const container = document.getElementById('threadsContainer');
        if (!threads || threads.length === 0) {
            container.innerHTML = `
                <div class="p-6 text-center text-gray-400 text-xs">
                    <i class="fas fa-inbox text-2xl mb-2 block text-gray-300"></i>
                    Belum ada percakapan masuk.
                </div>`;
            return;
        }

        let html = '';
        threads.forEach(t => {
            const isSelected = t.user_id == currentSelectedUserId;
            const activeBg = isSelected ? 'bg-black text-white hover:bg-black' : 'hover:bg-gray-100/50 text-gray-900';
            const unreadBadge = t.unread_count > 0 && !isSelected
                ? `<span class="bg-red-500 text-white text-[10px] font-black px-1.5 py-0.5 rounded-full">${t.unread_count}</span>` 
                : '';
            const msgPreviewColor = isSelected ? 'text-gray-300' : 'text-gray-500';
            
            // Format time
            const date = new Date(t.latest_message_time);
            const timeStr = date.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'});

            html += `
                <div class="p-4 flex items-start gap-3 cursor-pointer transition-all ${activeBg} thread-item" data-username="${t.username.toLowerCase()}" onclick="selectThread(${t.user_id}, '${t.username}', '${t.email}')">
                    <div class="w-10 h-10 rounded-full bg-gray-200 flex-shrink-0 flex items-center justify-center text-gray-700 font-bold uppercase text-sm border border-gray-300">
                        ${t.username.charAt(0)}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-bold truncate">${t.username}</p>
                            <span class="text-[10px] opacity-70">${timeStr}</span>
                        </div>
                        <p class="text-xs truncate ${msgPreviewColor} mt-1">${escapeHtml(t.latest_message)}</p>
                    </div>
                    <div class="flex-shrink-0 self-center">
                        ${unreadBadge}
                    </div>
                </div>
            `;
        });
        container.innerHTML = html;
    }

    function filterThreads() {
        const query = document.getElementById('threadSearch').value.toLowerCase();
        document.querySelectorAll('.thread-item').forEach(el => {
            const username = el.getAttribute('data-username');
            if (username.includes(query)) {
                el.classList.remove('hidden');
            } else {
                el.classList.add('hidden');
            }
        });
    }

    function selectThread(userId, username, email) {
        currentSelectedUserId = userId;
        
        // UI updates
        document.getElementById('chatWindowPlaceholder').classList.add('hidden');
        const activeWin = document.getElementById('activeChatWindow');
        activeWin.classList.remove('hidden');
        
        document.getElementById('activeCustomerName').innerText = username;
        document.getElementById('activeCustomerEmail').innerText = email;
        document.getElementById('activeUserId').value = userId;

        // Force thread active class re-render
        loadThreads();

        // Load conversation messages
        loadMessages();

        // Set message polling
        clearInterval(messagesInterval);
        messagesInterval = setInterval(loadMessages, 3000);
    }

    function loadMessages() {
        if (!currentSelectedUserId) return;

        fetch(`${BASEURL}/chat/admin_get_messages/${currentSelectedUserId}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    renderMessages(data.messages);
                }
            })
            .catch(err => console.error('Error loading messages:', err));
    }

    function renderMessages(messages) {
        const container = document.getElementById('adminChatMessages');
        if (!messages || messages.length === 0) {
            container.innerHTML = '<p class="text-xs text-gray-400 text-center py-20">Belum ada pesan.</p>';
            return;
        }

        let html = '';
        let lastProductId = null;

        messages.forEach(msg => {
            const isMe = msg.is_admin == 1;
            const bubbleBg = isMe 
                ? 'bg-black text-white rounded-br-none' 
                : 'bg-white border border-gray-200 text-gray-900 rounded-bl-none';
            const align = isMe ? 'justify-end' : 'justify-start';
            const dateStr = new Date(msg.created_at).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'});

            // Product Reference Banner (if message is bound to a product)
            let productBanner = '';
            if (msg.product_name) {
                lastProductId = msg.product_id;
                productBanner = `
                    <div class="text-[9px] border-b pb-1.5 mb-1.5 opacity-80 flex items-center gap-1.5 ${isMe ? 'border-white/20' : 'border-gray-200'}">
                        <i class="fas fa-shopping-bag"></i>
                        <span>Produk: <strong>${msg.product_name}</strong></span>
                    </div>
                `;
            }

            html += `
                <div class="flex ${align}">
                    <div class="max-w-[70%] rounded-2xl px-4 py-2.5 text-xs shadow-sm ${bubbleBg}">
                        ${productBanner}
                        <p class="leading-relaxed break-words">${escapeHtml(msg.message)}</p>
                        <p class="text-[9px] text-right mt-1 opacity-60">${dateStr}</p>
                    </div>
                </div>
            `;
        });

        // Track last product_id to pre-bind admin's reply to that product context
        if (lastProductId) {
            document.getElementById('activeProductId').value = lastProductId;
        }

        const isScrolledToBottom = container.scrollHeight - container.clientHeight <= container.scrollTop + 100;
        
        container.innerHTML = html;

        if (isScrolledToBottom || container.querySelector('.py-20')) {
            container.scrollTop = container.scrollHeight;
        }
    }

    // Submit Reply
    const sendForm = document.getElementById('adminChatSendForm');
    sendForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('adminChatMessageInput');
        const message = input.value.trim();
        const userId = document.getElementById('activeUserId').value;
        const productId = document.getElementById('activeProductId').value;

        if (!message || !userId) return;

        // Optimistic rendering
        const now = new Date();
        const dateStr = now.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'});
        const container = document.getElementById('adminChatMessages');
        const tempHtml = `
            <div class="flex justify-end">
                <div class="max-w-[70%] rounded-2xl px-4 py-2.5 text-xs bg-black text-white rounded-br-none shadow-sm opacity-60">
                    <p class="leading-relaxed break-words">${escapeHtml(message)}</p>
                    <p class="text-[9px] text-right mt-1 opacity-60">${dateStr} (mengirim...)</p>
                </div>
            </div>
        `;
        if (container.querySelector('.py-20')) {
            container.innerHTML = tempHtml;
        } else {
            container.innerHTML += tempHtml;
        }
        container.scrollTop = container.scrollHeight;
        input.value = '';

        const payload = {
            message: message,
            user_id: userId,
            product_id: productId ? parseInt(productId) : null
        };

        fetch(`${BASEURL}/chat/admin_send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                loadMessages();
                loadThreads();
            } else {
                alert('Gagal mengirim balasan: ' + data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert('Kesalahan jaringan.');
        });
    });

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

    // Startup page loading
    loadThreads();
    threadsInterval = setInterval(loadThreads, 5000); // Poll threads list every 5 seconds
</script>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>
