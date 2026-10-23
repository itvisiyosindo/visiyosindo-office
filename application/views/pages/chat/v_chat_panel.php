<style>
/* WhatsApp Web Styling for Office Visiyosindo */
.wa-chat-wrapper {
    display: flex;
    height: calc(100vh - 165px);
    min-height: 620px;
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #e2e8f0;
}

/* Kolom Kiri: Daftar Chat */
.wa-sidebar {
    width: 380px;
    min-width: 320px;
    max-width: 420px;
    border-right: 1px solid #e5e7eb;
    display: flex;
    flex-direction: column;
    background: #ffffff;
}

.wa-sidebar-header {
    padding: 16px;
    background: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
}

.wa-search-box {
    position: relative;
    margin-top: 10px;
}

.wa-search-box input {
    border-radius: 20px;
    padding-left: 36px;
    background: #f1f5f9;
    border: 1px solid transparent;
    font-size: 13px;
    transition: all 0.2s;
}

.wa-search-box input:focus {
    background: #ffffff;
    border-color: #25d366;
    box-shadow: 0 0 0 3px rgba(37, 211, 102, 0.15);
}

.wa-search-box .search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
}

.wa-filter-tabs {
    display: flex;
    gap: 6px;
    padding: 8px 12px;
    border-bottom: 1px solid #f1f5f9;
    background: #ffffff;
    overflow-x: auto;
}

.wa-filter-pill {
    font-size: 11px;
    padding: 4px 10px;
    border-radius: 14px;
    background: #f1f5f9;
    color: #64748b;
    border: none;
    cursor: pointer;
    font-weight: 500;
    white-space: nowrap;
    transition: all 0.15s;
}

.wa-filter-pill:hover,
.wa-filter-pill.active {
    background: #25d366;
    color: #ffffff;
}

.wa-conversation-list {
    flex: 1;
    overflow-y: auto;
}

.wa-conversation-item {
    display: flex;
    align-items: center;
    padding: 12px 14px;
    border-bottom: 1px solid #f1f5f9;
    cursor: pointer;
    transition: background 0.15s;
    position: relative;
}

.wa-conversation-item:hover {
    background: #f8fafc;
}

.wa-conversation-item.active {
    background: #e7f8ee;
    border-left: 4px solid #25d366;
}

.wa-avatar {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    background: #0ea5e9;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: bold;
    flex-shrink: 0;
    overflow: hidden;
    margin-right: 12px;
}

.wa-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.wa-conv-details {
    flex: 1;
    min-width: 0;
}

.wa-conv-top {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-bottom: 3px;
}

.wa-conv-name {
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 190px;
}

.wa-conv-time {
    font-size: 11px;
    color: #94a3b8;
    white-space: nowrap;
}

.wa-conv-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.wa-conv-msg {
    font-size: 12.5px;
    color: #64748b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 210px;
}

.wa-badge-unread {
    background: #25d366;
    color: white;
    font-size: 10px;
    font-weight: bold;
    border-radius: 10px;
    padding: 2px 7px;
    margin-left: 6px;
}

