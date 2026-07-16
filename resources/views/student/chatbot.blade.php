@extends('layouts.app')
@section('title', 'Asistente Virtual IA')

@section('content')
<div class="max-w-5xl mx-auto h-[calc(100vh-8rem)] flex flex-col bg-white dark:bg-[#1e293b] rounded-[2rem] shadow-xl border border-gray-100 dark:border-slate-700/50 overflow-hidden animate-fade-in-up">
    
    {{-- Header del Chat --}}
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-slate-700/50 bg-gray-50/50 dark:bg-[#0f172a]/50 backdrop-blur-sm z-10">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-teal-600 p-0.5 shadow-lg shadow-blue-500/30">
                <div class="w-full h-full bg-white dark:bg-[#1e293b] rounded-full flex items-center justify-center text-blue-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" /></svg>
                </div>
            </div>
            <div>
                <h2 class="text-lg font-black text-gray-900 dark:text-white">Asistente Virtual INCES</h2>
                <div class="flex items-center gap-2 text-xs font-bold text-gray-500 dark:text-slate-400">
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                    Gemini IA • Disponible 24/7
                </div>
            </div>
        </div>
        <a href="{{ route('student.chatbot') }}" class="px-4 py-2 text-sm font-bold text-gray-600 dark:text-slate-300 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
            Nueva Charla
        </a>
    </div>

    {{-- Área de Mensajes --}}
    <div id="chat-messages" class="flex-grow p-6 overflow-y-auto space-y-6 scroll-smooth bg-gray-50/30 dark:bg-transparent">
        <div class="flex gap-4 max-w-3xl">
            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center text-sm flex-shrink-0 mt-1 text-blue-600 dark:text-blue-400">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" /></svg>
            </div>
            <div class="bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 p-4 rounded-2xl rounded-tl-none shadow-sm text-sm text-gray-700 dark:text-slate-300">
                ¡Hola! Soy el Asistente Virtual del INCES. ¿En qué te puedo ayudar hoy con tu aprendizaje?
            </div>
        </div>
    </div>

    {{-- Input Form --}}
    <div class="p-4 bg-white dark:bg-[#1e293b] border-t border-gray-100 dark:border-slate-700/50">
        <form id="chat-form" class="relative flex items-end gap-2 max-w-4xl mx-auto">
            @csrf
            <div class="relative w-full">
                <textarea id="chat-input" rows="1" placeholder="Escribe tu pregunta aquí..." class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-2xl pl-5 pr-14 py-4 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all resize-none shadow-inner" style="min-height: 56px; max-height: 150px;"></textarea>
                <button type="submit" id="send-btn" class="absolute right-2 bottom-2 w-10 h-10 bg-blue-500 hover:bg-blue-600 rounded-xl flex items-center justify-center text-white transition-all shadow-md active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-5 h-5 translate-x-[-1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"></path></svg>
                </button>
            </div>
        </form>
        <div class="text-center mt-2">
            <span class="text-[10px] text-gray-400 font-medium">Impulsado por la Base de Conocimiento INCES</span>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('chat-form');
        const input = document.getElementById('chat-input');
        const messagesContainer = document.getElementById('chat-messages');
        const sendBtn = document.getElementById('send-btn');
        const csrfToken = document.querySelector('input[name="_token"]').value;

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                form.dispatchEvent(new Event('submit'));
            }
        });

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const message = input.value.trim();
            if (!message) return;

            appendMessage('user', message);
            input.value = '';
            
            const typingId = 'typing-' + Date.now();
            appendTyping(typingId);
            input.disabled = true;
            sendBtn.disabled = true;

            try {
                const response = await fetch('/student/chatbot/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message: message })
                });

                const data = await response.json();
                document.getElementById(typingId).remove();

                if (data.success) {
                    appendMessage('bot', data.data.response);
                } else {
                    appendMessage('bot', 'No cuento con esa información por el momento en mi base de conocimientos.');
                }
            } catch (error) {
                if(document.getElementById(typingId)) document.getElementById(typingId).remove();
                appendMessage('bot', 'Hubo un error de conexión, intenta de nuevo.');
            }

            input.disabled = false;
            sendBtn.disabled = false;
            input.focus();
        });

        function appendMessage(sender, text) {
            const div = document.createElement('div');
            div.className = sender === 'user' 
                ? 'flex gap-4 max-w-3xl ml-auto flex-row-reverse animate-fade-in-up' 
                : 'flex gap-4 max-w-3xl animate-fade-in-up';

            let bubbleClass = sender === 'user'
                ? 'bg-blue-500 text-white p-4 rounded-2xl rounded-tr-none shadow-sm text-sm'
                : 'bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 p-4 rounded-2xl rounded-tl-none shadow-sm text-sm text-gray-700 dark:text-slate-300';

            let avatar = sender === 'user'
                ? `<div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-xs flex-shrink-0 mt-1 text-white font-bold">Tú</div>`
                : `<div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center text-sm flex-shrink-0 mt-1 text-blue-600 dark:text-blue-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" /></svg>
                   </div>`;

            let formattedText = text.replace(/\n/g, '<br>');
            div.innerHTML = `${avatar}<div class="${bubbleClass}">${formattedText}</div>`;
            messagesContainer.appendChild(div);
            messagesContainer.scrollTo({ top: messagesContainer.scrollHeight, behavior: 'smooth' });
        }

        function appendTyping(id) {
            const div = document.createElement('div');
            div.id = id;
            div.className = 'flex gap-4 max-w-3xl animate-fade-in-up';
            div.innerHTML = `<div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-sm flex-shrink-0 mt-1">🤖</div>
                            <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl rounded-tl-none shadow-sm text-sm text-gray-500 flex gap-1.5 items-center">
                                <span class="w-2 h-2 bg-blue-400 rounded-full animate-bounce"></span>
                                <span class="w-2 h-2 bg-blue-400 rounded-full animate-bounce delay-150"></span>
                                <span class="w-2 h-2 bg-blue-400 rounded-full animate-bounce delay-300"></span>
                            </div>`;
            messagesContainer.appendChild(div);
        }
    });
</script>
@endsection
