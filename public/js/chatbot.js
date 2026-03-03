/**
 * Global Trade Fairs - AI Chatbot
 * Multi-language edition — v3.0
 */

/* =========================================
   LANGUAGE LIST (50+ world languages)
   ========================================= */
const CHATBOT_LANGUAGES = [
    { code: 'af', name: 'Afrikaans', native: 'Afrikaans', flag: '🇿🇦' },
    { code: 'sq', name: 'Albanian', native: 'Shqip', flag: '🇦🇱' },
    { code: 'am', name: 'Amharic', native: 'አማርኛ', flag: '🇪🇹' },
    { code: 'ar', name: 'Arabic', native: 'العربية', flag: '🇸🇦' },
    { code: 'hy', name: 'Armenian', native: 'Հայերեն', flag: '🇦🇲' },
    { code: 'az', name: 'Azerbaijani', native: 'Azərbaycan', flag: '🇦🇿' },
    { code: 'eu', name: 'Basque', native: 'Euskara', flag: '🇪🇸' },
    { code: 'be', name: 'Belarusian', native: 'Беларуская', flag: '🇧🇾' },
    { code: 'bn', name: 'Bengali', native: 'বাংলা', flag: '🇧🇩' },
    { code: 'bs', name: 'Bosnian', native: 'Bosanski', flag: '🇧🇦' },
    { code: 'bg', name: 'Bulgarian', native: 'Български', flag: '🇧🇬' },
    { code: 'ca', name: 'Catalan', native: 'Català', flag: '🏴󠁥󠁳󠁣󠁴󠁿' },
    { code: 'zh-CN', name: 'Chinese (Simplified)', native: '中文 (简体)', flag: '🇨🇳' },
    { code: 'zh-TW', name: 'Chinese (Traditional)', native: '中文 (繁體)', flag: '🇹🇼' },
    { code: 'hr', name: 'Croatian', native: 'Hrvatski', flag: '🇭🇷' },
    { code: 'cs', name: 'Czech', native: 'Čeština', flag: '🇨🇿' },
    { code: 'da', name: 'Danish', native: 'Dansk', flag: '🇩🇰' },
    { code: 'nl', name: 'Dutch', native: 'Nederlands', flag: '🇳🇱' },
    { code: 'en', name: 'English', native: 'English', flag: '🇬🇧' },
    { code: 'et', name: 'Estonian', native: 'Eesti', flag: '🇪🇪' },
    { code: 'fil', name: 'Filipino', native: 'Filipino', flag: '🇵🇭' },
    { code: 'fi', name: 'Finnish', native: 'Suomi', flag: '🇫🇮' },
    { code: 'fr', name: 'French', native: 'Français', flag: '🇫🇷' },
    { code: 'gl', name: 'Galician', native: 'Galego', flag: '🇪🇸' },
    { code: 'ka', name: 'Georgian', native: 'ქართული', flag: '🇬🇪' },
    { code: 'de', name: 'German', native: 'Deutsch', flag: '🇩🇪' },
    { code: 'el', name: 'Greek', native: 'Ελληνικά', flag: '🇬🇷' },
    { code: 'gu', name: 'Gujarati', native: 'ગુજરાતી', flag: '🇮🇳' },
    { code: 'ht', name: 'Haitian Creole', native: 'Kreyòl Ayisyen', flag: '🇭🇹' },
    { code: 'ha', name: 'Hausa', native: 'Hausa', flag: '🇳🇬' },
    { code: 'he', name: 'Hebrew', native: 'עברית', flag: '🇮🇱' },
    { code: 'hi', name: 'Hindi', native: 'हिन्दी', flag: '🇮🇳' },
    { code: 'hu', name: 'Hungarian', native: 'Magyar', flag: '🇭🇺' },
    { code: 'is', name: 'Icelandic', native: 'Íslenska', flag: '🇮🇸' },
    { code: 'ig', name: 'Igbo', native: 'Igbo', flag: '🇳🇬' },
    { code: 'id', name: 'Indonesian', native: 'Bahasa Indonesia', flag: '🇮🇩' },
    { code: 'ga', name: 'Irish', native: 'Gaeilge', flag: '🇮🇪' },
    { code: 'it', name: 'Italian', native: 'Italiano', flag: '🇮🇹' },
    { code: 'ja', name: 'Japanese', native: '日本語', flag: '🇯🇵' },
    { code: 'kn', name: 'Kannada', native: 'ಕನ್ನಡ', flag: '🇮🇳' },
    { code: 'kk', name: 'Kazakh', native: 'Қазақша', flag: '🇰🇿' },
    { code: 'km', name: 'Khmer', native: 'ខ្មែរ', flag: '🇰🇭' },
    { code: 'ko', name: 'Korean', native: '한국어', flag: '🇰🇷' },
    { code: 'ku', name: 'Kurdish', native: 'Kurdî', flag: '🏴' },
    { code: 'lo', name: 'Lao', native: 'ລາວ', flag: '🇱🇦' },
    { code: 'lv', name: 'Latvian', native: 'Latviešu', flag: '🇱🇻' },
    { code: 'lt', name: 'Lithuanian', native: 'Lietuvių', flag: '🇱🇹' },
    { code: 'lb', name: 'Luxembourgish', native: 'Lëtzebuergesch', flag: '🇱🇺' },
    { code: 'mk', name: 'Macedonian', native: 'Македонски', flag: '🇲🇰' },
    { code: 'mg', name: 'Malagasy', native: 'Malagasy', flag: '🇲🇬' },
    { code: 'ms', name: 'Malay', native: 'Bahasa Melayu', flag: '🇲🇾' },
    { code: 'ml', name: 'Malayalam', native: 'മലയാളം', flag: '🇮🇳' },
    { code: 'mt', name: 'Maltese', native: 'Malti', flag: '🇲🇹' },
    { code: 'mr', name: 'Marathi', native: 'मराठी', flag: '🇮🇳' },
    { code: 'mn', name: 'Mongolian', native: 'Монгол', flag: '🇲🇳' },
    { code: 'my', name: 'Myanmar (Burmese)', native: 'ဗမာ', flag: '🇲🇲' },
    { code: 'ne', name: 'Nepali', native: 'नेपाली', flag: '🇳🇵' },
    { code: 'no', name: 'Norwegian', native: 'Norsk', flag: '🇳🇴' },
    { code: 'ny', name: 'Nyanja (Chichewa)', native: 'Nyanja', flag: '🇲🇼' },
    { code: 'or', name: 'Odia (Oriya)', native: 'ଓଡ଼ିଆ', flag: '🇮🇳' },
    { code: 'ps', name: 'Pashto', native: 'پښتو', flag: '🇦🇫' },
    { code: 'fa', name: 'Persian', native: 'فارسی', flag: '🇮🇷' },
    { code: 'pl', name: 'Polish', native: 'Polski', flag: '🇵🇱' },
    { code: 'pt', name: 'Portuguese', native: 'Português', flag: '🇵🇹' },
    { code: 'pa', name: 'Punjabi', native: 'ਪੰਜਾਬੀ', flag: '🇮🇳' },
    { code: 'ro', name: 'Romanian', native: 'Română', flag: '🇷🇴' },
    { code: 'ru', name: 'Russian', native: 'Русский', flag: '🇷🇺' },
    { code: 'sm', name: 'Samoan', native: 'Samoan', flag: '🇼🇸' },
    { code: 'gd', name: 'Scottish Gaelic', native: 'Gàidhlig', flag: '🏴󠁧󠁢󠁳󠁣󠁴󠁿' },
    { code: 'sr', name: 'Serbian', native: 'Српски', flag: '🇷🇸' },
    { code: 'st', name: 'Sesotho', native: 'Sesotho', flag: '🇱🇸' },
    { code: 'sn', name: 'Shona', native: 'Shona', flag: '🇿🇼' },
    { code: 'sd', name: 'Sindhi', native: 'سنڌي', flag: '🇵🇰' },
    { code: 'si', name: 'Sinhala', native: 'සිංහල', flag: '🇱🇰' },
    { code: 'sk', name: 'Slovak', native: 'Slovenčina', flag: '🇸🇰' },
    { code: 'sl', name: 'Slovenian', native: 'Slovenščina', flag: '🇸🇮' },
    { code: 'so', name: 'Somali', native: 'Soomaali', flag: '🇸🇴' },
    { code: 'es', name: 'Spanish', native: 'Español', flag: '🇪🇸' },
    { code: 'su', name: 'Sundanese', native: 'Basa Sunda', flag: '🇮🇩' },
    { code: 'sw', name: 'Swahili', native: 'Kiswahili', flag: '🇰🇪' },
    { code: 'sv', name: 'Swedish', native: 'Svenska', flag: '🇸🇪' },
    { code: 'tg', name: 'Tajik', native: 'Тоҷикӣ', flag: '🇹🇯' },
    { code: 'ta', name: 'Tamil', native: 'தமிழ்', flag: '🇮🇳' },
    { code: 'tt', name: 'Tatar', native: 'Татарча', flag: '🇷🇺' },
    { code: 'te', name: 'Telugu', native: 'తెలుగు', flag: '🇮🇳' },
    { code: 'th', name: 'Thai', native: 'ภาษาไทย', flag: '🇹🇭' },
    { code: 'tr', name: 'Turkish', native: 'Türkçe', flag: '🇹🇷' },
    { code: 'tk', name: 'Turkmen', native: 'Türkmençe', flag: '🇹🇲' },
    { code: 'uk', name: 'Ukrainian', native: 'Українська', flag: '🇺🇦' },
    { code: 'ur', name: 'Urdu', native: 'اردو', flag: '🇵🇰' },
    { code: 'ug', name: 'Uyghur', native: 'ئۇيغۇرچە', flag: '🇨🇳' },
    { code: 'uz', name: 'Uzbek', native: "O'zbek", flag: '🇺🇿' },
    { code: 'vi', name: 'Vietnamese', native: 'Tiếng Việt', flag: '🇻🇳' },
    { code: 'cy', name: 'Welsh', native: 'Cymraeg', flag: '🏴󠁧󠁢󠁷󠁬󠁳󠁿' },
    { code: 'xh', name: 'Xhosa', native: 'isiXhosa', flag: '🇿🇦' },
    { code: 'yi', name: 'Yiddish', native: 'ייִדיש', flag: '🇮🇱' },
    { code: 'yo', name: 'Yoruba', native: 'Yorùbá', flag: '🇳🇬' },
    { code: 'zu', name: 'Zulu', native: 'isiZulu', flag: '🇿🇦' },
];

