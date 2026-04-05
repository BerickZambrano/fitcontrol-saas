<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Administrador</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 min-h-screen flex items-center justify-center">

    <div class="bg-white shadow-2xl rounded-2xl p-10 w-full max-w-md">

        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-blue-900">
                Crear Cuenta Administrador
            </h2>
            <p class="text-gray-500 text-sm mt-2">
                Configura el administrador principal del sistema
            </p>
        </div>

        <form method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Nombre
                </label>
                <input type="text" name="name" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Correo electrónico
                </label>
                <input type="email" name="email" required
                    value="{{ $tenant->email_corporativo }}"
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Contraseña
                </label>
                <input type="password" name="password" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Confirmar contraseña
                </label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:outline-none transition">
            </div>

            <button type="submit"
                class="w-full py-3 rounded-xl bg-blue-700 text-white font-semibold hover:bg-blue-800 transition shadow-lg">
                Crear Cuenta
            </button>
        </form>

        <p class="text-center text-xs text-gray-400 mt-6">
            Plataforma SaaS • FitControl
        </p>

    </div>

</body>
</html>