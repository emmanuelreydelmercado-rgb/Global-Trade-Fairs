{{-- Chatbot Widget Include --}}
{{-- Add this file to any blade template where you want the chatbot to appear --}}

@if(config('services.gemini.api_key'))
    {{-- Chatbot CSS --}}
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">

    {{-- Chatbot JavaScript --}}
    <script>
        window.chatbotConfig = {
            botLogo: "{{ asset('images/email-logo.png') }}",
            userAvatar: "{{ auth()->check() && auth()->user()->profilepic && auth()->user()->profilepic !== 'default.jpg' ? asset('profilepics/' . auth()->user()->profilepic) : null }}",
            userName: "{{ auth()->check() ? auth()->user()->name : 'Guest' }}"
        };
    </script>
    <script src="{{ asset('js/chatbot.js') }}" defer></script>
@endif
