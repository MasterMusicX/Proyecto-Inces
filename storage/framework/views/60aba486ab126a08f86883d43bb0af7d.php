<?php $__env->startSection('title', 'Asistente Virtual IA'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto h-[calc(100vh-8rem)] flex flex-col bg-white dark:bg-[#1e293b] rounded-[2rem] shadow-xl border border-gray-100 dark:border-slate-700/50 overflow-hidden animate-fade-in-up">
    
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-slate-700/50 bg-gray-50/50 dark:bg-[#0f172a]/50 backdrop-blur-sm z-10">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-teal-600 p-0.5 shadow-lg shadow-blue-500/30">
                <div class="w-full h-full bg-white dark:bg-[#1e293b] rounded-full flex items-center justify-center text-xl">
                    🤖
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
        <a href="<?php echo e(route('student.chatbot')); ?>" class="px-4 py-2 text-sm font-bold text-gray-600 dark:text-slate-300 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors shadow-sm">
            🔄 Nueva Charla
        </a>
    </div>

    <div id="chat-messages" class="flex-grow p-6 overflow-y-auto space-y-6 scroll-smooth bg-gray-50/30 dark:bg-transparent">
        
        <div class="flex gap-4 max-w-3xl">
            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center text-sm flex-shrink-0 mt-1">
                🤖
            </div>
            <div class="bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 p-4 rounded-2xl rounded-tl-none shadow-sm text-sm text-gray-700 dark:text-slate-300">
                ¡Hola José! Soy el Asistente Virtual del INCES. ¿En qué te puedo ayudar hoy con tu aprendizaje?
            </div>
        </div>

        </div>

    <div class="p-4 bg-white dark:bg-[#1e293b] border-t border-gray-100 dark:border-slate-700/50">
        <form id="chat-form" class="relative flex items-end gap-2 max-w-4xl mx-auto">
            <?php echo csrf_field(); ?>
            <div class="relative w-full">
                <textarea id="chat-input" rows="1" placeholder="Escribe tu pregunta aquí..." class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-2xl pl-5 pr-14 py-4 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all resize-none shadow-inner" style="min-height: 56px; max-height: 150px;"></textarea>
                
                <button type="submit" id="send-btn" class="absolute right-2 bottom-2 w-10 h-10 bg-blue-500 hover:bg-blue-600 rounded-xl flex items-center justify-center text-white transition-all shadow-md active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-5 h-5 translate-x-[-1px] translate-y-[1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </div>
        </form>
        <div class="text-center mt-2">
            <span class="text-[10px] text-gray-400 font-medium">Impulsado por la Base de Conocimiento INCES • La IA puede cometer errores.</span>
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

        // Permitir enviar con la tecla Enter (y Shift+Enter para nueva línea)
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

            // 1. Mostrar el mensaje del usuario en la pantalla
            appendMessage('user', message);
            input.value = '';
            
            // 2. Mostrar la animación de "La IA está escribiendo..."
            const typingId = 'typing-' + Date.now();
            appendTyping(typingId);

            // Bloquear el input mientras responde
            input.disabled = true;
            sendBtn.disabled = true;

            try {
                // 3. Mandar el mensaje a tu Controlador
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
                
                // Quitar los punticos de "escribiendo..."
                document.getElementById(typingId).remove();

                if (data.success) {
                    // Mostrar respuesta de la base de datos o de Gemini
                    appendMessage('bot', data.data.response);
                } else {
                    // CORRECCIÓN: Si la API de Gemini falla (ej. cuotas), muestra este mensaje en vez del error
                    appendMessage('bot', 'No cuento con esa información por el momento.');
                }

            } catch (error) {
                // Quitar los punticos si hay un error crítico
                if(document.getElementById(typingId)) {
                    document.getElementById(typingId).remove();
                }
                // CORRECCIÓN: Si el servidor falla (ej. error 500), el bot responde esto elegantemente
                appendMessage('bot', 'No cuento con esa información por el momento.');
            }

            // Desbloquear input
            input.disabled = false;
            sendBtn.disabled = false;
            input.focus();
        });

        // Función para pintar las burbujas de chat
        function appendMessage(sender, text) {
            const div = document.createElement('div');
            
            // Si es usuario va a la derecha, si es bot a la izquierda
            div.className = sender === 'user' 
                ? 'flex gap-4 max-w-3xl ml-auto flex-row-reverse animate-fade-in-up' 
                : 'flex gap-4 max-w-3xl animate-fade-in-up';

            let bubbleClass = sender === 'user'
                ? 'bg-blue-500 text-white p-4 rounded-2xl rounded-tr-none shadow-sm text-sm'
                : 'bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 p-4 rounded-2xl rounded-tl-none shadow-sm text-sm text-gray-700 dark:text-slate-300';

            let avatar = sender === 'user'
                ? `<div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-sm flex-shrink-0 mt-1 text-white">Tú</div>`
                : `<div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center text-sm flex-shrink-0 mt-1">🤖</div>`;

            // Formatear texto (saltos de línea y negritas básicas)
            let formattedText = text.replace(/\n/g, '<br>');
            formattedText = formattedText.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

            div.innerHTML = `
                ${avatar}
                <div class="${bubbleClass} leading-relaxed">${formattedText}</div>
            `;

            messagesContainer.appendChild(div);
            scrollToBottom();
        }

        // Función de "Escribiendo..."
        function appendTyping(id) {
            const div = document.createElement('div');
            div.id = id;
            div.className = 'flex gap-4 max-w-3xl';
            div.innerHTML = `
                <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center text-sm flex-shrink-0 mt-1">🤖</div>
                <div class="bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 p-4 rounded-2xl rounded-tl-none shadow-sm text-sm text-gray-500 flex gap-1.5 items-center">
                    <span class="w-2 h-2 bg-blue-500 rounded-full animate-bounce"></span>
                    <span class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                    <span class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0.4s"></span>
                </div>
            `;
            messagesContainer.appendChild(div);
            scrollToBottom();
        }

        function scrollToBottom() {
            messagesContainer.scrollTo({
                top: messagesContainer.scrollHeight,
                behavior: 'smooth'
            });
        }
    });
</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/inces/Escritorio/Proyecto Inces/resources/views/student/chatbot.blade.php ENDPATH**/ ?>