class Chatbot {
    constructor() {
        this.sessionId = this.getOrCreateSessionId();
        this.selectedLanguage = null; // { code, name, native, flag }
        this.isOpen = false;
        this.isTyping = false;
        this.messages = [];
        this.filteredLangs = [...CHATBOT_LANGUAGES];
        this.init();
    }

    /* =========================================
       INIT
       ========================================= */
    init() {
        this.createChatbotHTML();
        this.attachEventListeners();
        this.renderLanguageList(CHATBOT_LANGUAGES);
        this.loadSavedLanguage();
    }

    /**
     * Create chatbot HTML structure (only if not already in Blade)
     */
    createChatbotHTML() {
        if (document.getElementById('chatbot-toggle')) return;

        const chatbotHTML = `
            <button class="chatbot-button" id="chatbot-toggle">💬</button>
            <div class="chatbot-container" id="chatbot-container">
                <div class="chatbot-header">
                    <div class="chatbot-header-title">
                        <div class="chatbot-avatar-header">
                            <img src="${window.chatbotConfig?.botLogo || '/images/email-logo.png'}" alt="Bot" style="width:30px;height:30px;border-radius:50%;object-fit:cover;">
                        </div>
                        <div>
                            <h3>GTF Assistant</h3>
                            <div style="display:flex;align-items:center;gap:6px;font-size:12px;opacity:0.9;">
                                <span class="status"></span><span>Online</span>
                            </div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:6px;">
                        <span id="lang-badge" class="lang-badge" style="display:none;"></span>
                        <button class="chatbot-lang-btn" id="chatbot-change-lang" title="Change language" style="display:none;">🌐</button>
                        <button class="chatbot-close" id="chatbot-close">×</button>
                    </div>
                </div>
                <div id="lang-select-screen" class="lang-select-screen">
                    <div class="lang-select-inner">
                        <div class="lang-select-icon">🌐</div>
                        <h4>Choose Your Language</h4>
                        <p>Select the language for chatbot responses</p>
                        <input type="text" id="lang-search" class="lang-search-input" placeholder="🔍 Search language..." autocomplete="off">
                        <div class="lang-list-wrapper"><div id="lang-list" class="lang-list"></div></div>
                        <div id="lang-selected-preview" class="lang-selected-preview"></div>
                        <button id="lang-confirm-btn" class="lang-confirm-btn" disabled>Start Chat →</button>
                    </div>
                </div>
                <div class="chatbot-messages" id="chatbot-messages" style="display:none;"></div>
                <div class="quick-actions" id="quick-actions" style="display:none;"></div>
                <div class="chatbot-input" id="chatbot-input-area" style="display:none;">
                    <input type="text" id="chatbot-input" placeholder="Type your message..." autocomplete="off">
                    <button class="chatbot-send-btn" id="chatbot-send">➤</button>
                </div>
            </div>`;
        document.body.insertAdjacentHTML('beforeend', chatbotHTML);
    }

