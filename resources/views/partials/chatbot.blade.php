{{-- Chatbot Widget Include --}}
{{-- Add this file to any blade template where you want the chatbot to appear --}}

@if(config('services.gemini.api_key'))
    {{-- Chatbot CSS --}}
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">

    {{-- Chatbot JavaScript --}}
    <script src="{{ asset('js/chatbot.js') }}" defer></script>
@endif
