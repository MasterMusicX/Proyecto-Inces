<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Attendance;
use Barryvdh\DomPDF\Facade\Pdf; // 👈 Importamos el motor de PDFs

/**
 * Controlador de Reportes (ReportController)
 * * Se encarga de generar y exportar la documentación formal del INCES Campus,
 * como las listas de asistencia en formato PDF.
 */
class ReportController extends Controller
{
    /**
     * Genera y descarga el PDF de la lista de asistencia para un curso y fecha específicos.
     * * @param int $course_id ID del curso
     * @param string $date Fecha de la clase (YYYY-MM-DD)
     */
    public function downloadAttendance($course_id, $date)
    {
        // 1. Buscamos el curso (si no existe, tira error 404 de una vez)
        $course = Course::findOrFail($course_id);

        // 2. Buscamos todas las asistencias de ese curso en esa fecha específica
        // Usamos 'with('student')' para traernos los datos del chamo (nombre, cédula) y no saturar la BD
        $attendances = Attendance::with('student')
            ->where('course_id', $course_id)
            ->whereDate('date', $date)
            ->get();

        // 3. Verificamos si hay registros para evitar imprimir PDFs vacíos
        if ($attendances->isEmpty()) {
            return back()->with('error', 'No hay registros de asistencia para esta fecha.');
        }

        // 4. Cargamos la vista HTML (que luego crearemos) y le pasamos los datos
        // Esta vista estará en resources/views/pdf/attendance_list.blade.php
        $pdf = Pdf::loadView('pdf.attendance_list', compact('attendances', 'course', 'date'));

        // 5. Opcional: Configuramos el papel tamaño carta
        $pdf->setPaper('letter', 'portrait');

        // 6. Descargamos el archivo con un nombre formal para el MTP
        $fileName = "Asistencia_INCES_{$course->slug}_{$date}.pdf";
        return $pdf->download($fileName);
    }
}