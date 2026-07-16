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
<body class="bg-gray-50 dark:bg-[#0f172a] text-gray-800 dark:text-slate-200 antialiased min-h-screen flex flex-col lg:flex-row transition-colors duration-300">

    {{-- MITAD IZQUIERDA: IMAGEN DE FONDO --}}
    <div class="hidden lg:flex lg:w-1/2 relative items-center justify-center bg-blue-950 dark:bg-gray-900 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/90 to-blue-950/90 dark:from-slate-900/90 dark:to-[#0f172a]/90 z-10 transition-colors"></div>
        <img src="https://images.unsplash.com/photo-1517048676732-d65bc937f952?q=80&w=2070&auto=format&fit=crop" 
             alt="Fondo estudiantes" 
             class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-50 z-0">
        
        <div class="relative z-20 text-center px-12 w-full max-w-2xl">
            <h1 class="text-5xl font-extrabold text-white tracking-tight mb-6 drop-shadow-lg leading-tight">Formando a la<br><span class="text-red-500">Clase Trabajadora.</span></h1>
            <p class="text-lg text-blue-100 dark:text-gray-300 max-w-md mx-auto font-medium transition-colors leading-relaxed">
                Únete a IncesCampus, la plataforma de formación virtual para el desarrollo profesional y técnico.
            </p>
        </div>
    </div>

    {{-- MITAD DERECHA: FORMULARIO (Scroll independiente) --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 lg:p-16 h-screen overflow-y-auto bg-gray-50 dark:bg-[#0f172a] relative z-10"
         x-data="registrationForm()">
         
        <div class="w-full max-w-md bg-white dark:bg-slate-800/40 dark:backdrop-blur-2xl border border-gray-100 dark:border-slate-700/50 shadow-xl rounded-3xl p-8 relative z-20">
            
            <a href="/" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-blue-800 dark:text-slate-400 dark:hover:text-blue-400 transition-colors mb-8">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Volver al inicio
            </a>

            <div class="mb-10 text-left">
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-2">Crear Cuenta</h2>
                <p class="text-gray-500 dark:text-slate-400 font-medium">Ingresa tus datos para solicitar acceso.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-6" enctype="multipart/form-data">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-50 dark:bg-red-900/30 border border-red-400 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl mb-6 shadow-sm text-xs font-bold">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif
                
                {{-- FOTO DE PERFIL --}}
                <div class="flex flex-col items-center justify-center mb-6">
                    <div class="relative w-24 h-24 mb-3 group">
                        <template x-if="photoPreview">
                            <img :src="photoPreview" class="w-24 h-24 rounded-full object-cover border-2 border-blue-800 shadow-lg">
                        </template>
                        <template x-if="!photoPreview">
                            <div class="w-24 h-24 rounded-full bg-gray-50 dark:bg-[#0f172a]/80 border-2 border-dashed border-gray-300 dark:border-slate-600 flex flex-col items-center justify-center text-gray-400 hover:text-blue-800 dark:hover:text-blue-400 hover:border-blue-800 transition-all cursor-pointer shadow-inner" @click="$refs.photoInput.click()">
                                <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                <span class="text-[10px] font-bold uppercase">Foto</span>
                            </div>
                        </template>
                    </div>
                    <input type="file" name="avatar" x-ref="photoInput" @change="handlePhotoUpload" accept="image/*" class="hidden">
                </div>

                {{-- NOMBRES Y APELLIDOS --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-widest mb-2">Nombres *</label>
                        <input type="text" name="name" x-model="formData.name" @input="validateName" value="{{ old('name') }}"
                               class="w-full bg-gray-50 dark:bg-[#0f172a]/80 border rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none transition-all shadow-inner focus:ring-2 focus:ring-blue-800"
                               :class="errors.name && touched.name ? 'border-red-500' : 'border-gray-200 dark:border-slate-700'">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-widest mb-2">Apellidos *</label>
                        <input type="text" name="last_name" x-model="formData.lastName" @input="validateLastName" value="{{ old('last_name') }}"
                               class="w-full bg-gray-50 dark:bg-[#0f172a]/80 border rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none transition-all shadow-inner focus:ring-2 focus:ring-blue-800"
                               :class="errors.lastName && touched.lastName ? 'border-red-500' : 'border-gray-200 dark:border-slate-700'">
                    </div>
                </div>

                {{-- CÉDULA Y EMAIL --}}
                <div>
                    <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-widest mb-2">Cédula *</label>
                    <input type="text" name="cedula" x-model="formData.cedula" @input="validateCedula" placeholder="12345678"
                           class="w-full bg-gray-50 dark:bg-[#0f172a]/80 border rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none transition-all shadow-inner focus:ring-2 focus:ring-blue-800">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-widest mb-2">Correo Electrónico *</label>
                    <input type="email" name="email" x-model="formData.email" @input="validateEmail" placeholder="correo@inces.edu.ve"
                           class="w-full bg-gray-50 dark:bg-[#0f172a]/80 border rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none transition-all shadow-inner focus:ring-2 focus:ring-blue-800">
                </div>

                {{-- CONTRASEÑA Y REGLAS --}}
                <div>
                    <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-widest mb-2">Contraseña *</label>
                    <input type="password" name="password" x-model="formData.password" @input="validatePassword"
                           class="w-full bg-gray-50 dark:bg-[#0f172a]/80 border rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none transition-all shadow-inner focus:ring-2 focus:ring-blue-800">
                    
                    {{-- Reglas de contraseña con SVG --}}
                    <div class="mt-3 grid grid-cols-2 gap-2 text-[10px] font-bold text-gray-500 dark:text-slate-400">
                        <div class="flex items-center gap-1" :class="passwordRules.length ? 'text-blue-600' : ''">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path x-show="passwordRules.length" d="M5 13l4 4L19 7"></path><circle x-show="!passwordRules.length" cx="12" cy="12" r="9"/></svg>
                            Mín. 8 caracteres
                        </div>
                        <div class="flex items-center gap-1" :class="passwordRules.uppercase ? 'text-blue-600' : ''">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path x-show="passwordRules.uppercase" d="M5 13l4 4L19 7"/><circle x-show="!passwordRules.uppercase" cx="12" cy="12" r="9"/></svg>
                            Una mayúscula
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-4 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-red-600/30 flex items-center justify-center gap-2">
                    Registrar cuenta
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </form>
        </div>
    </div>

    {{-- Script de validación --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('registrationForm', () => ({
                photoPreview: null,
                formData: { name: '', lastName: '', cedula: '', email: '', password: '' },
                touched: { name: false, lastName: false, cedula: false, email: false, password: false },
                errors: { name: false, lastName: false, cedula: false, email: false, password: true },
                passwordRules: { length: false, uppercase: false },

                handlePhotoUpload(e) {
                    const file = e.target.files[0];
                    if (file) this.photoPreview = URL.createObjectURL(file);
                },
                validateName() { this.touched.name = true; this.errors.name = this.formData.name.length < 2; },
                validateLastName() { this.touched.lastName = true; this.errors.lastName = this.formData.lastName.length < 2; },
                validateCedula() { this.touched.cedula = true; this.errors.cedula = !/^[0-9]{6,10}$/.test(this.formData.cedula); },
                validateEmail() { this.touched.email = true; this.errors.email = !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.formData.email); },
                validatePassword() {
                    this.touched.password = true;
                    this.passwordRules.length = this.formData.password.length >= 8;
                    this.passwordRules.uppercase = /[A-Z]/.test(this.formData.password);
                    this.errors.password = !(this.passwordRules.length && this.passwordRules.uppercase);
                }
            }))
        });
    </script>
</body>
</html>