    /* =========================================
       LANGUAGE SCREEN
       ========================================= */

    /**
     * Render the list of languages (optionally filtered)
     */
    renderLanguageList(langs) {
        const list = document.getElementById('lang-list');
        if (!list) return;

        if (langs.length === 0) {
            list.innerHTML = `<div style="padding:14px;text-align:center;color:#94a3b8;font-size:13px;">No languages found</div>`;
            return;
        }

        list.innerHTML = langs.map(l => {
            const isSelected = this.selectedLanguage?.code === l.code;
            return `<div class="lang-item ${isSelected ? 'selected' : ''}" data-code="${l.code}">
                <span class="lang-flag">${l.flag}</span>
                <span class="lang-name">${l.name}</span>
                <span class="lang-native">${l.native}</span>
                ${isSelected ? '<span class="lang-tick">✓</span>' : ''}
            </div>`;
        }).join('');

        list.querySelectorAll('.lang-item').forEach(item => {
            item.addEventListener('click', () => {
                const code = item.getAttribute('data-code');
                const lang = CHATBOT_LANGUAGES.find(l => l.code === code);
                if (lang) this.selectLanguage(lang);
            });
        });
    }

    selectLanguage(lang) {
        this.selectedLanguage = lang;

        // Highlight selected
        document.querySelectorAll('.lang-item').forEach(el => {
            const isSel = el.getAttribute('data-code') === lang.code;
            el.classList.toggle('selected', isSel);
            const tick = el.querySelector('.lang-tick');
            if (isSel && !tick) {
                el.insertAdjacentHTML('beforeend', '<span class="lang-tick">✓</span>');
            } else if (!isSel && tick) {
                tick.remove();
            }
        });

        // Show preview
        const preview = document.getElementById('lang-selected-preview');
        if (preview) {
            preview.style.display = 'block';
            preview.textContent = `${lang.flag} ${lang.name} selected`;
        }

        // Enable confirm
        const btn = document.getElementById('lang-confirm-btn');
        if (btn) btn.disabled = false;
    }

