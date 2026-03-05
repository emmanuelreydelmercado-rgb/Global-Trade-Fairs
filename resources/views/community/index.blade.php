{{-- Community Tab - Main Page --}}
<x-app-layout>

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/community.css') }}">
@endpush

<div class="community-page" id="communityPage">

    {{-- =====================================================================
         SIDEBAR
         ===================================================================== --}}
    <aside class="community-sidebar" id="sidebar">

        {{-- Header --}}
        <div class="sidebar-header">
            <h2>💬 Community</h2>
            <p>Chat with your trade network</p>
            <div class="sidebar-actions">
                <button class="btn-find-friend" id="btnOpenFindFriend">
                    🔍 Find Friend
                </button>
                <button class="btn-pending" id="btnOpenPending" title="Pending friend requests">
                    👥 Requests
                    <span class="pending-badge {{ $pendingCount === 0 ? 'hidden' : '' }}" id="pendingBadge">
                        {{ $pendingCount }}
                    </span>
                </button>
            </div>
        </div>

        {{-- Friends search --}}
        <div class="friends-search">
            <input type="text" id="friendsSearchInput" placeholder="🔎 Search friends..." />
        </div>

        {{-- Friends list --}}
        <div class="friends-list" id="friendsList">
            @forelse($friends as $friend)
                <div class="friend-item {{ $selectedFriend && $selectedFriend->id == $friend['id'] ? 'active' : '' }}"
                     data-friend-id="{{ $friend['id'] }}"
                     data-friend-name="{{ $friend['name'] }}"
                     data-friend-avatar="{{ $friend['avatar'] }}"
                     onclick="openChat({{ $friend['id'] }}, '{{ addslashes($friend['name']) }}', '{{ $friend['avatar'] }}')">
                    <div class="friend-avatar">
                        <img src="{{ $friend['avatar'] }}" alt="{{ $friend['name'] }}" onerror="this.src='{{ asset('profilepics/user_avatar.png') }}'">
                    </div>
                    <div class="friend-info">
                        <div class="friend-name">{{ $friend['name'] }}</div>
                        <div class="friend-last-msg">{{ $friend['last_message'] }}</div>
                    </div>
                    <div class="friend-meta">
                        <span class="friend-time">{{ $friend['last_time'] }}</span>
                        @if($friend['unread'] > 0)
                            <span class="unread-badge">{{ $friend['unread'] }}</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="friends-list-empty">
                    <div class="empty-icon">👥</div>
                    <div>No friends yet.<br>Use "Find Friend" to get started!</div>
                </div>
            @endforelse
        </div>

    </aside>

    {{-- Overlay for mobile sidebar --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    {{-- Mobile sidebar toggle --}}
    <button class="sidebar-toggle-btn" id="sidebarToggle" onclick="toggleSidebar()" title="Open friends">👥</button>

    {{-- =====================================================================
         CHAT AREA
         ===================================================================== --}}
    <section class="community-chat" id="chatArea">

        {{-- Empty state (no friend selected) --}}
        <div class="chat-empty-state" id="chatEmptyState"
             style="{{ $selectedFriend ? 'display:none' : '' }}">
            <div class="empty-icon">🌐</div>
            <h3>Start a Conversation</h3>
            <p>Select a friend from the sidebar to chat, or use "Find Friend" to connect with other trade fair professionals.</p>
        </div>

        {{-- Chat Window (shown when friend selected) --}}
        <div id="chatWindow" style="{{ $selectedFriend ? '' : 'display:none' }}" class="community-chat">

            {{-- Chat Header --}}
            <div class="chat-header">
                <div class="chat-header-avatar">
                    <img src="{{ $selectedFriend ? (($selectedFriend->profilepic && $selectedFriend->profilepic !== 'default.jpg') ? asset('profilepics/' . $selectedFriend->profilepic) : asset('profilepics/user_avatar.png')) : asset('profilepics/user_avatar.png') }}"
                         alt="Friend" id="chatHeaderAvatar"
                         onerror="this.src='{{ asset('profilepics/user_avatar.png') }}'">
                </div>
                <div class="chat-header-info">
                    <div class="chat-header-name" id="chatHeaderName">
                        {{ $selectedFriend ? $selectedFriend->name : '' }}
                    </div>
                    <div class="chat-header-status">Online</div>
                </div>

                {{-- Language Selector --}}
                <div class="chat-lang-selector">
                    <span class="chat-lang-label">🌍 Language:</span>
                    <select class="lang-select-dropdown" id="languageSelect" onchange="onLanguageChange()">
                        <option value="en">🇬🇧 English</option>
                        <option value="ta">🇮🇳 Tamil</option>
                        <option value="hi">🇮🇳 Hindi</option>
                        <option value="fr">🇫🇷 French</option>
                        <option value="es">🇪🇸 Spanish</option>
                        <option value="de">🇩🇪 German</option>
                        <option value="zh">🇨🇳 Chinese</option>
                        <option value="ja">🇯🇵 Japanese</option>
                        <option value="ko">🇰🇷 Korean</option>
                        <option value="ar">🇸🇦 Arabic</option>
                        <option value="pt">🇵🇹 Portuguese</option>
                        <option value="ru">🇷🇺 Russian</option>
                        <option value="it">🇮🇹 Italian</option>
                        <option value="nl">🇳🇱 Dutch</option>
                        <option value="tr">🇹🇷 Turkish</option>
                        <option value="vi">🇻🇳 Vietnamese</option>
                        <option value="th">🇹🇭 Thai</option>
                        <option value="pl">🇵🇱 Polish</option>
                        <option value="sv">🇸🇪 Swedish</option>
                        <option value="ml">🇮🇳 Malayalam</option>
                        <option value="te">🇮🇳 Telugu</option>
                        <option value="kn">🇮🇳 Kannada</option>
                        <option value="bn">🇧🇩 Bengali</option>
                        <option value="mr">🇮🇳 Marathi</option>
                        <option value="ur">🇵🇰 Urdu</option>
                    </select>
                </div>
            </div>

            {{-- Messages --}}
            <div class="chat-messages" id="chatMessages">
                <div class="spinner" style="margin: 40px auto;"></div>
            </div>

            {{-- Input Area --}}
            <div class="chat-input-area">
                <div class="chat-input-wrap">
                    <textarea class="chat-input-textarea"
                              id="chatInput"
                              rows="1"
                              placeholder="Type a message…"
                              onkeydown="handleInputKeydown(event)"
                              oninput="autoResize(this)"></textarea>
                </div>
                <button class="chat-send-btn" id="sendBtn" onclick="sendMessage()" title="Send message">
                    ➤
                </button>
            </div>

        </div>
    </section>

</div>

{{-- =====================================================================
     MODAL: Find Friend
     ===================================================================== --}}
<div class="modal-overlay" id="findFriendModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3>🔍 Find Friend</h3>
            <button class="modal-close" onclick="closeModal('findFriendModal')">✕</button>
        </div>
        <div class="modal-body">
            <div class="find-friend-search">
                <input type="email" id="findFriendEmail" placeholder="Enter email address…"
                       onkeydown="if(event.key==='Enter') searchFriend()">
                <button class="btn-search" id="btnSearch" onclick="searchFriend()">Search</button>
            </div>
            <div id="findFriendResult"></div>
        </div>
    </div>
</div>

{{-- =====================================================================
     MODAL: Pending Friend Requests
     ===================================================================== --}}
<div class="modal-overlay" id="pendingModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3>👥 Friend Requests</h3>
            <button class="modal-close" onclick="closeModal('pendingModal')">✕</button>
        </div>
        <div class="modal-body">
            <div class="pending-requests-list" id="pendingList">
                <div class="spinner" style="margin: 20px auto;"></div>
            </div>
        </div>
    </div>
</div>

{{-- =====================================================================
     MODAL: Edit Message
     ===================================================================== --}}
<div class="modal-overlay" id="editMsgModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3>✏️ Edit Message</h3>
            <button class="modal-close" onclick="closeModal('editMsgModal')">✕</button>
        </div>
        <div class="modal-body">
            <textarea class="edit-msg-textarea" id="editMsgText" rows="4" placeholder="Edit your message…"></textarea>
            <button class="btn-save-edit" onclick="saveEdit()">Save Changes</button>
        </div>
    </div>
</div>

{{-- Toast container --}}
<div class="toast-container" id="toastContainer"></div>

@push('scripts')
<script>
// =====================================================================
// STATE
// =====================================================================
const ME            = {{ auth()->id() }};
const CSRF          = document.querySelector('meta[name="csrf-token"]').content;

let activeFriendId   = {{ $selectedFriend ? $selectedFriend->id : 'null' }};
let activeFriendName = '{{ $selectedFriend ? addslashes($selectedFriend->name) : '' }}';
let lastMsgId        = 0;
let pollTimer        = null;
let editingMsgId     = null;

// =====================================================================
// INIT
// =====================================================================
document.addEventListener('DOMContentLoaded', () => {
    // Load lang preference from localStorage
    const savedLang = localStorage.getItem('comm_lang') || 'en';
    document.getElementById('languageSelect').value = savedLang;

    if (activeFriendId) {
        loadMessages(true);
        startPolling();
    }

    // Friends sidebar search
    document.getElementById('friendsSearchInput').addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.friend-item').forEach(el => {
            el.style.display = el.dataset.friendName.toLowerCase().includes(q) ? '' : 'none';
        });
    });
});

