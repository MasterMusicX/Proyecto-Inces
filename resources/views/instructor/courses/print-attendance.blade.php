<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Asistencia - {{ $course->title }}</title>
    @vite('resources/css/app.css')
    <style>
        /* Reglas especiales para que la impresora no corte las tablas */
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; background-color: white !important; }
            .no-print { display: none !important; }
            @page { margin: 1.5cm; size: A4 portrait; }
        }
    </style>
</head>
<body class="bg-white text-black p-8 font-sans">

    <div class="no-print mb-8 flex justify-end">
        <button onclick="window.print()" class="bg-red-600 text-white px-6 py-2 rounded-lg font-bold shadow-md hover:bg-red-700 flex items-center gap-2">
            🖨️ Guardar como PDF / Imprimir
        </button>
    </div>

    <div class="flex items-center justify-between border-b-2 border-black pb-4 mb-6">
        <img src="{{ asset('images/Logo INCES.png') }}" alt="INCES" class="h-16 grayscale">
        <div class="text-right">
            <h1 class="text-xl font-black uppercase tracking-widest">Planilla de Asistencia</h1>
            <p class="text-sm font-bold text-gray-700">INCES Construcción - Estado Falcón</p>
        </div>
    </div>

    <div class="mb-8 grid grid-cols-2 gap-4 text-sm border border-black p-4 rounded-lg bg-gray-50">
        <div><span class="font-bold uppercase text-xs text-gray-600 block">Curso / Formación:</span> <span class="font-bold text-lg">{{ $course->title }}</span></div>
        <div><span class="font-bold uppercase text-xs text-gray-600 block">MTP / Instructor:</span> <span class="font-bold text-lg">{{ Auth::user()->name }}</span></div>
        <div><span class="font-bold uppercase text-xs text-gray-600 block">Fecha de Emisión:</span> {{ now()->format('d/m/Y') }}</div>
        <div><span class="font-bold uppercase text-xs text-gray-600 block">Total Estudiantes:</span> {{ $students->count() }}</div>
    </div>

    <table class="w-full text-left text-sm border-collapse border border-black">
        <thead class="bg-gray-100">
            <tr>
                <th class="border border-black px-3 py-2 text-center w-10">N°</th>
                <th class="border border-black px-3 py-2">Cédula</th>
                <th class="border border-black px-3 py-2">Apellidos y Nombres</th>
                <th class="border border-black px-3 py-2 text-center w-32">Firma del Estudiante</th>
                <th class="border border-black px-3 py-2 text-center w-16">Nota</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $student)
            <tr>
                <td class="border border-black px-3 py-2 text-center font-bold">{{ $index + 1 }}</td>
                <td class="border border-black px-3 py-2">V-{{ $student->cedula ?? 'N/A' }}</td>
                <td class="border border-black px-3 py-2 uppercase">{{ $student->name }}</td>
                <td class="border border-black px-3 py-2"></td>
                <td class="border border-black px-3 py-2 text-center font-bold">{{ $student->pivot->final_grade ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-16 pt-8 border-t border-black w-64 text-center mx-auto">
        <p class="font-bold uppercase text-xs">Firma del MTP</p>
        <p class="text-sm">{{ Auth::user()->name }}</p>
    </div>

</body>
</html>