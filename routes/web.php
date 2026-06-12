<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController      as AdminDash;
use App\Http\Controllers\Admin\UserController           as AdminUsers;
use App\Http\Controllers\Admin\CourseController         as AdminCourses;
use App\Http\Controllers\Admin\ModuleController         as AdminModules;
use App\Http\Controllers\Admin\CategoryController       as AdminCategories;
use App\Http\Controllers\Admin\StatisticsController     as AdminStats;
use App\Http\Controllers\Admin\KnowledgeBaseController  as AdminKB;
use App\Http\Controllers\Instructor\DashboardController as InstructorDash;
use App\Http\Controllers\Instructor\CourseController    as InstructorCourses;
use App\Http\Controllers\Instructor\ResourceController  as InstructorResources;
use App\Http\Controllers\Instructor\QuizController      as InstructorQuizzes;
use App\Http\Controllers\Student\DashboardController    as StudentDash;
use App\Http\Controllers\Student\CourseController       as StudentCourses;
use App\Http\Controllers\Student\ResourceController     as StudentResources;
use App\Http\Controllers\Student\ProfileController      as StudentProfile;
use App\Http\Controllers\Student\QuizController         as StudentQuizzes;
use App\Http\Controllers\Api\ChatbotController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// ── Root & Utils ────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
});

// 🔥 Ruta temporal para crear el enlace simbólico en Railway sin Error 502
Route::get('/crear-enlace-fotos', function () {
    Artisan::call('storage:link');
    return '¡Listo, chamo! El enlace simbólico se creó con éxito. Ya puedes ver las fotos del INCES Campus.';
});

// ── Auth (Guest) ────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get ('/login',    [AuthController::class, 'showLogin']   )->name('login');
    Route::post('/login',    [AuthController::class, 'login']       )->name('login.post');
    Route::get ('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']    )->name('register.post');
    Route::get('/forgot-password', fn() => view('auth.forgot-password'))->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Admin ────────────────────────────────────────────────────
Route::middleware(['auth','role:admin','check.active'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',   [AdminDash::class,  'index'])->name('dashboard');
    Route::get('/statistics',  [AdminStats::class, 'index'])->name('statistics');
    Route::resource('users',   AdminUsers::class);
    Route::post('users/{user}/toggle', [AdminUsers::class,'toggle'])->name('users.toggle');
    
    // 🔥 NUEVO: Rutas para Inscripción Forzada (Superpoder de Admin)
    // Se colocan antes del resource para que Laravel no las confunda con "show"
    Route::get('courses/force-enroll', [AdminCourses::class, 'showForceEnroll'])->name('courses.force-enroll');
    Route::post('courses/force-enroll', [AdminCourses::class, 'forceEnroll'])->name('courses.force-enroll.post');
    
    Route::resource('courses', AdminCourses::class);
    
    Route::prefix('courses/{course}/modules')->name('courses.modules.')->group(function () {
        Route::get('/',           [AdminModules::class,'index']  )->name('index');
        Route::post('/',          [AdminModules::class,'store']  )->name('store');
        Route::put('/{module}',   [AdminModules::class,'update'] )->name('update');
        Route::delete('/{module}',[AdminModules::class,'destroy'])->name('destroy');
    });
    
    Route::resource('categories',    AdminCategories::class)->except(['show']);
    Route::resource('knowledge-base', AdminKB::class)->except(['show']);
});