.wa-badge-tag {
    font-size: 9.5px;
    padding: 1px 6px;
    border-radius: 4px;
    font-weight: 600;
    text-transform: uppercase;
}
.wa-badge-customer { background: #e0f2fe; color: #0284c7; }
.wa-badge-karyawan { background: #f3e8ff; color: #9333ea; }
.wa-badge-umum { background: #f1f5f9; color: #64748b; }

/* Kolom Kanan: Jendela Percakapan */
.wa-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #efeae2;
    position: relative;
    background-image: radial-gradient(#d5cdbf 0.75px, transparent 0.75px);
    background-size: 16px 16px;
}

/* Header Percakapan */
.wa-main-header {
    padding: 12px 18px;
    background: #ffffff;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    z-index: 10;
}

.wa-header-info {
    display: flex;
    align-items: center;
}

.wa-header-meta {
    margin-left: 12px;
}

.wa-header-title {
    font-size: 15px;
    font-weight: 600;
    color: #0f172a;
    line-height: 1.2;
}

.wa-header-sub {
    font-size: 12px;
    color: #64748b;
    margin-top: 2px;
}

.wa-session-pill {
    display: inline-flex;
    align-items: center;
    font-size: 11px;
    padding: 4px 10px;
    border-radius: 20px;
    font-weight: 600;
}
.wa-session-pill.active { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
.wa-session-pill.expired { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

/* Stream Bubble Chat */
.wa-messages-container {
    flex: 1;
    overflow-y: auto;
    padding: 20px 24px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.wa-date-divider {
    text-align: center;
    margin: 12px 0;
}

.wa-date-divider span {
    background: rgba(255, 255, 255, 0.9);
    padding: 4px 12px;
    font-size: 11px;
    font-weight: 600;
    color: #475569;
    border-radius: 12px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
}

.wa-bubble-row {
    display: flex;
    width: 100%;
}

.wa-bubble-row.in {
    justify-content: flex-start;
}

.wa-bubble-row.out {
    justify-content: flex-end;
}

.wa-bubble {
    max-width: 65%;
    min-width: 120px;
    padding: 8px 12px 6px 12px;
    border-radius: 8px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.12);
    font-size: 13.5px;
    line-height: 1.45;
    position: relative;
    word-wrap: break-word;
}

.wa-bubble.in {
    background: #ffffff;
    color: #1e293b;
    border-top-left-radius: 2px;
}

.wa-bubble.out {
    background: #d9fdd3;
    color: #0f172a;
    border-top-right-radius: 2px;
}

.wa-bubble-meta {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 4px;
    margin-top: 4px;
    font-size: 10.5px;
    color: #64748b;
}

.wa-bubble-sender {
    font-size: 10.5px;
    font-weight: 600;
    color: #0284c7;
    margin-bottom: 2px;
}

/* Quick Response Templates */
.wa-quick-templates {
    padding: 6px 14px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    display: flex;
    gap: 6px;
    overflow-x: auto;
    align-items: center;
}

.wa-template-btn {
    font-size: 11px;
    padding: 3px 8px;
    border-radius: 12px;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    color: #475569;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.15s;
}

.wa-template-btn:hover {
    background: #f1f5f9;
    border-color: #94a3b8;
    color: #0f172a;
}

/* Footer Composer Input */
.wa-composer {
    padding: 10px 14px;
    background: #f0f2f5;
    border-top: 1px solid #e2e8f0;
    display: flex;
    align-items: flex-end;
    gap: 8px;
}

.wa-composer textarea {
    flex: 1;
    border-radius: 20px;
    padding: 10px 16px;
    border: 1px solid #cbd5e1;
    resize: none;
    font-size: 13.5px;
    max-height: 120px;
    min-height: 42px;
    line-height: 1.4;
    outline: none;
}

.wa-composer textarea:focus {
    border-color: #25d366;
    box-shadow: 0 0 0 2px rgba(37, 211, 102, 0.2);
    background: #ffffff;
}

.wa-btn-send {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #25d366;
    color: #ffffff;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    cursor: pointer;
    flex-shrink: 0;
    transition: all 0.2s;
    box-shadow: 0 2px 6px rgba(37, 211, 102, 0.4);
}

.wa-btn-send:hover {
    background: #20ba59;
    transform: scale(1.05);
}

.wa-btn-send:disabled {
    background: #94a3b8;
    cursor: not-allowed;
    box-shadow: none;
    transform: none;
}

/* Empty State */
.wa-empty-state {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #64748b;
    text-align: center;
    padding: 30px;
}

.wa-empty-state i {
    font-size: 64px;
    color: #25d366;
    margin-bottom: 16px;
    opacity: 0.8;
}

.wa-bypass-box {
    padding: 6px 14px;
    background: #fef2f2;
    border-top: 1px solid #fecaca;
    font-size: 11.5px;
    color: #991b1b;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
</style>

<!-- Header Breadcrumb & Actions -->
<header class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="fab fa-whatsapp text-success mr-2"></i><?= $page_title ?></h2>
    <div class="right-wrapper pr-3">
        <button type="button" class="btn btn-sm btn-success mr-2" data-toggle="modal" data-target="#modalNewChat">
            <i class="fas fa-plus mr-1"></i> Mulai Chat Baru
        </button>
        <button type="button" class="btn btn-sm btn-primary" onclick="loadConversations()">
            <i class="fas fa-sync-alt mr-1"></i> Refresh
        </button>
    </div>
</header>

<!-- Main Container Panel Chat -->
<div class="container-fluid px-0">
    <div class="wa-chat-wrapper">
        <!-- Kolom Kiri: Daftar Kontak & Chat -->
        <div class="wa-sidebar">
            <div class="wa-sidebar-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong class="text-dark"><i class="fab fa-whatsapp text-success mr-1"></i> WhatsApp Inbox</strong>
                        <div class="text-muted small" style="font-size: 11px;">Nomor Kantor: <strong>0896-6280-3131</strong></div>
                    </div>
                    <span class="badge badge-success px-2 py-1"><i class="fas fa-circle mr-1" style="font-size: 8px;"></i> Fonnte Gateway</span>
                </div>
                <div class="wa-search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari nama, RS, nomor HP..." onkeyup="handleSearch()">
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="wa-filter-tabs">
                <button class="wa-filter-pill active" onclick="setTab('all', this)">Semua</button>
                <button class="wa-filter-pill" onclick="setTab('unread', this)">Belum Dibaca</button>
                <button class="wa-filter-pill" onclick="setTab('active', this)">🟢 Sesi 24j</button>
                <button class="wa-filter-pill" onclick="setTab('customer', this)">Customer</button>
                <button class="wa-filter-pill" onclick="setTab('karyawan', this)">Karyawan</button>
            </div>

            <!-- List Percakapan -->
            <div class="wa-conversation-list" id="conversationList">
                <div class="p-4 text-center text-muted">
                    <i class="fas fa-spinner fa-spin fa-2x mb-2 text-success"></i>
                    <div>Memuat daftar percakapan...</div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Jendela Percakapan -->
        <div class="wa-main" id="chatMainArea">
            <!-- Empty State Awal -->
            <div class="wa-empty-state" id="emptyStateView">
                <i class="fab fa-whatsapp"></i>
                <h4 class="font-weight-bold text-dark mb-1">WhatsApp Live Chat Office</h4>
                <p class="text-muted mb-3" style="max-width: 400px; font-size: 13px;">
                    Kelola dan balas chat dari Pelanggan maupun Karyawan secara langsung dari sistem office tanpa perlu buka HP.
                </p>
                <button type="button" class="btn btn-outline-success btn-sm rounded-pill px-3" data-toggle="modal" data-target="#modalNewChat">
                    <i class="fas fa-paper-plane mr-1"></i> Buka Chat Baru
                </button>
            </div>

            <!-- Chat Active View (Hidden by default) -->
            <div id="activeChatView" style="display: none; height: 100%; flex-direction: column;">
                <!-- Header Chat -->
                <div class="wa-main-header">
                    <div class="wa-header-info">
                        <div class="wa-avatar" id="headerAvatar">A</div>
                        <div class="wa-header-meta">
                            <div class="d-flex align-items-center">
                                <span class="wa-header-title" id="headerName">Nama Kontak</span>
                                <span class="wa-badge-tag ml-2" id="headerBadge">CUSTOMER</span>
                            </div>
                            <div class="wa-header-sub">
                                <span id="headerPhone" class="mr-2">0812-xxxx-xxxx</span>
                                <span class="text-muted">•</span>
                                <span id="headerSubtext" class="ml-2 text-muted">Subtext</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="mr-3" id="headerSessionStatus">
                            <!-- Injected dynamically -->
                        </div>
                        <button type="button" class="btn btn-light btn-sm rounded-circle text-muted" title="Refresh Pesan" onclick="refreshActiveChat(false)">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>

                <!-- Bypass Warning Box (jika sesi expired) -->
                <div class="wa-bypass-box" id="bypassWarningBox" style="display: none;">
                    <div>
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        <strong>Sesi 24 Jam Telah Habis:</strong> Pesan bebas hanya bisa diterima setelah pelanggan mengirim chat baru.
                    </div>
                    <label class="mb-0 ml-2 font-weight-bold text-danger" style="cursor: pointer;">
                        <input type="checkbox" id="checkBypass" class="mr-1"> Kirim Paksa (Bypass Window)
                    </label>
                </div>

                <!-- Bubble Messages Container -->
                <div class="wa-messages-container" id="messagesContainer">
                    <!-- Injected dynamically -->
                </div>

                <!-- Quick Response Templates -->
                <div class="wa-quick-templates">
                    <span class="text-muted font-weight-bold mr-1" style="font-size: 11px;"><i class="fas fa-bolt text-warning mr-1"></i> Template:</span>
                    <button class="wa-template-btn" onclick="insertTemplate('Halo bapak/ibu, terima kasih telah menghubungi PT Visi Yosindo Medikal. Ada yang bisa kami bantu?')">Salam Pembuka</button>
                    <button class="wa-template-btn" onclick="insertTemplate('Pesanan Purchase Order (PO) Anda telah kami terima dan saat ini sedang dalam proses kroscek oleh tim kami.')">Konfirmasi PO</button>
                    <button class="wa-template-btn" onclick="insertTemplate('Mohon kirimkan dokumen PO/Surat terkait dalam bentuk PDF atau gambar agar bisa langsung kami tindak lanjuti. Terima kasih.')">Minta File PO</button>
                    <button class="wa-template-btn" onclick="insertTemplate('Pengiriman barang sudah dijadwalkan oleh bagian gudang kami. Resi pengiriman akan segera kami informasikan.')">Info Pengiriman</button>
                </div>

                <!-- Chat Composer Footer -->
                <div class="wa-composer">
                    <textarea id="messageInput" placeholder="Ketik balasan WhatsApp di sini... (Tekan Enter untuk kirim, Shift+Enter untuk baris baru)" onkeydown="handleComposerKey(event)"></textarea>
                    <button type="button" id="btnSend" class="wa-btn-send" title="Kirim Pesan (Enter)" onclick="sendMessage()">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Mulai Chat Baru -->
<div class="modal fade" id="modalNewChat" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white py-2">
                <h5 class="modal-title font-weight-bold"><i class="fab fa-whatsapp mr-1"></i> Mulai Percakapan Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="font-weight-bold">Nomor WhatsApp Tujuan <span class="text-danger">*</span></label>
                    <input type="text" id="newChatPhone" class="form-control" placeholder="Contoh: 081234567890 atau 6281234567890">
                    <small class="text-muted">Bisa nomor Customer, Rumah Sakit, Rekanan, atau Karyawan.</small>
                </div>
                <div class="form-group mb-2">
                    <label class="font-weight-bold">Pesan Pertama (Opsional)</label>
                    <textarea id="newChatMessage" class="form-control" rows="3" placeholder="Ketik pesan pembuka..."></textarea>
                </div>
                <div class="custom-control custom-checkbox mt-2">
                    <input type="checkbox" class="custom-control-input" id="newChatBypass" checked>
                    <label class="custom-control-label small text-muted" for="newChatBypass">Kirim langsung tanpa menunggu sesi 24 jam</label>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success btn-sm" onclick="startNewChat()">
                    <i class="fas fa-paper-plane mr-1"></i> Buka Chat
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// State Management
var activePhone = '<?= !empty($active_phone) ? $active_phone : "" ?>';
var currentTab = 'all';
var conversationsData = [];
var lastMessageId = 0;
var pollChatTimer = null;
var pollListTimer = null;
var isSending = false;

// Inisialisasi saat halaman selesai dimuat
document.addEventListener('DOMContentLoaded', function() {
    loadConversations(function() {
        if (activePhone) {
            selectConversation(activePhone);
        }
    });

    // Background Pollers
    pollListTimer = setInterval(function() {
        loadConversationsSilent();
    }, 10000); // Poll conversation list setiap 10 detik

    pollChatTimer = setInterval(function() {
        if (activePhone) {
            pollNewMessages();
        }
    }, 3500); // Poll pesan aktif setiap 3.5 detik
});

// Sound notification menggunakan Web Audio API (tidak butuh file eksternal)
function playBeep() {
    try {
        var audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        var osc = audioCtx.createOscillator();
        var gain = audioCtx.createGain();
        osc.type = 'sine';
        osc.frequency.setValueAtTime(587.33, audioCtx.currentTime); // D5
        osc.frequency.setValueAtTime(880, audioCtx.currentTime + 0.08); // A5
        gain.gain.setValueAtTime(0.1, audioCtx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.25);
        osc.connect(gain);
        gain.connect(audioCtx.destination);
        osc.start();
        osc.stop(audioCtx.currentTime + 0.25);
    } catch(e) {}
}

// Ganti Tab Filter
function setTab(tab, el) {
    currentTab = tab;
    document.querySelectorAll('.wa-filter-pill').forEach(function(pill) {
        pill.classList.remove('active');
    });
    if (el) el.classList.add('active');
    renderConversationList();
}

// Pencarian Kontak
var searchTimeout = null;
function handleSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(function() {
        renderConversationList();
    }, 200);
}

// Muat Daftar Percakapan dari Server
function loadConversations(callback) {
    var search = document.getElementById('searchInput').value;
    var url = '<?= base_url("chat/get_conversations") ?>?tab=' + encodeURIComponent(currentTab) + '&search=' + encodeURIComponent(search);

    fetch(url)
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.status === 'success') {
                conversationsData = data.data || [];
                renderConversationList();
                if (typeof callback === 'function') {
                    callback();
                }
            }
        })
        .catch(function(err) {
            console.error('Error load conversations:', err);
        });
}

// Muat Daftar Percakapan secara Senyap (Tanpa reset UI)
function loadConversationsSilent() {
    var search = document.getElementById('searchInput').value;
    var url = '<?= base_url("chat/get_conversations") ?>?tab=' + encodeURIComponent(currentTab) + '&search=' + encodeURIComponent(search);

    fetch(url)
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.status === 'success') {
                conversationsData = data.data || [];
                renderConversationList();
            }
        });
}

