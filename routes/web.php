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
use App\Http\Controllers\Student\DashboardController    as StudentDash;
use App\Http\Controllers\Student\CourseController       as StudentCourses;
use App\Http\Controllers\Student\ResourceController     as StudentResources;
use App\Http\Controllers\Student\ProfileController      as StudentProfile;

// 👇 IMPORTANTE: Añadimos el controlador del Chatbot de la API
use App\Http\Controllers\Api\ChatbotController;

use Illuminate\Support\Facades\Route;

// ── Root ────────────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
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
    Route::get('/dashboard',                  [InstructorDash::class,    'index'])->name('dashboard');
    Route::get('/courses',                    [InstructorCourses::class, 'index'])->name('courses.index');// Rutas para Crear Cursos
    Route::get('/courses/create', [App\Http\Controllers\Instructor\CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [App\Http\Controllers\Instructor\CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}',           [InstructorCourses::class, 'show'] )->name('courses.show');
    Route::get('/courses/{course}/students',  [InstructorCourses::class, 'students'])->name('courses.students');
    Route::post('/courses/{course}/students/{student}/grade', [InstructorCourses::class, 'updateGrade'])->name('courses.students.grade');
    Route::get('/courses/{course}/modules',   [InstructorCourses::class, 'modules'])->name('courses.modules');
    Route::post('/courses/{course}/modules',  [InstructorCourses::class, 'storeModule'])->name('courses.modules.store');
    Route::delete('/courses/{course}/modules/{module}', [InstructorCourses::class,'destroyModule'])->name('courses.modules.destroy');
    Route::get('/courses/{course}/resources',         [InstructorResources::class,'index']  )->name('courses.resources.index');
    Route::get('/courses/{course}/resources/create',  [InstructorResources::class,'create'] )->name('courses.resources.create');
    Route::post('/courses/{course}/resources',        [InstructorResources::class,'store']  )->name('courses.resources.store');
    Route::delete('/courses/{course}/resources/{resource}', [InstructorResources::class,'destroy'])->name('courses.resources.destroy');
});

// ── Student ──────────────────────────────────────────────────
Route::middleware(['auth','check.active'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard',                  [StudentDash::class,    'index']  )->name('dashboard');
    Route::get('/courses',                    [StudentCourses::class, 'catalog'])->name('courses.catalog');
    Route::get('/courses/{course}',           [StudentCourses::class, 'show']   )->name('courses.show');
    Route::post('/courses/{course}/enroll',   [StudentCourses::class, 'enroll'] )->name('courses.enroll');
    Route::get('/courses/{course}/learn',     [StudentCourses::class, 'learn']  )->name('courses.learn');
    Route::get('/resources/{resource}',       [StudentResources::class,'show']  )->name('resources.show');
    Route::get('/profile',                    [StudentProfile::class, 'show']   )->name('profile');
    Route::post('/profile',                   [StudentProfile::class, 'update'] )->name('profile.update');
    Route::post('/profile/password',          [StudentProfile::class, 'changePassword'])->name('profile.password');
    
    // 👇 Corregimos la ruta de la vista y agregamos la ruta POST para procesar el mensaje 👇
    Route::get('/chatbot',                    fn() => view('student.chatbot')   )->name('chatbot');
    Route::post('/chatbot/send',              [ChatbotController::class, 'sendMessage'])->name('student.chatbot.send');
    
    Route::get('/search',                     fn() => view('student.search')   )->name('search');
});