    confirmLanguage() {
        if (!this.selectedLanguage) return;

        // Save to localStorage
        localStorage.setItem('chatbot_language', JSON.stringify(this.selectedLanguage));

        // Switch screens
        this.showChatScreen();
    }

    showChatScreen() {
        const lang = this.selectedLanguage;
        if (!lang) return;

        // Hide language screen
        const langScreen = document.getElementById('lang-select-screen');
        if (langScreen) langScreen.style.display = 'none';

        // Show chat elements
        const msgs = document.getElementById('chatbot-messages');
        const quick = document.getElementById('quick-actions');
        const inputArea = document.getElementById('chatbot-input-area');
        if (msgs) msgs.style.display = '';
        if (quick) quick.style.display = '';
        if (inputArea) inputArea.style.display = '';

        // Update header badge
        const badge = document.getElementById('lang-badge');
        if (badge) {
            badge.textContent = `${lang.flag} ${lang.name}`;
            badge.style.display = '';
        }

        // Show change-lang button
        const changeLangBtn = document.getElementById('chatbot-change-lang');
        if (changeLangBtn) changeLangBtn.style.display = '';

        // Show welcome + quick actions (only on first load)
        if (!this._chatStarted) {
            this._chatStarted = true;
            this.showWelcomeMessage();
            this.loadQuickActions();
        }

        setTimeout(() => {
            const input = document.getElementById('chatbot-input');
            if (input) input.focus();
        }, 100);
    }