// Render DOM Daftar Percakapan
function renderConversationList() {
    var container = document.getElementById('conversationList');
    var search = (document.getElementById('searchInput').value || '').toLowerCase().trim();

    var filtered = conversationsData.filter(function(item) {
        if (search) {
            var match = (
                (item.name || '').toLowerCase().indexOf(search) !== -1 ||
                (item.phone || '').indexOf(search) !== -1 ||
                (item.subtext || '').toLowerCase().indexOf(search) !== -1 ||
                (item.last_message || '').toLowerCase().indexOf(search) !== -1
            );
            if (!match) return false;
        }

        if (currentTab === 'unread') return item.unread_count > 0;
        if (currentTab === 'active') return item.session_active;
        if (currentTab === 'customer') return item.type === 'customer';
        if (currentTab === 'karyawan') return item.type === 'karyawan';
        return true;
    });

    if (filtered.length === 0) {
        container.innerHTML = '<div class="p-4 text-center text-muted" style="font-size: 13px;"><i class="fas fa-inbox fa-2x mb-2 text-muted" style="opacity: 0.4;"></i><div>Tidak ada percakapan ditemukan.</div></div>';
        return;
    }

    var html = '';
    filtered.forEach(function(item) {
        var isActive = (item.phone === activePhone) ? 'active' : '';
        var initial = (item.name || 'W').charAt(0).toUpperCase();

        var avatarContent = initial;
        if (item.avatar) {
            avatarContent = '<img src="' + item.avatar + '" alt="' + item.name + '" onerror="this.parentElement.innerHTML=\'' + initial + '\'">';
        }

        var badgeClass = 'wa-badge-umum';
        var badgeText = 'UMUM';
        if (item.type === 'customer') {
            badgeClass = 'wa-badge-customer';
            badgeText = 'CUSTOMER';
        } else if (item.type === 'karyawan') {
            badgeClass = 'wa-badge-karyawan';
            badgeText = 'KARYAWAN';
        }

        var unreadBadge = item.unread_count > 0 ? '<span class="wa-badge-unread">' + item.unread_count + '</span>' : '';
        
        var dirIcon = '';
        if (item.last_direction === 'out') {
            dirIcon = '<i class="fas fa-check text-muted mr-1" style="font-size: 11px;"></i>';
        }

        var sessionDot = item.session_active 
            ? '<span class="text-success ml-1" title="Sesi 24j Aktif: ' + item.remaining_text + '" style="font-size: 9px;"><i class="fas fa-circle"></i></span>'
            : '';

        html += '<div class="wa-conversation-item ' + isActive + '" onclick="selectConversation(\'' + item.phone + '\')">';
        html += '  <div class="wa-avatar">' + avatarContent + '</div>';
        html += '  <div class="wa-conv-details">';
        html += '    <div class="wa-conv-top">';
        html += '      <div class="wa-conv-name">' + escapeHtml(item.name) + sessionDot + '</div>';
        html += '      <div class="wa-conv-time">' + (item.last_time_display || '') + '</div>';
        html += '    </div>';
        html += '    <div class="wa-conv-bottom">';
        html += '      <div class="wa-conv-msg">' + dirIcon + escapeHtml(item.last_message || '') + '</div>';
        html += '      <div class="d-flex align-items-center">';
        html += '        <span class="wa-badge-tag ' + badgeClass + '">' + badgeText + '</span>';
        html +=          unreadBadge;
        html += '      </div>';
        html += '    </div>';
        html += '  </div>';
        html += '</div>';
    });

    container.innerHTML = html;
}

