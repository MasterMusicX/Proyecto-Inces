<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GradeAssignedNotification extends Notification
{
    use Queueable;

    public $course;
    public $grade;
    public $isApproved;

    public function __construct($course, $grade, $isApproved)
    {
        $this->course = $course;
        $this->grade = $grade;
        $this->isApproved = $isApproved;
    }

    // Le decimos que se guarde en la Base de Datos (para que salga en la campanita)
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $status = $this->isApproved ? '¡Aprobaste!' : 'No aprobado.';
        return [
            'title' => 'Nota Actualizada 📝',
            'message' => "El instructor calificó tu desempeño en {$this->course->title}. Nota: {$this->grade}. {$status}",
            'url' => route('student.courses.show', $this->course->slug)
        ];
    }
}