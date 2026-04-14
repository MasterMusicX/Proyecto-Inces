<?php
namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\KnowledgeBase;
use App\Models\Module;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ────────────────────────────────────────────
        $admin = User::firstOrCreate(['email' => 'admin@inces.gob.ve'], [
            'name'      => 'Administrador INCES',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        // ── Instructors ──────────────────────────────────────
        $instructor1 = User::firstOrCreate(['email' => 'instructor@inces.gob.ve'], [
            'name'      => 'Prof. María González',
            'password'  => Hash::make('password'),
            'role'      => 'instructor',
            'is_active' => true,
            'bio'       => 'Especialista en Administración y Gestión Empresarial con 10 años de experiencia.',
        ]);

        $instructor2 = User::firstOrCreate(['email' => 'instructor2@inces.gob.ve'], [
            'name'      => 'Prof. Carlos Rodríguez',
            'password'  => Hash::make('password'),
            'role'      => 'instructor',
            'is_active' => true,
            'bio'       => 'Técnico en Sistemas de Información y Tecnología.',
        ]);

        // ── Students ─────────────────────────────────────────
        $student = User::firstOrCreate(['email' => 'estudiante@inces.gob.ve'], [
            'name'      => 'José Davalillo',
            'password'  => Hash::make('password'),
            'role'      => 'student',
            'is_active' => true,
        ]);

        // ── Categories ───────────────────────────────────────
        $categories = [
            ['name' => 'Administración',    'slug' => 'administracion',    'color' => '#005A9E'],
            ['name' => 'Tecnología',        'slug' => 'tecnologia',        'color' => '#00A86B'],
            ['name' => 'Electricidad',      'slug' => 'electricidad',      'color' => '#F5A623'],
            ['name' => 'Construcción',      'slug' => 'construccion',      'color' => '#D0021B'],
            ['name' => 'Gastronomía',       'slug' => 'gastronomia',       'color' => '#7B68EE'],
            ['name' => 'Salud y Seguridad', 'slug' => 'salud-seguridad',   'color' => '#1ABC9C'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        $catAdmin = Category::where('slug', 'administracion')->first();
        $catTech  = Category::where('slug', 'tecnologia')->first();

        // ── Courses ──────────────────────────────────────────
        $course1 = Course::firstOrCreate(
            ['slug' => 'administracion-empresas-basico'],
            [
                'title'          => 'Administración de Empresas Básico',
                'description'    => 'Curso introductorio a los principios fundamentales de la administración empresarial, gestión de recursos y planificación organizacional.',
                'objectives'     => "Al finalizar el curso el participante será capaz de:\n- Identificar los principios de la administración\n- Aplicar técnicas básicas de gestión\n- Elaborar planes operativos simples",
                'instructor_id'  => $instructor1->id,
                'category_id'    => $catAdmin->id,
                'status'         => 'published',
                'level'          => 'basico',
                'duration_hours' => 40,
                'is_featured'    => true,
            ]
        );

        $course2 = Course::firstOrCreate(
            ['slug' => 'informatica-basica-inces'],
            [
                'title'          => 'Informática Básica',
                'description'    => 'Aprende a utilizar computadoras, herramientas de ofimática y navegar por internet de forma segura y eficiente.',
                'instructor_id'  => $instructor2->id,
                'category_id'    => $catTech->id,
                'status'         => 'published',
                'level'          => 'basico',
                'duration_hours' => 30,
                'is_featured'    => true,
            ]
        );

        // ── Modules ──────────────────────────────────────────
        // CORREGIDO: order -> sort_order
        $modules1 = [
            ['title' => 'Unidad 1: Introducción a la Administración', 'sort_order' => 1],
            ['title' => 'Unidad 2: Planificación Estratégica',        'sort_order' => 2],
            ['title' => 'Unidad 3: Organización Empresarial',         'sort_order' => 3],
            ['title' => 'Unidad 4: Control y Evaluación',             'sort_order' => 4],
        ];
        foreach ($modules1 as $m) {
            Module::firstOrCreate(
                ['course_id' => $course1->id, 'title' => $m['title']],
                // CORREGIDO: is_published -> is_visible
                array_merge($m, ['is_visible' => true]) 
            );
        }

        $modules2 = [
            ['title' => 'Módulo 1: Hardware y Software',      'sort_order' => 1],
            ['title' => 'Módulo 2: Sistema Operativo Windows','sort_order' => 2],
            ['title' => 'Módulo 3: Microsoft Office',         'sort_order' => 3],
            ['title' => 'Módulo 4: Internet y Seguridad',     'sort_order' => 4],
        ];
        foreach ($modules2 as $m) {
            Module::firstOrCreate(
                ['course_id' => $course2->id, 'title' => $m['title']],
                array_merge($m, ['is_visible' => true])
            );
        }

        // ── Enrollment ───────────────────────────────────────
        Enrollment::firstOrCreate(
            ['user_id' => $student->id, 'course_id' => $course1->id],
            ['status' => 'active', 'progress_percentage' => 25]
        );

        // ── Knowledge Base ───────────────────────────────────
        $faqs = [
            [
                'category' => 'faq',
                'question' => '¿Cómo me inscribo en un curso?',
                'answer'   => 'Para inscribirte en un curso, sigue estos pasos: 1) Inicia sesión en tu cuenta. 2) Dirígete al Catálogo de Cursos. 3) Selecciona el curso de tu interés. 4) Haz clic en el botón "Inscribirme". Una vez inscrito, podrás acceder a todos los materiales del curso desde tu panel de estudiante.',
            ],
            [
                'category' => 'faq',
                'question' => '¿Cómo descargo los materiales del curso?',
                'answer'   => 'Puedes descargar los materiales de dos formas: 1) Desde el contenido del curso, haz clic en el recurso que deseas descargar y selecciona el botón de descarga. 2) Desde la sección de Recursos de cada módulo. Nota: algunos recursos pueden estar configurados solo para visualización en línea.',
            ],
            [
                'category' => 'faq',
                'question' => '¿Qué hago si olvidé mi contraseña?',
                'answer'   => 'Si olvidaste tu contraseña: 1) Ve a la página de inicio de sesión. 2) Haz clic en "¿Olvidaste tu contraseña?". 3) Ingresa tu correo electrónico registrado. 4) Recibirás un enlace para restablecer tu contraseña. Si no recibes el correo, verifica tu carpeta de spam.',
            ],
            [
                'category' => 'faq',
                'question' => '¿Qué es el INCES?',
                'answer'   => 'El INCES (Instituto Nacional de Capacitación y Educación Socialista) es un organismo del Estado venezolano adscrito al Ministerio del Poder Popular para el Proceso Social de Trabajo. Su misión es capacitar, formar y certificar a los trabajadores y trabajadoras venezolanas en diversas áreas técnicas y profesionales para contribuir al desarrollo económico y social del país.',
            ],
            [
                'category' => 'faq',
                'question' => '¿Cómo obtengo mi certificado al finalizar el curso?',
                'answer'   => 'Al completar el 100% del progreso del curso, el sistema generará automáticamente tu certificado de participación. Podrás descargarlo desde tu panel de estudiante, en la sección "Mis Logros". El certificado es emitido oficialmente por el INCES y tiene validez nacional.',
            ],
        ];

        foreach ($faqs as $faq) {
            KnowledgeBase::firstOrCreate(['question' => $faq['question']], $faq);
        }

        $this->command->info('✅ Base de datos poblada exitosamente.');
        $this->command->info('');
        $this->command->info('Credenciales de acceso:');
        $this->command->info('  Admin:      admin@inces.gob.ve     / password');
        $this->command->info('  Instructor: instructor@inces.gob.ve / password');
        $this->command->info('  Estudiante: estudiante@inces.gob.ve / password');
    }
}