// Buka dan Pilih Percakapan
function selectConversation(phone) {
    activePhone = phone;
    lastMessageId = 0;

    // Update state tampilan
    document.getElementById('emptyStateView').style.display = 'none';
    var activeView = document.getElementById('activeChatView');
    activeView.style.display = 'flex';

    // Highlight item di sidebar
    renderConversationList();

    // Reset container pesan
    var container = document.getElementById('messagesContainer');
    container.innerHTML = '<div class="p-4 text-center text-muted"><i class="fas fa-spinner fa-spin fa-2x text-success mb-2"></i><div>Memuat riwayat chat...</div></div>';

    // Ambil pesan dari server
    refreshActiveChat(true);

    // Focus input
    setTimeout(function() {
        var input = document.getElementById('messageInput');
        if (input) input.focus();
    }, 300);
}

// Refresh Pesan Percakapan Aktif
function refreshActiveChat(scrollToBottom) {
    if (!activePhone) return;

    var url = '<?= base_url("chat/get_messages") ?>?phone=' + encodeURIComponent(activePhone) + '&last_id=0';

    fetch(url)
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.status === 'success') {
                updateChatHeader(data);
                renderMessages(data.messages || [], false);
                if (scrollToBottom) {
                    scrollChatToBottom();
                }
                // Update unread count lokal
                var conv = conversationsData.find(function(c) { return c.phone === activePhone; });
                if (conv) conv.unread_count = 0;
            }
        });
}

