<?php
namespace App\Console\Commands;

use App\Models\AiQuery;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Console\Command;

class LmsStats extends Command
{
    protected $signature   = 'lms:stats';
    protected $description = 'Display INCES LMS platform statistics';

    public function handle(): int
    {
        $this->newLine();
        $this->line('  <fg=blue>🎓 INCES LMS — Estadísticas de la Plataforma</>');
        $this->line('  ' . str_repeat('─', 45));

        $data = [
            ['Administradores',  User::where('role', 'admin')->count()],
            ['Instructores',     User::where('role', 'instructor')->count()],
            ['Estudiantes',      User::where('role', 'student')->count()],
            ['Cursos Totales',   Course::count()],
            ['Cursos Publicados',Course::where('status', 'published')->count()],
            ['Inscripciones',    Enrollment::count()],
            ['Recursos',         Resource::count()],
            ['Consultas IA',     AiQuery::count()],
        ];

        foreach ($data as [$label, $value]) {
            $this->line("  {$label}: <fg=green>{$value}</>");
        }

        $this->newLine();
        return Command::SUCCESS;
    }
}
