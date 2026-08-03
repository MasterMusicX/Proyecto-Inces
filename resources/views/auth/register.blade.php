<!DOCTYPE html>
<html lang="es" class="scroll-smooth"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripción | IncesCampus</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-gray-50 dark:bg-[#0f172a] text-gray-800 dark:text-slate-200 antialiased min-h-screen flex flex-col lg:flex-row transition-colors duration-300"
      x-data="registrationForm()">

    {{-- MITAD IZQUIERDA: HERO INSTITUCIONAL --}}
    <div class="hidden lg:flex lg:w-1/2 relative items-center justify-center bg-blue-950 dark:bg-gray-900 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/90 to-blue-950/95 dark:from-slate-900/90 dark:to-[#0f172a]/95 z-10 transition-colors"></div>
        <img src="https://images.unsplash.com/photo-1517048676732-d65bc937f952?q=80&w=2070&auto=format&fit=crop" 
             alt="Fondo estudiantes" 
             class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-50 z-0">
        
        <div class="relative z-20 text-center px-12 w-full max-w-2xl">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-red-600/20 border border-red-500/30 text-red-300 text-xs font-black uppercase tracking-widest rounded-full mb-6 backdrop-blur-md shadow-sm">
                <svg class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25V9" /></svg>
                Plataforma Institucional INCES
            </div>
            <h1 class="text-5xl font-black text-white tracking-tight mb-6 drop-shadow-lg leading-tight">Formando a la<br><span class="text-red-500">Clase Trabajadora.</span></h1>
            <p class="text-base text-blue-100 dark:text-gray-300 max-w-md mx-auto font-medium transition-colors leading-relaxed">
                Únete a IncesCampus, la plataforma de formación virtual para el desarrollo profesional y técnico del poder popular.
            </p>
        </div>
    </div>

    {{-- MITAD DERECHA: FORMULARIO DE REGISTRO --}}
    <div class="w-full lg:w-1/2 flex flex-col p-6 sm:p-10 lg:p-12 h-screen overflow-y-auto bg-gray-50 dark:bg-[#0f172a] relative z-10">
         
        {{-- LUCES DE FONDO MODO OSCURO --}}
        <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-red-600/10 rounded-full blur-[100px] pointer-events-none hidden dark:block z-0"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-blue-600/10 rounded-full blur-[100px] pointer-events-none hidden dark:block z-0"></div>

        {{-- TARJETA DEL FORMULARIO --}}
        <div class="w-full max-w-lg my-auto mx-auto bg-white dark:bg-[#1e293b]/95 dark:backdrop-blur-xl border border-gray-100 dark:border-slate-700/80 shadow-2xl rounded-[2.5rem] p-8 sm:p-10 relative z-20">
            
            {{-- BOTÓN VOLVER --}}
            <a href="/" class="inline-flex items-center text-xs font-extrabold text-gray-500 hover:text-blue-700 dark:text-slate-400 dark:hover:text-blue-400 transition-colors mb-6 group uppercase tracking-wider">
                <svg class="w-4 h-4 mr-1.5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"></path></svg>
                Volver al inicio
            </a>

            <div class="mb-6 text-left">
                <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-1">Crear Cuenta</h2>
                <p class="text-gray-500 dark:text-slate-400 font-medium text-xs">Ingresa tus datos personales para formalizar tu registro.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5" enctype="multipart/form-data">
                @csrf

                @if ($errors->any())
                    <div class="bg-rose-50 dark:bg-rose-500/10 border-l-4 border-rose-500 text-rose-800 dark:text-rose-400 px-4 py-3 rounded-r-xl mb-4 text-xs font-bold shadow-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif
                
                {{-- FOTO DE PERFIL --}}
                <div class="flex flex-col items-center justify-center mb-2">
                    <div class="relative w-20 h-20 mb-2 group cursor-pointer" @click="$refs.photoInput.click()">
                        <template x-if="photoPreview">
                            <img :src="photoPreview" class="w-20 h-20 rounded-full object-cover border-2 border-blue-600 shadow-md">
                        </template>
                        <template x-if="!photoPreview">
                            <div class="w-20 h-20 rounded-full bg-gray-50 dark:bg-[#0f172a] border-2 border-dashed border-gray-300 dark:border-slate-600 flex flex-col items-center justify-center text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-600 transition-all shadow-inner">
                                <svg class="w-7 h-7 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Z"></path></svg>
                                <span class="text-[9px] font-black uppercase tracking-wider">Subir Foto</span>
                            </div>
                        </template>
                    </div>
                    <input type="file" name="avatar" x-ref="photoInput" @change="handlePhotoUpload" accept="image/*" class="hidden">
                </div>

                {{-- NOMBRES Y APELLIDOS --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider mb-1">Nombres *</label>
                        <input type="text" name="name" x-model="formData.name" value="{{ old('name') }}" required placeholder="Tus nombres"
                               class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-600 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider mb-1">Apellidos *</label>
                        <input type="text" name="last_name" x-model="formData.lastName" value="{{ old('last_name') }}" required placeholder="Tus apellidos"
                               class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-600 transition-all">
                    </div>
                </div>

                {{-- CÉDULA Y GÉNERO (ESTÉTICAMENTE PERFECCIONADOS) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider mb-1">Cédula *</label>
                        <input type="text" name="cedula" x-model="formData.cedula" value="{{ old('cedula') }}" required placeholder="Ej: 12345678"
                               class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-600 transition-all">
                    </div>

                    {{-- GÉNERO BOTONES MODERNOS --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider mb-1">Género *</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="flex items-center justify-center py-2.5 px-3 rounded-xl border cursor-pointer transition-all text-xs font-bold select-none"
                                   :class="formData.gender === 'M' 
                                       ? 'bg-blue-600 text-white border-blue-600 shadow-md' 
                                       : 'bg-gray-50 dark:bg-[#0f172a] border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-300 hover:border-blue-300'">
                                <input type="radio" name="gender" value="M" x-model="formData.gender" required class="sr-only">
                                <svg class="w-4 h-4 mr-1.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                                <span>Masculino</span>
                            </label>

                            <label class="flex items-center justify-center py-2.5 px-3 rounded-xl border cursor-pointer transition-all text-xs font-bold select-none"
                                   :class="formData.gender === 'F' 
                                       ? 'bg-pink-600 text-white border-pink-600 shadow-md' 
                                       : 'bg-gray-50 dark:bg-[#0f172a] border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-300 hover:border-pink-300'">
                                <input type="radio" name="gender" value="F" x-model="formData.gender" required class="sr-only">
                                <svg class="w-4 h-4 mr-1.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                                <span>Femenino</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- CORREO ELECTRÓNICO --}}
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider mb-1">Correo Electrónico *</label>
                    <input type="email" name="email" x-model="formData.email" value="{{ old('email') }}" required placeholder="correo@inces.edu.ve"
                           class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-600 transition-all">
                </div>

                {{-- CONTRASEÑA --}}
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider mb-1">Contraseña *</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" name="password" x-model="formData.password" @input="validatePassword" required placeholder="••••••••"
                               class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl pl-4 pr-12 py-2.5 text-sm text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-600 transition-all">
                        
                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 px-3.5 flex items-center text-gray-400 hover:text-blue-600 focus:outline-none">
                            <svg x-show="!showPassword" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                            <svg x-show="showPassword" style="display: none;" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                        </button>
                    </div>
                </div>

                {{-- CONFIRMAR CONTRASEÑA --}}
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider mb-1">Confirmar Contraseña *</label>
                    <div class="relative">
                        <input :type="showPasswordConfirm ? 'text' : 'password'" name="password_confirmation" x-model="formData.passwordConfirmation" required placeholder="Repite tu contraseña"
                               class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl pl-4 pr-12 py-2.5 text-sm text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-600 transition-all">
                        
                        <button type="button" @click="showPasswordConfirm = !showPasswordConfirm" class="absolute inset-y-0 right-0 px-3.5 flex items-center text-gray-400 hover:text-blue-600 focus:outline-none">
                            <svg x-show="!showPasswordConfirm" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                            <svg x-show="showPasswordConfirm" style="display: none;" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                        </button>
                    </div>
                </div>

                {{-- 🔥 BOTÓN ACEPTAR TÉRMINOS Y CONDICIONES (REQUISITO LEGAL) 🔥 --}}
                <div class="pt-2">
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input type="checkbox" name="terms" value="1" required x-model="acceptedTerms"
                               class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:bg-slate-800 dark:border-slate-600 cursor-pointer shrink-0">
                        <span class="text-xs text-gray-600 dark:text-slate-400 leading-relaxed select-none">
                            He leído y acepto los <button type="button" @click="showTermsModal = true" class="text-blue-600 dark:text-blue-400 font-bold hover:underline">Términos, Condiciones y Políticas de Privacidad</button> de la plataforma IncesCampus.
                        </span>
                    </label>
                </div>

                {{-- BOTÓN REGISTRAR CUENTA --}}
                <button type="submit" class="w-full py-4 bg-red-600 hover:bg-red-700 text-white font-black rounded-xl transition-all shadow-lg shadow-red-600/30 flex items-center justify-center gap-2 mt-4 hover:-translate-y-0.5 text-sm uppercase tracking-wider">
                    <span>Registrar Cuenta</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"></path></svg>
                </button>
            </form>
        </div>
    </div>

    {{-- 🔥 MODAL INTERACTIVO DE TÉRMINOS Y CONDICIONES 🔥 --}}
    <div x-show="showTermsModal" style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm text-gray-900" 
         x-transition.opacity>
        <div @click.away="showTermsModal = false" 
             class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-2xl p-6 sm:p-8 max-w-2xl w-full border border-gray-100 dark:border-slate-700 max-h-[85vh] flex flex-col">
            
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-700 pb-4 mb-4">
                <h3 class="text-xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" /></svg>
                    Términos y Condiciones Institucionales
                </h3>
                <button @click="showTermsModal = false" class="text-gray-400 hover:text-gray-600 rounded-lg p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="overflow-y-auto space-y-4 text-xs text-gray-600 dark:text-slate-300 leading-relaxed pr-2 custom-scrollbar">
                <p><strong>1. Declaración de Uso Institucional:</strong> IncesCampus es una plataforma tecnológica destinada a la formación técnica, profesional y productiva administrada por el Instituto Nacional de Capacitación y Educación Socialista (INCES).</p>
                <p><strong>2. Veracidad de los Datos:</strong> El participante declara que la información suministrada durante su registro (Cédula de Identidad, Nombres, Apellidos y Correo Electrónico) es legítima y fidedigna.</p>
                <p><strong>3. Propiedad Intelectual:</strong> Todos los módulos, contenidos, guías y recursos pedagógicos alojados en la plataforma son propiedad intelectual reservada del INCES o sus respectivos instructores autorizados.</p>
                <p><strong>4. Compromiso Académico:</strong> La acreditación de los módulos y certificados requerirá el cumplimiento de los porcentajes de asistencia, entrega de proyectos y aprobación por parte del Maestro Técnico Productivo (MTP).</p>
                <p><strong>5. Protección de Datos:</strong> Los datos recolectados se mantendrán bajo estricta confidencialidad para los fines académicos e institucionales oficiales.</p>
            </div>

            <div class="pt-4 border-t border-gray-100 dark:border-slate-700 mt-4 flex justify-end">
                <button @click="acceptedTerms = true; showTermsModal = false" type="button" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-md">
                    Entendido y Aceptar
                </button>
            </div>
        </div>
    </div>

    {{-- Script de Alpine.js --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('registrationForm', () => ({
                photoPreview: null,
                showPassword: false,
                showPasswordConfirm: false,
                showTermsModal: false,
                acceptedTerms: false,
                formData: { 
                    name: '{{ old('name') }}', 
                    lastName: '{{ old('last_name') }}', 
                    cedula: '{{ old('cedula') }}', 
                    gender: '{{ old('gender', 'M') }}',
                    email: '{{ old('email') }}', 
                    password: '', 
                    passwordConfirmation: '' 
                },

                handlePhotoUpload(e) {
                    const file = e.target.files[0];
                    if (file) this.photoPreview = URL.createObjectURL(file);
                }
            }))
        });
    </script>
</body>
</html>