// Incremental Poller Pesan Baru
function pollNewMessages() {
    if (!activePhone || lastMessageId === 0) return;

    var url = '<?= base_url("chat/get_messages") ?>?phone=' + encodeURIComponent(activePhone) + '&last_id=' + lastMessageId;

    fetch(url)
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.status === 'success' && data.messages && data.messages.length > 0) {
                renderMessages(data.messages, true);
                scrollChatToBottom();
                playBeep();
                // Refresh list percakapan agar pesan terakhir terupdate
                loadConversationsSilent();
            }
        });
}

// Update Header Kontak & Status Sesi 24 Jam
function updateChatHeader(data) {
    var contact = data.contact || {};
    var session = data.session || {};

    document.getElementById('headerName').innerText = contact.name || data.phone;
    document.getElementById('headerPhone').innerText = data.phone;
    document.getElementById('headerSubtext').innerText = contact.subtext || '';

    var initial = (contact.name || 'W').charAt(0).toUpperCase();
    var avatarEl = document.getElementById('headerAvatar');
    if (contact.avatar) {
        avatarEl.innerHTML = '<img src="' + contact.avatar + '" alt="' + contact.name + '" onerror="this.parentElement.innerHTML=\'' + initial + '\'">';
    } else {
        avatarEl.innerText = initial;
    }

    var badgeEl = document.getElementById('headerBadge');
    badgeEl.className = 'wa-badge-tag ml-2';
    if (contact.type === 'customer') {
        badgeEl.classList.add('wa-badge-customer');
        badgeEl.innerText = 'CUSTOMER';
    } else if (contact.type === 'karyawan') {
        badgeEl.classList.add('wa-badge-karyawan');
        badgeEl.innerText = 'KARYAWAN';
    } else {
        badgeEl.classList.add('wa-badge-umum');
        badgeEl.innerText = 'UMUM';
    }

    // Status Sesi 24 Jam
    var sessionContainer = document.getElementById('headerSessionStatus');
    var bypassWarning = document.getElementById('bypassWarningBox');

    if (session.active) {
        sessionContainer.innerHTML = '<span class="wa-session-pill active"><i class="fas fa-shield-alt mr-1"></i> Sesi Aktif (' + session.remaining_text + ')</span>';
        bypassWarning.style.display = 'none';
        document.getElementById('checkBypass').checked = false;
    } else {
        sessionContainer.innerHTML = '<span class="wa-session-pill expired"><i class="fas fa-exclamation-circle mr-1"></i> Sesi Kedaluwarsa</span>';
        bypassWarning.style.display = 'flex';
    }
}

