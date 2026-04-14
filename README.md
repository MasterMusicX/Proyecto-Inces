# 🎓 INCES LMS — Plataforma de Aprendizaje con IA Conversacional

**Sistema completo de Gestión de Aprendizaje (LMS)** con Asistente Virtual de Inteligencia Artificial para el **Instituto Nacional de Capacitación y Educación Socialista (INCES)** de Venezuela.

---

## 🚀 Stack Tecnológico

| Capa | Tecnología |
|------|-----------|
| Backend | Laravel 11 (PHP 8.2+) |
| Base de Datos | PostgreSQL 14+ |
| Frontend | Blade + TailwindCSS CDN + Alpine.js |
| IA / Chatbot | Google Gemini Pro API |
| Documentos | PdfParser, PhpWord, PhpSpreadsheet |
| Charts | Chart.js (CDN) |

---

## 📦 Módulos Implementados

### 👤 3 Roles de Usuario
| Rol | Acceso |
|-----|--------|
| **Administrador** | Control total: usuarios, cursos, módulos, categorías, estadísticas, base de conocimiento IA |
| **Instructor** | Sus cursos, módulos, recursos, progreso de estudiantes |
| **Estudiante** | Catálogo, inscripción, aprendizaje, chatbot, búsqueda, perfil |

### 📚 Módulos Funcionales

**Panel Administrador**
- Dashboard con estadísticas en tiempo real
- Gráficos de registros, inscripciones y uso de IA (Chart.js)
- CRUD completo de usuarios con roles y estado
- CRUD completo de cursos con módulos anidados
- Gestión de categorías con colores
- **Base de Conocimiento IA** — FAQ que el chatbot consulta automáticamente

**Panel Instructor**
- Vista de sus cursos con estadísticas
- Gestión de módulos por curso
- Subida de recursos didácticos (PDF, DOCX, XLSX, PPTX, Video, URL, Imagen)
- Lista de estudiantes con progreso
- Análisis IA automático en background

**Panel Estudiante**
- Dashboard personalizado con progreso
- Catálogo de cursos con filtros
- Visualizador de recursos integrado (PDF, video, imagen)
- **Preguntar a la IA sobre cualquier documento**
- Perfil con cambio de foto y contraseña

**Asistente Virtual IA**
- Chatbot conversacional con Gemini Pro
- Historial de conversaciones
- Sugerencias rápidas
- Respuestas enriquecidas (markdown formateado)

**Búsqueda Inteligente**
- Búsqueda en cursos, recursos y documentos analizados
- Debounce y resultados en tiempo real
- Integración con análisis de documentos IA

---

## ⚡ Instalación en Linux

### 1. Prerrequisitos
```bash
# PHP 8.2 + extensiones
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

### 2. Instalación del Proyecto
```bash
# Instalar dependencias PHP
composer install

# Copiar y configurar entorno
cp .env.example .env

# Editar configuración (DB + Gemini API Key)
nano .env

# Generar clave de aplicación
php artisan key:generate

# Ejecutar migraciones y datos de prueba
php artisan migrate --seed

# Crear enlace de almacenamiento público
php artisan storage:link

# Iniciar servidor de desarrollo
php artisan serve
```

### 3. Para producción con Cola de Trabajos (procesamiento de documentos)
```bash
# En una terminal separada o configurar como servicio systemd
php artisan queue:work --queue=default --tries=3 --timeout=120
```

---

## 🔑 Credenciales de Prueba

| Rol | Email | Contraseña |
|-----|-------|-----------|
| Administrador | admin@inces.gob.ve | password |
| Instructor | instructor@inces.gob.ve | password |
| Estudiante | estudiante@inces.gob.ve | password |

**URL:** `http://localhost:8000`

---

## 🔑 Configuración Gemini AI