// =====================================================================
// FRIENDS / SIDEBAR
// =====================================================================
function openChat(friendId, friendName, friendAvatar) {
    clearInterval(pollTimer);

    activeFriendId   = friendId;
    activeFriendName = friendName;
    lastMsgId        = 0;

    // Update header
    document.getElementById('chatHeaderName').textContent   = friendName;
    document.getElementById('chatHeaderAvatar').src          = friendAvatar;
    document.getElementById('chatEmptyState').style.display  = 'none';
    document.getElementById('chatWindow').style.display      = '';

    // Highlight active friend
    document.querySelectorAll('.friend-item').forEach(el => {
        el.classList.toggle('active', parseInt(el.dataset.friendId) === friendId);
    });

    // Restore saved lang
    const savedLang = localStorage.getItem('comm_lang') || 'en';
    document.getElementById('languageSelect').value = savedLang;

    // Load messages
    document.getElementById('chatMessages').innerHTML = '<div class="spinner" style="margin:40px auto;"></div>';
    loadMessages(true);
    startPolling();

    // Close sidebar on mobile
    closeSidebar();
}

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('active');
}

function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('active');
}

// =====================================================================
// LANGUAGE
// =====================================================================
function onLanguageChange() {
    const lang = document.getElementById('languageSelect').value;
    localStorage.setItem('comm_lang', lang);
    // Reload messages to re-render translations
    lastMsgId = 0;
    loadMessages(true);
}