    showLanguageScreen() {
        // Show lang screen, hide chat elements
        const langScreen = document.getElementById('lang-select-screen');
        const msgs = document.getElementById('chatbot-messages');
        const quick = document.getElementById('quick-actions');
        const inputArea = document.getElementById('chatbot-input-area');

        if (langScreen) langScreen.style.display = '';
        if (msgs) msgs.style.display = 'none';
        if (quick) quick.style.display = 'none';
        if (inputArea) inputArea.style.display = 'none';

        // Hide badge & change button
        const badge = document.getElementById('lang-badge');
        const changeLangBtn = document.getElementById('chatbot-change-lang');
        if (badge) badge.style.display = 'none';
        if (changeLangBtn) changeLangBtn.style.display = 'none';

        // Re-render with current selection highlighted
        this.renderLanguageList(CHATBOT_LANGUAGES);
        const search = document.getElementById('lang-search');
        if (search) search.value = '';
    }

    loadSavedLanguage() {
        try {
            const saved = localStorage.getItem('chatbot_language');
            if (saved) {
                const lang = JSON.parse(saved);
                // Verify it exists in our list
                if (CHATBOT_LANGUAGES.find(l => l.code === lang.code)) {
                    this.selectedLanguage = lang;
                }
            }
        } catch (e) { /* ignore */ }
    }

    /* =========================================
       EVENT LISTENERS
       ========================================= */
    attachEventListeners() {
        const toggleBtn = document.getElementById('chatbot-toggle');
        const closeBtn = document.getElementById('chatbot-close');
        const sendBtn = document.getElementById('chatbot-send');
        const input = document.getElementById('chatbot-input');
        const langSearch = document.getElementById('lang-search');
        const langConfirmBtn = document.getElementById('lang-confirm-btn');
        const changeLangBtn = document.getElementById('chatbot-change-lang');

        if (toggleBtn) toggleBtn.addEventListener('click', () => this.toggleChat());
        if (closeBtn) closeBtn.addEventListener('click', () => this.closeChat());
        if (sendBtn) sendBtn.addEventListener('click', () => this.sendMessage());
        if (changeLangBtn) changeLangBtn.addEventListener('click', () => this.showLanguageScreen());
        if (langConfirmBtn) langConfirmBtn.addEventListener('click', () => this.confirmLanguage());

        if (input) {
            input.addEventListener('keypress', e => {
                if (e.key === 'Enter') this.sendMessage();
            });
        }

        if (langSearch) {
            langSearch.addEventListener('input', e => {
                const q = e.target.value.trim().toLowerCase();
                const filtered = q
                    ? CHATBOT_LANGUAGES.filter(l =>
                        l.name.toLowerCase().includes(q) ||
                        l.native.toLowerCase().includes(q))
                    : CHATBOT_LANGUAGES;
                this.renderLanguageList(filtered);
            });
        }
    }

    /* =========================================
       TOGGLE / OPEN / CLOSE
       ========================================= */
    toggleChat() {
        this.isOpen = !this.isOpen;
        const container = document.getElementById('chatbot-container');
        const button = document.getElementById('chatbot-toggle');

        if (this.isOpen) {
            container.classList.add('active');
            button.classList.add('active');
            button.textContent = '✕';

            // Decide which screen to show
            if (this.selectedLanguage) {
                // Language already chosen — go straight to chat
                this.showChatScreen();
            } else {
                // First time — show language picker
                this.showLanguageScreen();
            }
        } else {
            container.classList.remove('active');
            button.classList.remove('active');
            button.textContent = '💬';
        }
    }