En el archivo `.env`:
```env
GEMINI_API_KEY=AIzaSy...tu_clave_aqui
GEMINI_MODEL=gemini-pro
GEMINI_MAX_TOKENS=2048
GEMINI_TEMPERATURE=0.7
```
➡ Obtén tu API Key gratis: https://makersuite.google.com/app/apikey

---

## 🛠️ Comandos Artisan Personalizados

```bash
# Ver estadísticas de la plataforma
php artisan lms:stats

# Analizar todos los documentos sin procesar con IA
php artisan lms:analyze-documents

# Forzar re-análisis de todos los documentos
php artisan lms:analyze-documents --force
```

---

## 📁 Estructura del Proyecto

```
inces-lms/
├── app/
│   ├── Console/Commands/
│   │   ├── AnalyzeAllDocuments.php    # Analiza documentos pendientes
│   │   └── LmsStats.php              # Estadísticas por consola
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                 # Dashboard, Users, Courses, Modules, Categories, Stats, KB
│   │   │   ├── Auth/                  # Login, Register, Logout
│   │   │   ├── Instructor/            # Dashboard, Courses, Resources
│   │   │   ├── Student/               # Dashboard, Courses, Resources, Profile
│   │   │   └── Api/                   # Chatbot, Search, Document AI
│   │   └── Middleware/               # RoleMiddleware, CheckActive
│   ├── Jobs/
│   │   └── ProcessDocumentJob.php    # Background job para análisis IA
│   ├── Models/                       # 12 modelos Eloquent
│   ├── Policies/
│   │   └── CoursePolicy.php         # Autorización de cursos
│   └── Services/
│       ├── GeminiService.php        # Cliente Gemini Pro API
│       ├── ChatbotService.php       # Lógica del chatbot
│       └── DocumentProcessorService.php # Extracción de texto
├── database/
│   ├── migrations/                  # 7 archivos de migración
│   └── seeders/DatabaseSeeder.php   # Datos demo completos
├── resources/views/
│   ├── admin/                       # Dashboard, users, courses, modules, categories, stats, KB
│   ├── auth/                        # Login, register
│   ├── chatbot/                     # Asistente virtual con historial
│   ├── components/                  # Sidebar, Navbar
│   ├── errors/                      # 403, 404
│   ├── instructor/                  # Dashboard, cursos, módulos, recursos, estudiantes
│   ├── layouts/                     # App layout (dark/light), Auth layout
│   └── student/                     # Dashboard, perfil, cursos, recursos, búsqueda
└── routes/
    ├── web.php                      # Todas las rutas web por rol
    └── api.php                      # API REST (chatbot, búsqueda, IA)
```

---

## 🛡️ Seguridad Implementada

- ✅ Autenticación Laravel nativa con hash bcrypt
- ✅ Protección CSRF en todos los formularios
- ✅ Middleware de roles (admin/instructor/student)
- ✅ Middleware de usuario activo (CheckActive)
- ✅ Políticas de autorización (CoursePolicy)
- ✅ Validación de tipos y tamaño de archivos
- ✅ Soft deletes para auditoría de datos
- ✅ Rate limiting en API endpoints
- ✅ Sanitización de inputs XSS

---

## 📊 Base de Datos PostgreSQL

```sql
users                  -- Usuarios del sistema
categories             -- Categorías de cursos
courses                -- Cursos educativos
modules                -- Módulos/unidades
enrollments            -- Inscripciones
course_progress        -- Progreso por recurso
resources              -- Materiales didácticos
document_analysis      -- Análisis IA (con tsvector para búsqueda full-text)
resource_views         -- Tracking de visualizaciones
ai_queries             -- Historial de consultas al chatbot
chatbot_conversations  -- Conversaciones del chatbot
chatbot_messages       -- Mensajes individuales
knowledge_base         -- FAQ y base de conocimiento INCES
```

---

*Desarrollado con ❤️ para el INCES — Institución Nacional de Capacitación y Educación Socialista de Venezuela*