// =====================================================================
// MESSAGES — POLLING
// =====================================================================
function startPolling() {
    clearInterval(pollTimer);
    pollTimer = setInterval(() => loadMessages(false), 3000);
}

async function loadMessages(fullLoad = false) {
    if (!activeFriendId) return;

    const lang    = document.getElementById('languageSelect').value;
    const afterId = fullLoad ? 0 : lastMsgId;

    try {
        const res = await fetch(
            `/community/messages/${activeFriendId}?my_lang=${lang}&after_id=${afterId}`,
            { headers: { 'X-Requested-With': 'XMLHttpRequest' } }
        );
        const data = await res.json();

        if (fullLoad) {
            renderAllMessages(data.messages, lang);
        } else if (data.messages.length > 0) {
            appendMessages(data.messages, lang);
        }

        if (data.messages.length > 0) {
            lastMsgId = data.messages[data.messages.length - 1].id;
        }
    } catch (e) {
        console.error('Poll error:', e);
    }
}

function renderAllMessages(messages, lang) {
    const container = document.getElementById('chatMessages');
    if (messages.length === 0) {
        container.innerHTML =
            '<div class="find-friend-msg">No messages yet. Say hello! 👋</div>';
        return;
    }
    container.innerHTML = '';
    messages.forEach(msg => container.appendChild(buildMsgEl(msg, lang)));
    container.scrollTop = container.scrollHeight;
    if (messages.length) lastMsgId = messages[messages.length - 1].id;
}