// ── Instructor ───────────────────────────────────────────────
Route::middleware(['auth','role:admin,instructor','check.active'])->prefix('instructor')->name('instructor.')->group(function () {
    
    // Dashboard y Estadísticas
    Route::get('/dashboard', [InstructorDash::class, 'index'])->name('dashboard');

    // 🔥 1. LAS RUTAS ESPECÍFICAS VAN PRIMERO (Regla de Oro) 🔥
    // Detalles específicos del Curso
    Route::prefix('courses/{course}')->name('courses.')->group(function () {
        
        // Estudiantes, Notas y Asistencia
        Route::get('/students', [InstructorCourses::class, 'students'])->name('students');
        Route::post('/students/{student}/grade', [InstructorCourses::class, 'updateGrade'])->name('students.grade');
        Route::get('/export-students', [InstructorCourses::class, 'exportStudents'])->name('export-students'); 
        
        // Módulos
        Route::get('/modules', [InstructorCourses::class, 'modules'])->name('modules');
        Route::post('/modules', [InstructorCourses::class, 'storeModule'])->name('modules.store');
        Route::delete('/modules/{module}', [InstructorCourses::class, 'destroyModule'])->name('modules.destroy');
        
        // Recursos
        Route::resource('resources', InstructorResources::class)->except(['update', 'edit']);

        // Gestión de Evaluaciones (Quizzes)
        Route::prefix('quizzes')->name('quizzes.')->group(function () {
            Route::get('/create', [\App\Http\Controllers\Instructor\QuizController::class, 'create'])->name('create');
            
            // 🔥 EL PARCHE: Aquí está el '/save' para que no explote el formulario 🔥
            Route::post('/save',  [\App\Http\Controllers\Instructor\QuizController::class, 'store'])->name('store');
            
            // Toggle para activar/desactivar la evaluación
            Route::post('/{quiz}/toggle', [\App\Http\Controllers\Instructor\QuizController::class, 'toggleStatus'])->name('toggle');
        });

        // Reportes del Curso
        Route::get('/reportes/asistencia/{date}', [ReportController::class, 'downloadAttendance'])->name('reports.attendance');
    });

    // 🔥 2. EL RESOURCE BASE VA DE ÚLTIMO (Para que no atrape las rutas de arriba) 🔥
    // Gestión de Cursos (CRUD Base)
    Route::resource('courses', InstructorCourses::class); 
});

// ── Student ──────────────────────────────────────────────────
Route::middleware(['auth','check.active'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard',                  [StudentDash::class,    'index']  )->name('dashboard');
    Route::get('/courses',                    [StudentCourses::class, 'catalog'])->name('courses.catalog');
    Route::get('/courses/{course}',           [StudentCourses::class, 'show']   )->name('courses.show');
    Route::post('/courses/{course}/enroll',   [StudentCourses::class, 'enroll'] )->name('courses.enroll');
    Route::delete('/courses/{course}/withdraw', [StudentCourses::class, 'withdraw'])->name('courses.withdraw');
    
    // Ruta para el salón de clases (Learn)
    Route::get('/courses/{course}/learn',     [StudentCourses::class, 'learn']  )->name('courses.learn');
    
    // Ruta para guardar el progreso del estudiante en tiempo real
    Route::post('/courses/{course}/progress', [StudentCourses::class, 'updateProgress'])->name('courses.progress');
    
    Route::get('/resources/{resource}',       [StudentResources::class,'show']  )->name('resources.show');
    Route::get('/profile',                    [StudentProfile::class, 'show']   )->name('profile');
    Route::post('/profile',                   [StudentProfile::class, 'update'] )->name('profile.update');
    Route::post('/profile/password',          [StudentProfile::class, 'changePassword'])->name('profile.password');

    // Chatbot e IA
    Route::get('/chatbot',                    fn() => view('student.chatbot')   )->name('chatbot');
    Route::post('/chatbot/send',              [ChatbotController::class, 'sendMessage'])->name('student.chatbot.send');
    Route::get('/search',                     fn() => view('student.search')   )->name('search');
    
    // Rutas Definitivas para la Evaluación Final (Examen)
    Route::get('/courses/{course}/quizzes/{quiz}',  [\App\Http\Controllers\Student\QuizController::class, 'show'])->name('quizzes.show');
    Route::post('/courses/{course}/quizzes/{quiz}', [\App\Http\Controllers\Student\QuizController::class, 'submit'])->name('quizzes.submit');
});