// Render Gelembung Chat
function renderMessages(messages, isAppend) {
    var container = document.getElementById('messagesContainer');
    if (!isAppend) {
        container.innerHTML = '';
    }

    if (messages.length === 0 && !isAppend) {
        container.innerHTML = '<div class="p-4 text-center text-muted" style="margin: auto;"><i class="far fa-comments fa-2x mb-2 text-muted" style="opacity: 0.5;"></i><div>Belum ada riwayat percakapan dengan nomor ini.</div><small>Ketik pesan di bawah untuk memulai obrolan.</small></div>';
        return;
    }

    var currentDate = '';
    var html = '';

    messages.forEach(function(m) {
        if (m.id > lastMessageId) {
            lastMessageId = m.id;
        }

        // Tanggal pembatas
        if (m.date_display && m.date_display !== currentDate) {
            currentDate = m.date_display;
            html += '<div class="wa-date-divider"><span>' + currentDate + '</span></div>';
        }

        var isOut = (m.direction === 'out');
        var bubbleClass = isOut ? 'out' : 'in';
        var senderLabel = '';

        if (isOut) {
            senderLabel = '<div class="wa-bubble-sender" style="color: #059669;">' + escapeHtml(m.sender_name || 'Admin Office') + '</div>';
        } else if (m.sender_name) {
            senderLabel = '<div class="wa-bubble-sender">' + escapeHtml(m.sender_name) + '</div>';
        }

        var checkIcon = '';
        if (isOut) {
            checkIcon = '<i class="fas fa-check-double text-primary ml-1" style="font-size: 10px;"></i>';
        }

        var mediaHtml = '';
        if (m.media_url) {
            if (m.media_type === 'image') {
                mediaHtml = '<div class="mb-2"><a href="' + m.media_url + '" target="_blank"><img src="' + m.media_url + '" style="max-width: 250px; border-radius: 6px; max-height: 250px; object-fit: cover;"></a></div>';
            } else {
                mediaHtml = '<div class="mb-2"><a href="' + m.media_url + '" target="_blank" class="btn btn-sm btn-light border"><i class="fas fa-paperclip mr-1"></i> Buka Lampiran</a></div>';
            }
        }

        var textFormatted = nl2br(escapeHtml(m.message || ''));

        html += '<div class="wa-bubble-row ' + bubbleClass + '">';
        html += '  <div class="wa-bubble ' + bubbleClass + '">';
        html +=      senderLabel;
        html +=      mediaHtml;
        html += '    <div>' + textFormatted + '</div>';
        html += '    <div class="wa-bubble-meta">';
        html += '      <span>' + m.time_display + '</span>';
        html +=        checkIcon;
        html += '    </div>';
        html += '  </div>';
        html += '</div>';
    });

    if (isAppend) {
        container.insertAdjacentHTML('beforeend', html);
    } else {
        container.innerHTML = html;
    }
}