function appendMessages(messages, lang) {
    const container   = document.getElementById('chatMessages');
    const wasAtBottom = container.scrollHeight - container.scrollTop - container.clientHeight < 80;
    messages.forEach(msg => container.appendChild(buildMsgEl(msg, lang)));
    if (wasAtBottom) container.scrollTop = container.scrollHeight;
}

function buildMsgEl(msg, lang) {
    const row = document.createElement('div');
    row.classList.add('msg-row');
    if (msg.is_mine) row.classList.add('mine');
    row.dataset.msgId = msg.id;

    const bubbleClass = msg.is_deleted ? 'msg-bubble deleted' : 'msg-bubble';

    // Build display text HTML
    let textHtml = '';
    if (msg.is_deleted) {
        textHtml = '🚫 This message was deleted.';
    } else if (Array.isArray(msg.display_text)) {
        msg.display_text.forEach((part, i) => {
            if (i > 0) {
                textHtml += `
                    <div class="translation-divider">
                        <div class="translation-divider-line"></div>
                        <div class="translation-divider-label">${getLangLabel(lang)}</div>
                        <div class="translation-divider-line"></div>
                    </div>
                    <div class="translated-text">${escHtml(part.text)}</div>`;
            } else {
                textHtml += `<div>${escHtml(part.text)}</div>`;
            }
        });
    } else {
        textHtml = escHtml(msg.display_text || '');
    }

    // Action buttons (only for own messages that aren't deleted)
    const actionsHtml = (msg.is_mine && !msg.is_deleted) ? `
        <div class="msg-actions">
            <button class="msg-action-btn" onclick="openEdit(${msg.id}, ${JSON.stringify(msg.message || '')})" title="Edit">✏️</button>
            <button class="msg-action-btn delete" onclick="confirmDelete(${msg.id})" title="Delete">🗑️</button>
        </div>` : '';

    const editedTag = (msg.is_edited && !msg.is_deleted) ? '<span class="edited-tag">edited</span>' : '';

    const defaultAvatar = '{{ asset('profilepics/user_avatar.png') }}';

    row.innerHTML = `
        <div class="msg-avatar">
            <img src="${escHtml(msg.sender_avatar)}" alt="" onerror="this.src='${defaultAvatar}'">
        </div>
        <div class="msg-body">
            ${!msg.is_mine ? `<div class="msg-sender-name">${escHtml(msg.sender_name)}</div>` : ''}
            <div class="${bubbleClass}">
                ${actionsHtml}
                ${textHtml}
            </div>
            <div class="msg-meta">
                <span class="msg-time">${msg.time}</span>
                ${editedTag}
            </div>
        </div>`;

    return row;
}

function getLangLabel(code) {
    const labels = {
        ta: 'Tamil', hi: 'Hindi', fr: 'French', es: 'Spanish', de: 'German',
        zh: 'Chinese', ja: 'Japanese', ko: 'Korean', ar: 'Arabic', pt: 'Portuguese',
        ru: 'Russian', it: 'Italian', nl: 'Dutch', tr: 'Turkish', vi: 'Vietnamese',
        th: 'Thai', pl: 'Polish', sv: 'Swedish', ml: 'Malayalam', te: 'Telugu',
        kn: 'Kannada', bn: 'Bengali', mr: 'Marathi', ur: 'Urdu'
    };
    return labels[code] || code.toUpperCase();
}

// =====================================================================
// SEND MESSAGE
// =====================================================================
async function sendMessage() {
    if (!activeFriendId) return;

    const input   = document.getElementById('chatInput');
    const message = input.value.trim();
    if (!message) return;

    const sendBtn    = document.getElementById('sendBtn');
    const senderLang = document.getElementById('languageSelect').value;

    sendBtn.disabled     = true;
    sendBtn.innerHTML    = '<div class="spinner"></div>';
    input.value          = '';
    input.style.height   = 'auto';

    try {
        const res = await fetch('/community/messages/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                receiver_id   : activeFriendId,
                message       : message,
                sender_lang   : senderLang,
                receiver_lang : senderLang
            })
        });

        const data = await res.json();
        if (!data.success) {
            showToast(data.message || 'Failed to send message.', 'error');
        } else {
            await loadMessages(false);
        }
    } catch (e) {
        showToast('Network error. Please try again.', 'error');
    } finally {
        sendBtn.disabled  = false;
        sendBtn.innerHTML = '➤';
    }
}

