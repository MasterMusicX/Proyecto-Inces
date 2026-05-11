<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Asistencia - {{ $course->title }}</title>
    <style>
        /* CSS Clásico pero con paleta de colores y espaciados tipo Tailwind */
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 13px;
            color: #374151; /* text-gray-700 */
            margin: 0;
            padding: 0;
        }
        
        /* Contenedor del encabezado usando display table para alinear logo y texto en domPDF */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #ef4444; /* text-red-500 (Rojo INCES) */
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .logo-container {
            width: 20%;
            text-align: left;
        }
        .logo-container img {
            max-width: 120px;
            height: auto;
        }
        .title-container {
            width: 80%;
            text-align: right;
        }
        .title-container h1 {
            font-size: 18px;
            font-weight: bold;
            color: #111827; /* text-gray-900 */
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }
        .title-container h2 {
            font-size: 14px;
            font-weight: normal;
            color: #6b7280; /* text-gray-500 */
            margin: 0;
        }

        /* Tarjeta de Información */
        .info-card {
            background-color: #f3f4f6; /* bg-gray-100 */
            border-radius: 6px; /* rounded-md */
            padding: 12px;
            margin-bottom: 25px;
        }
        .info-card table {
            width: 100%;
        }
        .info-card td {
            padding: 4px 0;
            font-size: 12px;
        }
        .label {
            font-weight: bold;
            color: #111827;
        }

        /* Tabla de Datos estilo moderno */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        .data-table th, .data-table td {
            border-bottom: 1px solid #e5e7eb; /* border-gray-200 */
            padding: 10px 8px;
            text-align: left;
        }
        .data-table th {
            background-color: #f9fafb; /* bg-gray-50 */
            color: #4b5563; /* text-gray-600 */
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .text-center { text-align: center; }
        
        /* Badges de estado */
        .badge {
            padding: 3px 8px;
            border-radius: 9999px; /* rounded-full */
            font-size: 10px;
            font-weight: bold;
        }
        .badge-presente { background-color: #d1fae5; color: #065f46; } /* bg-green-100 text-green-800 */
        .badge-inasistente { background-color: #fee2e2; color: #991b1b; } /* bg-red-100 text-red-800 */
        .badge-justificado { background-color: #fef3c7; color: #92400e; } /* bg-yellow-100 text-yellow-800 */

        /* Sección de Firmas */
        .signatures {
            width: 100%;
            margin-top: 60px;
        }
        .signatures td {
            width: 50%;
            text-align: center;
        }
        .sign-line {
            border-top: 1px solid #9ca3af; /* border-gray-400 */
            width: 70%;
            margin: 0 auto;
            padding-top: 8px;
            color: #4b5563;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="logo-container">
                <img src="{{ public_path('images/logo-inces.png') }}" alt="Logo INCES">
            </td>
            <td class="title-container">
                <h1>INCES Construcción</h1>
                <h2>Reporte Oficial de Asistencia Técnica</h2>
            </td>
        </tr>
    </table>

    <div class="info-card">
        <table>
            <tr>
                <td><span class="label">Formación:</span> {{ $course->title }}</td>
                <td style="text-align: right;"><span class="label">Fecha:</span> {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td><span class="label">Maestro Técnico Productivo:</span> {{ $course->instructor->name ?? 'No asignado' }} {{ $course->instructor->last_name ?? '' }}</td>
                <td style="text-align: right;"><span class="label">Total Participantes:</span> {{ $attendances->count() }}</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" width="5%">N°</th>
                <th class="text-center" width="15%">Cédula</th>
                <th width="40%">Participante</th>
                <th class="text-center" width="15%">Estatus</th>
                <th width="25%">Observación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $index => $row)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $row->student->cedula ?? 'N/A' }}</td>
                <td>{{ $row->student->name }} {{ $row->student->last_name }}</td>
                <td class="text-center">
                    @if($row->status == 'present')
                        <span class="badge badge-presente">PRESENTE</span>
                    @elseif($row->status == 'absent')
                        <span class="badge badge-inasistente">INASISTENTE</span>
                    @else
                        <span class="badge badge-justificado">JUSTIFICADO</span>
                    @endif
                </td>
                <td style="font-size: 11px; color: #6b7280;">
                    @if($row->status == 'justified' && $row->justification_status == 'approved')
                        Validado por coordinación
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="signatures">
        <tr>
            <td>
                <div class="sign-line">
                    <strong>Firma del MTP</strong><br>
                    {{ $course->instructor->name ?? 'Instructor' }} {{ $course->instructor->last_name ?? '' }}
                </div>
            </td>
            <td>
                <div class="sign-line">
                    <strong>Firma Coordinación</strong><br>
                    Sello y Firma
                </div>
            </td>
        </tr>
    </table>

</body>
</html>