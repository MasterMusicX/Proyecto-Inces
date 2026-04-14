<!DOCTYPE html>
<html lang="es" class="scroll-smooth"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripción | IncesCampus</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-gray-50 dark:bg-[#0f172a] text-gray-800 dark:text-slate-200 antialiased min-h-screen flex transition-colors duration-300">

    <div class="hidden lg:flex lg:w-1/2 relative items-center justify-center bg-blue-950 dark:bg-gray-900 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/90 to-blue-950/90 dark:from-slate-900/90 dark:to-gray-900/90 z-10 transition-colors"></div>
        <img src="https://images.unsplash.com/photo-1517048676732-d65bc937f952?q=80&w=2070&auto=format&fit=crop" 
             alt="Fondo estudiantes" 
             class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-50 z-0">
        
        <div class="relative z-20 text-center px-12 w-full max-w-2xl">
            <div class="mb-12 flex items-center justify-center gap-3">
                <img src="<?php echo e(asset('images/Logo INCES.png')); ?>" alt="Logo INCES Campus" class="h-16 w-auto brightness-0 invert drop-shadow-md">
            </div>
            
            <h1 class="text-5xl font-extrabold text-white tracking-tight mb-6 drop-shadow-lg leading-tight">Formando a la<br><span class="text-red-500">Clase Trabajadora.</span></h1>
            <p class="text-lg text-blue-100 dark:text-gray-300 max-w-md mx-auto font-medium transition-colors leading-relaxed">
                Únete a IncesCampus, la plataforma de formación virtual para el desarrollo profesional y técnico.
            </p>
        </div>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 lg:p-16 overflow-y-auto bg-white dark:bg-[#0f172a] transition-colors"
         x-data="registrationForm()">
        
        <div class="w-full max-w-md">
            
            <a href="/" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-blue-800 dark:hover:text-blue-400 transition-colors mb-8">
                ← Volver al inicio
            </a>

            <div class="mb-10 text-center lg:text-left block lg:hidden">
                <img src="<?php echo e(asset('images/Logo INCES.png')); ?>" alt="Logo INCES Campus" class="h-12 w-auto mx-auto lg:mx-0 mb-4 transition-all">
                <p class="text-gray-500 dark:text-slate-400 font-medium mt-2">Solicita tu acceso creando una cuenta.</p>
            </div>
            
            <div class="mb-10 text-left hidden lg:block">
                <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-2">Crear Cuenta</h2>
                <p class="text-gray-500 dark:text-slate-400 font-medium">Ingresa tus datos para solicitar acceso a la plataforma.</p>
            </div>

            <form method="POST" action="<?php echo e(route('register')); ?>" class="space-y-6" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>

                <div class="flex flex-col items-center justify-center mb-6">
                    <div class="relative w-24 h-24 mb-3">
                        <template x-if="photoPreview">
                            <img :src="photoPreview" class="w-24 h-24 rounded-full object-cover border-2 border-blue-800 shadow-lg">
                        </template>
                        <template x-if="!photoPreview">
                            <div class="w-24 h-24 rounded-full bg-gray-50 dark:bg-slate-800/50 border-2 border-dashed border-gray-300 dark:border-slate-600 flex flex-col items-center justify-center text-gray-400 hover:text-blue-800 hover:border-blue-800 hover:bg-blue-50 dark:hover:bg-slate-800 transition-all cursor-pointer shadow-inner" @click="$refs.photoInput.click()">
                                <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span class="text-[10px] font-bold uppercase">Subir Foto</span>
                            </div>
                        </template>
                    </div>
                    <input type="file" name="avatar" x-ref="photoInput" @change="handlePhotoUpload" accept="image/*" class="hidden">
                    <p class="text-xs text-gray-500 dark:text-slate-400 text-center">Formatos JPG, PNG (Máx 2MB). Opcional.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-widest mb-2">Nombres *</label>
                        <input type="text" name="name" x-model="formData.name" @input="validateName"
                               class="w-full bg-white dark:bg-[#1e293b] border rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none transition-all focus:ring-2 focus:ring-blue-800"
                               :class="{'border-gray-200 dark:border-slate-700': !touched.name, 'border-red-500 ring-1 ring-red-500': errors.name && touched.name, 'border-blue-800 ring-1 ring-blue-800': !errors.name && touched.name}">
                        
                        <div class="mt-2 min-h-[20px] text-xs font-medium">
                            <span x-show="errors.name && touched.name" class="text-red-500 flex items-center gap-1">⚠️ Solo letras y espacios.</span>
                            <span x-show="!errors.name && touched.name && formData.name.length > 0" class="text-blue-600 flex items-center gap-1">✅ Válido.</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-widest mb-2">Apellidos *</label>
                        <input type="text" name="last_name" x-model="formData.lastName" @input="validateLastName"
                               class="w-full bg-white dark:bg-[#1e293b] border rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none transition-all focus:ring-2 focus:ring-blue-800"
                               :class="{'border-gray-200 dark:border-slate-700': !touched.lastName, 'border-red-500 ring-1 ring-red-500': errors.lastName && touched.lastName, 'border-blue-800 ring-1 ring-blue-800': !errors.lastName && touched.lastName}">
                        
                        <div class="mt-2 min-h-[20px] text-xs font-medium">
                            <span x-show="errors.lastName && touched.lastName" class="text-red-500 flex items-center gap-1">⚠️ Solo letras y espacios.</span>
                            <span x-show="!errors.lastName && touched.lastName && formData.lastName.length > 0" class="text-blue-600 flex items-center gap-1">✅ Válido.</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-widest mb-2">Cédula de Identidad *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 dark:text-slate-500 font-bold">V-</span>
                        <input type="text" name="cedula" x-model="formData.cedula" @input="validateCedula" placeholder="Ej: 12345678"
                               class="w-full bg-white dark:bg-[#1e293b] border rounded-xl pl-10 pr-4 py-3 text-gray-900 dark:text-white outline-none transition-all focus:ring-2 focus:ring-blue-800"
                               :class="{'border-gray-200 dark:border-slate-700': !touched.cedula, 'border-red-500 ring-1 ring-red-500': errors.cedula && touched.cedula, 'border-blue-800 ring-1 ring-blue-800': !errors.cedula && touched.cedula}">
                    </div>
                    <div class="mt-2 min-h-[20px] text-xs font-medium">
                        <span x-show="errors.cedula && touched.cedula" class="text-red-500 flex items-center gap-1">⚠️ Solo números (6 a 10 dígitos).</span>
                        <span x-show="!errors.cedula && touched.cedula && formData.cedula.length > 0" class="text-blue-600 flex items-center gap-1">✅ Cédula válida.</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-widest mb-3">Género *</label>
                    <div class="flex gap-4">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="gender" value="M" x-model="formData.gender" class="peer sr-only" @change="validateGender">
                            <div class="px-4 py-3 text-center border rounded-xl font-medium transition-all peer-checked:bg-blue-800 peer-checked:text-white peer-checked:border-blue-800 bg-gray-50 dark:bg-[#1e293b] text-gray-700 dark:text-slate-300 border-gray-200 dark:border-slate-700 hover:bg-gray-100 dark:hover:bg-slate-800">
                                Masculino
                            </div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="gender" value="F" x-model="formData.gender" class="peer sr-only" @change="validateGender">
                            <div class="px-4 py-3 text-center border rounded-xl font-medium transition-all peer-checked:bg-blue-800 peer-checked:text-white peer-checked:border-blue-800 bg-gray-50 dark:bg-[#1e293b] text-gray-700 dark:text-slate-300 border-gray-200 dark:border-slate-700 hover:bg-gray-100 dark:hover:bg-slate-800">
                                Femenino
                            </div>
                        </label>
                    </div>
                    <div class="mt-2 min-h-[20px] text-xs font-medium">
                        <span x-show="errors.gender && touched.gender" class="text-red-500 flex items-center gap-1">⚠️ Selecciona un género.</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-widest mb-2">Correo Electrónico *</label>
                    <input type="email" name="email" x-model="formData.email" @input="validateEmail" placeholder="tu.correo@ejemplo.com"
                           class="w-full bg-white dark:bg-[#1e293b] border rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none transition-all focus:ring-2 focus:ring-blue-800"
                           :class="{'border-gray-200 dark:border-slate-700': !touched.email, 'border-red-500 ring-1 ring-red-500': errors.email && touched.email, 'border-blue-800 ring-1 ring-blue-800': !errors.email && touched.email}">
                    
                    <div class="mt-2 min-h-[20px] text-xs font-medium">
                        <span x-show="errors.email && touched.email" class="text-red-500 flex items-center gap-1">⚠️ Formato de correo inválido.</span>
                        <span x-show="!errors.email && touched.email && formData.email.length > 0" class="text-blue-600 flex items-center gap-1">✅ Correo válido.</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-widest mb-2">Contraseña *</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" name="password" x-model="formData.password" @input="validatePassword"
                               class="w-full bg-white dark:bg-[#1e293b] border rounded-xl pl-4 pr-12 py-3 text-gray-900 dark:text-white outline-none transition-all focus:ring-2 focus:ring-blue-800"
                               :class="{'border-gray-200 dark:border-slate-700': !touched.password, 'border-red-500 ring-1 ring-red-500': errors.password && touched.password, 'border-blue-800 ring-1 ring-blue-800': !errors.password && touched.password}">
                        
                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 dark:text-slate-500 hover:text-blue-800 transition-colors">
                            <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                        </button>
                    </div>
                    
                    <div class="mt-3 grid grid-cols-2 gap-2 text-[10px] font-bold text-gray-500 dark:text-slate-400">
                        <div class="flex items-center gap-1" :class="{'text-blue-600 dark:text-blue-400': passwordRules.length}">
                            <span x-text="passwordRules.length ? '✅' : '○'"></span> Mín. 8 caracteres
                        </div>
                        <div class="flex items-center gap-1" :class="{'text-blue-600 dark:text-blue-400': passwordRules.uppercase}">
                            <span x-text="passwordRules.uppercase ? '✅' : '○'"></span> Una mayúscula
                        </div>
                        <div class="flex items-center gap-1" :class="{'text-blue-600 dark:text-blue-400': passwordRules.number}">
                            <span x-text="passwordRules.number ? '✅' : '○'"></span> Un número
                        </div>
                        <div class="flex items-center gap-1" :class="{'text-blue-600 dark:text-blue-400': passwordRules.special}">
                            <span x-text="passwordRules.special ? '✅' : '○'"></span> Un carácter especial
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" 
                            :disabled="!isFormValid"
                            class="w-full py-4 text-white font-bold rounded-xl transition-all shadow-lg flex items-center justify-center gap-2"
                            :class="isFormValid ? 'bg-red-600 hover:bg-red-700 dark:bg-red-600 dark:hover:bg-red-700 hover:-translate-y-1 shadow-red-600/30 cursor-pointer' : 'bg-gray-400 dark:bg-slate-700 cursor-not-allowed opacity-70'">
                        <span>Registrar mi cuenta</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
                
                <div class="text-center mt-6">
                    <p class="text-sm text-gray-500 dark:text-slate-400">
                        ¿Ya estás en la institución? <a href="<?php echo e(route('login')); ?>" class="text-blue-800 dark:text-blue-400 font-bold hover:underline">Inicia sesión aquí</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('registrationForm', () => ({
                photoPreview: null,
                showPassword: false,
                formData: {
                    name: '',
                    lastName: '',
                    cedula: '',
                    gender: '',
                    email: '',
                    password: ''
                },
                touched: {
                    name: false,
                    lastName: false,
                    cedula: false,
                    gender: false,
                    email: false,
                    password: false
                },
                errors: {
                    name: true,
                    lastName: true,
                    cedula: true,
                    gender: true,
                    email: true,
                    password: true
                },
                passwordRules: {
                    length: false,
                    uppercase: false,
                    number: false,
                    special: false
                },

                get isFormValid() {
                    return !this.errors.name && !this.errors.lastName && !this.errors.cedula && 
                           !this.errors.gender && !this.errors.email && !this.errors.password;
                },

                handlePhotoUpload(e) {
                    const file = e.target.files[0];
                    if (file) {
                        this.photoPreview = URL.createObjectURL(file);
                    }
                },

                validateName() {
                    this.touched.name = true;
                    const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
                    this.errors.name = !regex.test(this.formData.name) || this.formData.name.length < 2;
                },

                validateLastName() {
                    this.touched.lastName = true;
                    const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
                    this.errors.lastName = !regex.test(this.formData.lastName) || this.formData.lastName.length < 2;
                },

                validateCedula() {
                    this.touched.cedula = true;
                    const regex = /^[0-9]{6,10}$/;
                    this.errors.cedula = !regex.test(this.formData.cedula);
                },

                validateGender() {
                    this.touched.gender = true;
                    this.errors.gender = !this.formData.gender;
                },

                validateEmail() {
                    this.touched.email = true;
                    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    this.errors.email = !regex.test(this.formData.email);
                },

                validatePassword() {
                    this.touched.password = true;
                    const p = this.formData.password;
                    
                    this.passwordRules.length = p.length >= 8;
                    this.passwordRules.uppercase = /[A-Z]/.test(p);
                    this.passwordRules.number = /[0-9]/.test(p);
                    this.passwordRules.special = /[^A-Za-z0-9]/.test(p);

                    this.errors.password = !(this.passwordRules.length && this.passwordRules.uppercase && 
                                            this.passwordRules.number && this.passwordRules.special);
                }
            }))
        })
    </script>
</body>
</html><?php /**PATH /home/inces/Escritorio/Proyecto Inces/resources/views/auth/register.blade.php ENDPATH**/ ?>