// Kirim Balasan Pesan WhatsApp
function sendMessage() {
    if (isSending || !activePhone) return;

    var input = document.getElementById('messageInput');
    var message = (input.value || '').trim();
    if (!message) return;

    var bypass24h = document.getElementById('checkBypass').checked ? 1 : 0;
    var btn = document.getElementById('btnSend');

    isSending = true;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    var formData = new FormData();
    formData.append('phone', activePhone);
    formData.append('message', message);
    formData.append('bypass_24h', bypass24h);

    fetch('<?= base_url("chat/send_message") ?>', {
        method: 'POST',
        body: formData
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        isSending = false;
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane"></i>';

        if (data.status === 'success' || data.status === 'warning') {
            input.value = '';
            input.style.height = 'auto';

            // Segera ambil pesan baru
            refreshActiveChat(true);
            loadConversationsSilent();
        } else if (data.code === 'SESSION_EXPIRED') {
            Swal.fire({
                icon: 'warning',
                title: 'Sesi 24 Jam Kedaluwarsa',
                text: data.message,
                showCancelButton: true,
                confirmButtonText: 'Kirim Paksa (Bypass)',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#25d366'
            }).then(function(result) {
                if (result.isConfirmed) {
                    document.getElementById('checkBypass').checked = true;
                    sendMessage();
                }
            });
        } else {
            Swal.fire('Gagal Kirim', data.message || 'Terjadi kesalahan sistem', 'error');
        }
    })
    .catch(function(err) {
        isSending = false;
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane"></i>';
        console.error('Send error:', err);
        Swal.fire('Gagal', 'Terjadi gangguan jaringan ke server', 'error');
    });
}