function handleInputKeydown(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
}

function autoResize(el) {
    el.style.height = 'auto';
    el.style.height = Math.min(el.scrollHeight, 120) + 'px';
}

// =====================================================================
// EDIT MESSAGE
// =====================================================================
function openEdit(msgId, currentText) {
    editingMsgId = msgId;
    document.getElementById('editMsgText').value = currentText;
    openModal('editMsgModal');
}

async function saveEdit() {
    if (!editingMsgId) return;

    const newText    = document.getElementById('editMsgText').value.trim();
    if (!newText)    { showToast('Message cannot be empty.', 'error'); return; }

    const senderLang = document.getElementById('languageSelect').value;

    try {
        const res = await fetch(`/community/messages/${editingMsgId}/edit`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ message: newText, sender_lang: senderLang })
        });

        const data = await res.json();
        if (data.success) {
            showToast('Message updated ✅', 'success');
            closeModal('editMsgModal');
            lastMsgId = 0;
            await loadMessages(true);
        } else {
            showToast(data.message || 'Failed to edit.', 'error');
        }
    } catch (e) {
        showToast('Network error.', 'error');
    }
}

// =====================================================================
// DELETE MESSAGE
// =====================================================================
async function confirmDelete(msgId) {
    if (!confirm('Delete this message? This cannot be undone.')) return;

    try {
        const res = await fetch(`/community/messages/${msgId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const data = await res.json();
        if (data.success) {
            showToast('Message deleted.', 'info');
            lastMsgId = 0;
            await loadMessages(true);
        } else {
            showToast('Failed to delete.', 'error');
        }
    } catch (e) {
        showToast('Network error.', 'error');
    }
}

// =====================================================================
// FIND FRIEND MODAL
// =====================================================================
document.getElementById('btnOpenFindFriend').addEventListener('click', () => {
    document.getElementById('findFriendEmail').value = '';
    document.getElementById('findFriendResult').innerHTML = '';
    openModal('findFriendModal');
    setTimeout(() => document.getElementById('findFriendEmail').focus(), 200);
});

async function searchFriend() {
    const email   = document.getElementById('findFriendEmail').value.trim();
    const btn     = document.getElementById('btnSearch');
    const result  = document.getElementById('findFriendResult');

    if (!email) {
        result.innerHTML = '<div class="find-friend-msg error">Please enter an email address.</div>';
        return;
    }

    btn.disabled    = true;
    btn.textContent = '…';
    result.innerHTML = '<div class="spinner" style="margin:16px auto;"></div>';

    try {
        const res  = await fetch(`/community/find-friend?email=${encodeURIComponent(email)}`,
            { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const data = await res.json();

        if (!data.found) {
            result.innerHTML = `<div class="find-friend-msg error">⚠️ ${escHtml(data.message)}</div>`;
            return;
        }

        let actionHtml;
        if (data.already_friend) {
            actionHtml = `<button class="btn-add-friend already" disabled>✅ Friends</button>`;
        } else if (data.request_sent) {
            actionHtml = `<button class="btn-add-friend already" disabled>📨 Request Sent</button>`;
        } else if (data.request_received) {
            actionHtml = `<button class="btn-add-friend send" onclick="acceptFromSearch(${data.request_received})">✅ Accept Request</button>`;
        } else {
            actionHtml = `<button class="btn-add-friend send" onclick="sendFriendReq(${data.id}, this)">➕ Add Friend</button>`;
        }

        const defaultAvatar = '{{ asset('profilepics/user_avatar.png') }}';
        result.innerHTML = `
            <div class="find-friend-result">
                <img src="${escHtml(data.avatar)}" alt="" onerror="this.src='${defaultAvatar}'">
                <div class="find-friend-result-info">
                    <div class="find-friend-result-name">${escHtml(data.name)}</div>
                    <div class="find-friend-result-email">${escHtml(data.email)}</div>
                </div>
                ${actionHtml}
            </div>`;

    } catch (e) {
        result.innerHTML = '<div class="find-friend-msg error">Network error. Please try again.</div>';
    } finally {
        btn.disabled    = false;
        btn.textContent = 'Search';
    }
}

async function sendFriendReq(receiverId, btn) {
    btn.disabled    = true;
    btn.textContent = 'Sending…';

    try {
        const res  = await fetch('/community/friend-request', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ receiver_id: receiverId })
        });
        const data = await res.json();
        if (data.success) {
            btn.textContent           = '📨 Request Sent';
            btn.className             = 'btn-add-friend already';
            showToast('Friend request sent! 🎉', 'success');
        } else {
            showToast(data.message || 'Failed.', 'error');
            btn.disabled    = false;
            btn.textContent = '➕ Add Friend';
        }
    } catch(e) {
        showToast('Network error.', 'error');
        btn.disabled = false;
        btn.textContent = '➕ Add Friend';
    }
}

async function acceptFromSearch(requestId) {
    await respondRequest(requestId, 'accept');
    closeModal('findFriendModal');
    location.reload();
}

// =====================================================================
// PENDING REQUESTS MODAL
// =====================================================================
document.getElementById('btnOpenPending').addEventListener('click', async () => {
    openModal('pendingModal');
    await loadPendingRequests();
});

async function loadPendingRequests() {
    const list = document.getElementById('pendingList');
    list.innerHTML = '<div class="spinner" style="margin:20px auto;"></div>';

    try {
        const res  = await fetch('/community/pending-requests',
            { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const data = await res.json();

        // Update badge
        const badge = document.getElementById('pendingBadge');
        if (data.count > 0) {
            badge.textContent = data.count;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }

        if (data.requests.length === 0) {
            list.innerHTML = '<div class="find-friend-msg">No pending friend requests. 🎉</div>';
            return;
        }

        const defaultAvatar = '{{ asset('profilepics/user_avatar.png') }}';
        list.innerHTML = '';
        data.requests.forEach(req => {
            const el = document.createElement('div');
            el.className = 'pending-request-item';
            el.id = `preq-${req.id}`;
            el.innerHTML = `
                <img src="${escHtml(req.avatar)}" alt="" onerror="this.src='${defaultAvatar}'">
                <div class="pending-request-info">
                    <div class="pending-request-name">${escHtml(req.name)}</div>
                    <div class="pending-request-email">${escHtml(req.email)}</div>
                </div>
                <div class="pending-request-actions">
                    <button class="btn-accept" onclick="respondRequest(${req.id}, 'accept', this)">✅ Accept</button>
                    <button class="btn-reject" onclick="respondRequest(${req.id}, 'reject', this)">✕ Reject</button>
                </div>`;
            list.appendChild(el);
        });
    } catch (e) {
        list.innerHTML = '<div class="find-friend-msg error">Failed to load requests.</div>';
    }
}

async function respondRequest(requestId, action, btn) {
    if (btn) btn.disabled = true;

    try {
        const res  = await fetch('/community/friend-request/respond', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ request_id: requestId, action })
        });
        const data = await res.json();
        if (data.success) {
            showToast(data.message, action === 'accept' ? 'success' : 'info');
            document.getElementById(`preq-${requestId}`)?.remove();
            if (action === 'accept') {
                setTimeout(() => location.reload(), 1000);
            }
        } else {
            showToast(data.message || 'Failed.', 'error');
            if (btn) btn.disabled = false;
        }
    } catch(e) {
        showToast('Network error.', 'error');
        if (btn) btn.disabled = false;
    }
}

// =====================================================================
// MODAL HELPERS
// =====================================================================
function openModal(id) {
    document.getElementById(id).classList.add('active');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}

document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) closeModal(this.id);
    });
});

// =====================================================================
// TOAST
// =====================================================================
function showToast(msg, type = 'info') {
    const container = document.getElementById('toastContainer');
    const el        = document.createElement('div');
    el.className    = `toast ${type}`;
    el.textContent  = msg;
    container.appendChild(el);
    setTimeout(() => el.remove(), 3200);
}

// =====================================================================
// UTILS
// =====================================================================
function escHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}
</script>
@endpush

</x-app-layout>