    closeChat() {
        this.isOpen = false;
        document.getElementById('chatbot-container').classList.remove('active');
        const btn = document.getElementById('chatbot-toggle');
        btn.classList.remove('active');
        btn.textContent = '💬';
    }

    /* =========================================
       WELCOME MESSAGE
       ========================================= */
    showWelcomeMessage() {
        const messagesContainer = document.getElementById('chatbot-messages');
        const langNote = this.selectedLanguage && this.selectedLanguage.code !== 'en'
            ? `<br><small style="opacity:0.7;">🌐 Responding in <b>${this.selectedLanguage.name}</b> + English</small>`
            : '';

        const welcomeHTML = `
            <div class="welcome-message">
                <h4>👋 Welcome to Global Trade Fairs!</h4>
                <p>I'm your AI assistant. I can help you with information about trade fairs, packages, registration, and more.${langNote}</p>
            </div>`;
        messagesContainer.innerHTML = welcomeHTML;
    }

    /* =========================================
       QUICK ACTIONS
       ========================================= */
    async loadQuickActions() {
        try {
            const response = await fetch('/chatbot/quick-actions');
            const data = await response.json();
            if (data.success) this.renderQuickActions(data.actions);
        } catch (error) {
            console.error('Failed to load quick actions:', error);
        }
    }

