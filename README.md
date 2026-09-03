# 🎓 INCES LMS — Plataforma de Aprendizaje con IA Conversacional y Matriz Vocacional

**Sistema completo de Gestión de Aprendizaje (LMS)** con Asistente Virtual de Inteligencia Artificial y **Matriz Vocacional de Competencias Técnicas** para el **Instituto Nacional de Capacitación y Educación Socialista (INCES)** de Venezuela.

---

## 🚀 Stack Tecnológico

| Capa | Tecnología |
|------|-----------|
| Backend | Laravel 11 (PHP 8.2+) |
| Base de Datos | PostgreSQL 14+ |
| Frontend | Blade + TailwindCSS CDN + Alpine.js |
| Iconografía | SVG Inline Vectors (Heroicons Design System) |
| IA / Chatbot | Google Gemini Pro API |
| Documentos | PdfParser, PhpWord, PhpSpreadsheet |
| Charts | Chart.js (CDN) |

---

## 📦 Módulos y Nuevas Funcionalidades Implementadas

### 👤 Roles de Usuario
| Rol | Acceso y Permisos |
|-----|-------------------|
| **Administrador** | Control total: usuarios, cursos, módulos, categorías, inscripción expresa, estadísticas, base de conocimiento IA |
| **Instructor / MTP** | Sus cursos, módulos, recursos didácticos, evaluación modular, notas cuantitativas y **Matriz Vocacional INCES** |
| **Estudiante** | Catálogo con prelaciones, inscripción, hoja de ruta, entrega de tareas por módulo, répes médicos, notas, chatbot IA |

---

### 🌟 Modelo Único Diferenciador: Matriz Vocacional de Habilidades Técnicas INCES

El sistema incluye un modelo de evaluación propio diseñado para la educación práctica e industrial del INCES:

1. **Calificación Cuantitativa (0-20 Pts)**:
   - Registro y visualización de notas numéricas otorgadas por el Maestro Técnico Productivo (MTP).
2. **Evaluación Cualitativa en 4 Dimensiones Técnicas (1 a 5 Estrellas)**:
   - 🛠️ **Destreza Técnica y Ejecución**: Habilidad operativa en el desarrollo de la tarea.
   - 📐 **Calidad y Presentación**: Estándar del acabado del proyecto entregado.
   - 🛡️ **Normas de Seguridad e Higiene**: Aplicación de medidas de protección laboral.
   - 💡 **Innovación y Criterio Práctico**: Capacidad de resolución autónoma de problemas.
3. **Insignias y Reconocimiento Especial INCES**:
   - Distintivos otorgados por el instructor (*Excelencia Técnica INCES*, *Cumplimiento de Estándar Industrial*, *Dominio Práctico Destacado*).

---

### 🧩 Entregables y Tareas por Módulos
- **Asignación Modular de Tareas**: Los estudiantes pueden subir entregables en PDF asociados a un módulo específico del curso.
- **Acceso Directo desde la Hoja de Ruta**: En la vista de detalle del curso, cada módulo cuenta con un botón directo `Subir Tarea del Módulo`.
- **Módulo de Récipes y Justificativos Médicos**: Subida de justificativos de salud con revisión y dictamen por parte del docente.

---

### 📚 Cursos, Prelaciones e Inscripción Expresa
- **Prerrequisitos y Prelaciones de Cursos**: Validación de cursos previos aprobados antes de poder inscribirse en módulos avanzados.
- **Inscripción Expresa (Administrador)**: Inscripción directa e inmediata de participantes por el administrador sin pasar por la solicitud estándar.
- **Evaluación Final y Cuestionarios (Quizzes)**: Exámenes cronometrados con porcentaje mínimo de aprobación para completar la formación.

---

### 🤖 Asistente Virtual IA & Búsqueda Inteligente
- **Chatbot con Gemini Pro API**: Respuestas en lenguaje natural fundamentadas en la base de conocimiento institucional y documentos cargados.
- **Búsqueda en Contenido de Documentos (Full-Text Search)**: Búsqueda indexada en archivos PDF, DOCX y PPTX subidos por los profesores.

---

## ⚡ Instalación en Linux

### 1. Prerrequisitos
```bash
# PHP 8.2 + extensiones necesarias
sudo apt install php8.2 php8.2-pgsql php8.2-mbstring php8.2-xml php8.2-zip php8.2-gd php8.2-curl php8.2-fileinfo

# Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# PostgreSQL
sudo apt install postgresql postgresql-contrib
sudo -u postgres psql -c "CREATE DATABASE inces_lms;"
sudo -u postgres psql -c "CREATE USER inces_user WITH PASSWORD 'secret';"
sudo -u postgres psql -c "GRANT ALL PRIVILEGES ON DATABASE inces_lms TO inces_user;"
```

### 2. Instalación y Configuración
```bash
# Instalar dependencias PHP
composer install

# Copiar y configurar entorno
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate

# Ejecutar migraciones
php artisan migrate

# Cargar datos iniciales y de prueba
php artisan db:seed

# Enlace de almacenamiento
php artisan storage:link

# Iniciar servidor
php artisan serve
```

---

## 🔑 Credenciales de Prueba

| Rol | Email | Contraseña |
|-----|-------|-----------|
| **Administrador** | `admin@inces.gob.ve` | `password` |
| **Instructor / MTP** | `instructor@inces.gob.ve` | `password` |
| **Estudiante** | `estudiante@inces.gob.ve` | `password` |

---

## 🛡️ Estructura de Base de Datos PostgreSQL

```sql
users                         -- Usuarios, roles y credenciales
categories                    -- Categorías de cursos
courses                       -- Cursos, prelaciones y niveles
modules                       -- Módulos o unidades temáticas
resources                     -- Recursos didácticos (PDF, Video, Docs)
enrollments                   -- Inscripciones a cursos y módulos
student_submissions           -- Tareas por módulo, récipes, notas (0-20 pts) y Matriz INCES (JSON)
quizzes / quiz_questions      -- Cuestionarios y evaluaciones finales
chatbot_conversations / msgs  -- Asistente virtual Gemini IA
knowledge_base                -- Base de conocimientos institucional
```

---

*Desarrollado con ❤️ para el INCES — Institución Nacional de Capacitación y Educación Socialista de Venezuela*
