/**
 * Global Trade Fairs - AI Chatbot
 * Powered by Google Gemini API
 */

class Chatbot {
    constructor() {
        this.sessionId = this.getOrCreateSessionId();
        this.isOpen = false;
        this.isTyping = false;
        this.messages = [];
        this.init();
    }

    /**
     * Initialize chatbot
     */
    init() {
        this.createChatbotHTML();
        this.attachEventListeners();
        this.loadQuickActions();
        this.showWelcomeMessage();
    }

    /**
     * Create chatbot HTML structure
     */
    createChatbotHTML() {
        const chatbotHTML = `
            <!-- Chatbot Button -->
            <button class="chatbot-button" id="chatbot-toggle">
                💬
            </button>

            <!-- Chatbot Container -->
            <div class="chatbot-container" id="chatbot-container">
                <!-- Header -->
                <div class="chatbot-header">
                    <div class="chatbot-header-title">
                        <div class="chatbot-avatar-header">
                            <img src="${window.chatbotConfig?.botLogo || '/images/email-logo.png'}" alt="Bot" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">
                        </div>
                        <div>
                            <h3>GTF Assistant</h3>
                            <div style="display: flex; align-items: center; gap: 6px; font-size: 12px; opacity: 0.9;">
                                <span class="status"></span>
                                <span>Online</span>
                            </div>
                        </div>
                    </div>
                    <button class="chatbot-close" id="chatbot-close">×</button>
                </div>

                <!-- Messages Area -->
                <div class="chatbot-messages" id="chatbot-messages">
                    <!-- Messages will be added here -->
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions" id="quick-actions">
                    <!-- Quick action buttons will be added here -->
                </div>

                <!-- Input Area -->
                <div class="chatbot-input">
                    <input 
                        type="text" 
                        id="chatbot-input" 
                        placeholder="Type your message..."
                        autocomplete="off"
                    />
                    <button class="chatbot-send-btn" id="chatbot-send">
                        ➤
                    </button>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', chatbotHTML);
    }

    /**
     * Attach event listeners
     */
    attachEventListeners() {
        const toggleBtn = document.getElementById('chatbot-toggle');
        const closeBtn = document.getElementById('chatbot-close');
        const sendBtn = document.getElementById('chatbot-send');
        const input = document.getElementById('chatbot-input');

        toggleBtn.addEventListener('click', () => this.toggleChat());
        closeBtn.addEventListener('click', () => this.closeChat());
        sendBtn.addEventListener('click', () => this.sendMessage());

        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                this.sendMessage();
            }
        });
    }

    /**
     * Toggle chat window
     */
    toggleChat() {
        this.isOpen = !this.isOpen;
        const container = document.getElementById('chatbot-container');
        const button = document.getElementById('chatbot-toggle');

        if (this.isOpen) {
            container.classList.add('active');
            button.classList.add('active');
            button.textContent = '✕';
            document.getElementById('chatbot-input').focus();
        } else {
            container.classList.remove('active');
            button.classList.remove('active');
            button.textContent = '💬';
        }
    }

    /**
     * Close chat window
     */
    closeChat() {
        this.isOpen = false;
        document.getElementById('chatbot-container').classList.remove('active');
        document.getElementById('chatbot-toggle').classList.remove('active');
        document.getElementById('chatbot-toggle').textContent = '💬';
    }

    /**
     * Show welcome message
     */
    showWelcomeMessage() {
        const messagesContainer = document.getElementById('chatbot-messages');
        const welcomeHTML = `
            <div class="welcome-message">
                <h4>👋 Welcome to Global Trade Fairs!</h4>
                <p>I'm your AI assistant. I can help you with information about trade fairs, packages, registration, and more. How can I assist you today?</p>
            </div>
        `;
        messagesContainer.innerHTML = welcomeHTML;
    }

    /**
     * Load quick action buttons
     */
    async loadQuickActions() {
        try {
            const response = await fetch('/chatbot/quick-actions');
            const data = await response.json();

            if (data.success) {
                this.renderQuickActions(data.actions);
            }
        } catch (error) {
            console.error('Failed to load quick actions:', error);
        }
    }

    /**
     * Render quick action buttons
     */
    renderQuickActions(actions) {
        const container = document.getElementById('quick-actions');
        container.innerHTML = actions.map(action => `
            <button class="quick-action-btn" data-message="${action.message}">
                ${action.text}
            </button>
        `).join('');

        // Attach click handlers
        container.querySelectorAll('.quick-action-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const message = btn.getAttribute('data-message');
                document.getElementById('chatbot-input').value = message;
                this.sendMessage();
            });
        });
    }

    /**
     * Send message to chatbot
     */
    async sendMessage() {
        const input = document.getElementById('chatbot-input');
        const message = input.value.trim();

        if (!message || this.isTyping) return;

        // Clear input
        input.value = '';

        // Add user message to UI
        this.addMessage('user', message);

        // Show typing indicator
        this.showTyping();

        try {
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            // Send to backend
            const response = await fetch('/chatbot/message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    message: message,
                    session_id: this.sessionId,
                }),
            });

            const data = await response.json();

            // Hide typing indicator
            this.hideTyping();

            if (data.success) {
                // Add bot response to UI
                this.addMessage('bot', data.message);
            } else {
                // Handle specific actions like login requirement
                if (data.action === 'login_required') {
                    const loginMsg = `${data.message}<br><br><a href="/login" class="chatbot-login-btn" style="background: #2563eb; color: white; padding: 5px 10px; border-radius: 4px; text-decoration: none; font-size: 12px; display: inline-block;">Login to Continue</a>`;
                    this.addMessage('bot', loginMsg);
                } else {
                    this.addMessage('bot', data.message || 'Sorry, I encountered an error. Please try again.');
                }
            }

        } catch (error) {
            this.hideTyping();
            this.addMessage('bot', 'Sorry, I\'m having trouble connecting. Please try again later.');
            console.error('Chatbot error:', error);
        }
    }

    /**
     * Add message to chat
     */
    addMessage(role, text) {
        const messagesContainer = document.getElementById('chatbot-messages');
        const time = new Date().toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit'
        });

        const messageHTML = `
            <div class="message ${role}">
                <div class="message-avatar">
                   ${this.getAvatarHTML(role)}
                </div>
                <div>
                    <div class="message-bubble">
                        ${this.formatMessage(text)}
                    </div>
                    <div class="message-time">${time}</div>
                </div>
            </div>
        `;

        // Remove welcome message if exists
        const welcomeMsg = messagesContainer.querySelector('.welcome-message');
        if (welcomeMsg && role === 'user') {
            welcomeMsg.remove();
        }

        messagesContainer.insertAdjacentHTML('beforeend', messageHTML);
        this.scrollToBottom();
    }

    /**
     * Get avatar HTML based on role and config
     */
    getAvatarHTML(role) {
        if (role === 'bot') {
            const logo = window.chatbotConfig?.botLogo || '/images/email-logo.png';
            return `<img src="${logo}" onerror="this.src='/images/email-logo.png'" alt="Bot" style="width: 24px; height: 24px; border-radius: 50%; object-fit: cover;">`;
        } else {
            // User
            const avatar = window.chatbotConfig?.userAvatar;
            if (avatar) {
                // Fallback to emoji/default if image fails
                return `<img src="${avatar}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'" alt="User" style="width: 24px; height: 24px; border-radius: 50%; object-fit: cover;"> <div style="display:none; width: 24px; height: 24px; border-radius: 50%; background: #ddd; align-items: center; justify-content: center;">👤</div>`;
            }
            return '<div style="width: 24px; height: 24px; border-radius: 50%; background: #ddd; display: flex; align-items: center; justify-content: center;">👤</div>';
        }
    }

    /**
     * Format message text (convert line breaks, links, etc.)
     */
    formatMessage(text) {
        // Bold: **text** -> <b>text</b>
        text = text.replace(/\*\*(.*?)\*\*/g, '<b>$1</b>');

        // Italic: *text* -> <i>text</i>
        text = text.replace(/\*(.*?)\*/g, '<i>$1</i>');

        // Bullet points
        text = text.replace(/^\s*[\-\*]\s+(.*)$/gm, '• $1');

        // Convert line breaks
        text = text.replace(/\n/g, '<br>');

        // Convert URLs to links
        text = text.replace(
            /(https?:\/\/[^\s]+)/g,
            '<a href="$1" target="_blank" style="color: inherit; text-decoration: underline;">$1</a>'
        );

        return text;
    }

    /**
     * Show typing indicator
     */
    showTyping() {
        this.isTyping = true;
        const messagesContainer = document.getElementById('chatbot-messages');

        const typingHTML = `
            <div class="message bot" id="typing-indicator">
                <div class="message-avatar">
                    <img src="${window.chatbotConfig?.botLogo || '/images/email-logo.png'}" alt="Bot" style="width: 24px; height: 24px; border-radius: 50%; object-fit: cover;">
                </div>
                <div class="typing-indicator active">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            </div>
        `;

        messagesContainer.insertAdjacentHTML('beforeend', typingHTML);
        this.scrollToBottom();
    }

    /**
     * Hide typing indicator
     */
    hideTyping() {
        this.isTyping = false;
        const indicator = document.getElementById('typing-indicator');
        if (indicator) {
            indicator.remove();
        }
    }

    /**
     * Scroll to bottom of messages
     */
    scrollToBottom() {
        const messagesContainer = document.getElementById('chatbot-messages');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    /**
     * Get or create session ID
     */
    getOrCreateSessionId() {
        let sessionId = localStorage.getItem('chatbot_session_id');

        if (!sessionId) {
            sessionId = this.generateUUID();
            localStorage.setItem('chatbot_session_id', sessionId);
        }

        return sessionId;
    }

    /**
     * Generate UUID
     */
    generateUUID() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            const r = Math.random() * 16 | 0;
            const v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }
}

// Initialize chatbot when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new Chatbot();
    });
} else {
    new Chatbot();
}
