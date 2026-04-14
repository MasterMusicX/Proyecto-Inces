<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear nueva contraseña</title>
</head>
<body>
    <div>
        <h2>Restablecer Contraseña</h2>

        @if ($errors->any())
            <div style="color: red;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" required readonly>
            </div>

            <div>
                <label for="password">Nueva Contraseña:</label>
                <input type="password" id="password" name="password" required autofocus>
            </div>

            <div>
                <label for="password_confirmation">Confirmar Nueva Contraseña:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <br>
            <button type="submit">
                Restablecer contraseña
            </button>
        </form>
    </div>
</body>
</html>