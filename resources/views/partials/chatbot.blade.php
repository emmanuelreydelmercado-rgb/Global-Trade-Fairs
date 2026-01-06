{{-- Chatbot Widget Include --}}
{{-- Add this file to any blade template where you want the chatbot to appear --}}


    {{-- Chatbot CSS --}}
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}?v=2.1">

    {{-- Chatbot HTML Structure (Direct Embed) --}}
    <div id="chatbot-wrapper" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
        <!-- Chatbot Button -->
        <button class="chatbot-button" id="chatbot-toggle" style="display: flex;">
            💬
        </button>

        <!-- Chatbot Container -->
        <div class="chatbot-container" id="chatbot-container">
            <!-- Header -->
            <div class="chatbot-header">
                <div class="chatbot-header-title">
                    <div class="chatbot-avatar-header">
                        <img src="{{ asset('images/email-logo.png') }}" alt="Bot" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">
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
            <div class="quick-actions" id="quick-actions"></div>

            <!-- Input Area -->
            <div class="chatbot-input">
                <input type="text" id="chatbot-input" placeholder="Type your message..." autocomplete="off" />
                <button class="chatbot-send-btn" id="chatbot-send">➤</button>
            </div>
        </div>
    </div>

    {{-- Chatbot JavaScript --}}
    <script>
        window.chatbotConfig = {
            botLogo: "{{ asset('images/email-logo.png') }}",
            userAvatar: "{{ auth()->check() && auth()->user()->profilepic && auth()->user()->profilepic !== 'default.jpg' ? asset('profilepics/' . auth()->user()->profilepic) : null }}",
            userName: "{{ auth()->check() ? auth()->user()->name : 'Guest' }}"
        };
    </script>
    <script src="{{ asset('js/chatbot.js') }}?v=2.1" defer></script>