    renderQuickActions(actions) {
        const container = document.getElementById('quick-actions');
        if (!container) return;
        container.innerHTML = actions.map(action =>
            `<button class="quick-action-btn" data-message="${action.message}">${action.text}</button>`
        ).join('');

        container.querySelectorAll('.quick-action-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('chatbot-input').value = btn.getAttribute('data-message');
                this.sendMessage();
            });
        });
    }

    /* =========================================
       SEND MESSAGE
       ========================================= */
    async sendMessage() {
        const input = document.getElementById('chatbot-input');
        const message = input.value.trim();
        if (!message || this.isTyping) return;

        input.value = '';
        this.addMessage('user', message);
        this.showTyping();

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            const response = await fetch('/chatbot/message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    message: message,
                    session_id: this.sessionId,
                    selected_language: this.selectedLanguage?.name || 'English',
                }),
            });

            const data = await response.json();
            this.hideTyping();

            if (data.success) {
                this.addMessage('bot', data.message);
            } else {
                if (data.action === 'login_required') {
                    const loginMsg = `${data.message}<br><br><a href="/login" class="chatbot-login-btn" style="background:#2563eb;color:white;padding:5px 10px;border-radius:4px;text-decoration:none;font-size:12px;display:inline-block;">Login to Continue</a>`;
                    this.addMessage('bot', loginMsg);
                } else {
                    this.addMessage('bot', data.message || 'Sorry, I encountered an error. Please try again.');
                }
            }
        } catch (error) {
            this.hideTyping();
            this.addMessage('bot', "Sorry, I'm having trouble connecting. Please try again later.");
            console.error('Chatbot error:', error);
        }
    }

    /* =========================================
       ADD MESSAGE TO UI
       ========================================= */
    addMessage(role, text) {
        const messagesContainer = document.getElementById('chatbot-messages');
        const time = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

        const messageHTML = `
            <div class="message ${role}">
                <div class="message-avatar">${this.getAvatarHTML(role)}</div>
                <div>
                    <div class="message-bubble">${this.formatMessage(text)}</div>
                    <div class="message-time">${time}</div>
                </div>
            </div>`;

        // Remove welcome message on first user message
        const welcomeMsg = messagesContainer.querySelector('.welcome-message');
        if (welcomeMsg && role === 'user') welcomeMsg.remove();

        messagesContainer.insertAdjacentHTML('beforeend', messageHTML);
        this.scrollToBottom();
    }

    getAvatarHTML(role) {
        if (role === 'bot') {
            const logo = window.chatbotConfig?.botLogo || '/images/email-logo.png';
            return `<img src="${logo}" onerror="this.src='/images/email-logo.png'" alt="Bot" style="width:24px;height:24px;border-radius:50%;object-fit:cover;">`;
        }
        const avatar = window.chatbotConfig?.userAvatar;
        if (avatar) {
            return `<img src="${avatar}" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'" alt="User" style="width:24px;height:24px;border-radius:50%;object-fit:cover;"> <div style="display:none;width:24px;height:24px;border-radius:50%;background:#ddd;align-items:center;justify-content:center;">👤</div>`;
        }
        return '<div style="width:24px;height:24px;border-radius:50%;background:#ddd;display:flex;align-items:center;justify-content:center;">👤</div>';
    }

    /* =========================================
       FORMAT MESSAGE (markdown-lite + dual-lang divider)
       ========================================= */
    formatMessage(text) {
        // Detect dual-language separator inserted by AI
        // AI is instructed to use: "---\n🇬🇧 English:" or "--- English ---"
        const dividerPatterns = [
            /\n---\s*\n🇬🇧\s*English\s*:?\s*\n/i,
            /\n---\s*English\s*---\s*\n/i,
            /\n\[English\]\s*\n/i,
            /\n─{3,}\s*\n🇬🇧/i,
        ];

        let parts = null;
        for (const pattern of dividerPatterns) {
            const idx = text.search(pattern);
            if (idx !== -1) {
                const match = text.match(pattern);
                const sep = match[0];
                parts = [text.substring(0, idx), text.substring(idx + sep.length)];
                break;
            }
        }

        if (parts) {
            const langPart = this.applyMarkdown(parts[0].trim());
            const englishPart = this.applyMarkdown(parts[1].trim());
            const divider = `<div class="lang-divider">
                <div class="lang-divider-line"></div>
                <span class="lang-divider-label">🇬🇧 English</span>
                <div class="lang-divider-line"></div>
            </div>`;
            return `${langPart}${divider}${englishPart}`;
        }

        return this.applyMarkdown(text);
    }

    applyMarkdown(text) {
        text = text.replace(/\*\*(.*?)\*\*/g, '<b>$1</b>');
        text = text.replace(/\*(.*?)\*/g, '<i>$1</i>');
        text = text.replace(/^\s*[-*]\s+(.*)$/gm, '• $1');
        text = text.replace(/\n/g, '<br>');
        text = text.replace(/(https?:\/\/[^\s]+)/g,
            '<a href="$1" target="_blank" style="color:inherit;text-decoration:underline;">$1</a>');
        return text;
    }

    /* =========================================
       TYPING INDICATOR
       ========================================= */
    showTyping() {
        this.isTyping = true;
        const messagesContainer = document.getElementById('chatbot-messages');
        const typingHTML = `
            <div class="message bot" id="typing-indicator">
                <div class="message-avatar">
                    <img src="${window.chatbotConfig?.botLogo || '/images/email-logo.png'}" alt="Bot" style="width:24px;height:24px;border-radius:50%;object-fit:cover;">
                </div>
                <div class="typing-indicator active">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            </div>`;
        messagesContainer.insertAdjacentHTML('beforeend', typingHTML);
        this.scrollToBottom();
    }

    hideTyping() {
        this.isTyping = false;
        const indicator = document.getElementById('typing-indicator');
        if (indicator) indicator.remove();
    }

    scrollToBottom() {
        const messagesContainer = document.getElementById('chatbot-messages');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    /* =========================================
       SESSION ID
       ========================================= */
    getOrCreateSessionId() {
        let sessionId = localStorage.getItem('chatbot_session_id');
        if (!sessionId) {
            sessionId = this.generateUUID();
            localStorage.setItem('chatbot_session_id', sessionId);
        }
        return sessionId;
    }

    generateUUID() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, c => {
            const r = Math.random() * 16 | 0;
            const v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }
}

// Initialize chatbot when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => new Chatbot());
} else {
    new Chatbot();
}