// Mulai Chat Baru dari Modal
function startNewChat() {
    var phoneInput = document.getElementById('newChatPhone');
    var msgInput = document.getElementById('newChatMessage');
    var bypass = document.getElementById('newChatBypass').checked ? 1 : 0;

    var rawPhone = (phoneInput.value || '').trim();
    if (!rawPhone) {
        Swal.fire('Perhatian', 'Silakan masukkan nomor WhatsApp tujuan', 'warning');
        return;
    }

    var cleanPhone = rawPhone.replace(/[^0-9]/g, '');
    if (cleanPhone.substr(0, 1) === '0') {
        cleanPhone = '62' + cleanPhone.substr(1);
    } else if (cleanPhone.substr(0, 1) === '8') {
        cleanPhone = '628' + cleanPhone.substr(1);
    }

    var initialMsg = (msgInput.value || '').trim();

    $('#modalNewChat').modal('hide');
    phoneInput.value = '';
    msgInput.value = '';

    selectConversation(cleanPhone);

    if (initialMsg) {
        document.getElementById('messageInput').value = initialMsg;
        if (bypass) {
            document.getElementById('checkBypass').checked = true;
        }
        sendMessage();
    }
}

// Helper: Masukkan template pesan ke input composer
function insertTemplate(text) {
    var input = document.getElementById('messageInput');
    input.value = text;
    input.focus();
    input.style.height = 'auto';
    input.style.height = (input.scrollHeight) + 'px';
}

// Helper: Handle keyboard enter
function handleComposerKey(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
}

// Helper: Scroll ke bawah
function scrollChatToBottom() {
    setTimeout(function() {
        var container = document.getElementById('messagesContainer');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }, 100);
}

// Helper: Escape HTML
function escapeHtml(text) {
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return (text || '').replace(/[&<>"']/g, function(m) { return map[m]; });
}

// Helper: nl2br
function nl2br(str) {
    return (str || '').replace(/\n/g, '<br>');
}
</script>
