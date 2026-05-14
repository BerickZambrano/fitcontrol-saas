<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitar Acceso</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 min-h-screen flex items-center justify-center p-6">




<div class="bg-white shadow-2xl rounded-2xl p-10 w-full max-w-4xl">
    @if(session('success'))
    <div 
        x-data="{ show: true }"
        x-show="show"
        x-transition
        x-init="setTimeout(() => show = false, 4000)"
        class="mb-6 bg-green-100 border border-green-300 text-green-800 px-6 py-4 rounded-xl shadow-md"
    >
        <div class="flex justify-between items-center">
            <span class="text-sm font-medium">
                {{ session('success') }}
            </span>
            <button @click="show = false" class="font-bold">
                ✕
            </button>
        </div>
    </div>
@endif

    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-blue-900">
            Solicitud de Acceso al Sistema
        </h2>
        <p class="text-gray-500 text-sm mt-2">
            Completa la información para solicitar tu espacio en la plataforma
        </p>
    </div>

    @if ($errors->any())
    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif  

    <form method="POST" action="{{ route('tenant.request.store') }}">
        @csrf

        <div class="grid md:grid-cols-2 gap-6">

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Nombre del Club *
                </label>
                <input type="text" name="nombre" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    NIT *
                </label>
                <input type="text" name="nit" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Tipo de Club *
                </label>
                <select name="tipo_club" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:outline-none transition">
                    <option value="">Seleccione</option>
                    <option value="formativo">Formativo</option>
                    <option value="amateur">Amateur</option>
                    <option value="profesional">Profesional</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Ciudad *
                </label>
                <input type="text" name="ciudad" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    País *
                </label>
                <input type="text" name="pais" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Email Corporativo *
                </label>
                <input type="email" name="email_corporativo" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Nombre del Encargado *
                </label>
                <input type="text" name="encargado_nombre" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Email del Encargado *
                </label>
                <input type="email" name="encargado_email" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:outline-none transition">
            </div>

        </div>

        <button type="submit"
            class="mt-8 w-full py-4 rounded-xl bg-blue-700 text-white font-semibold hover:bg-blue-800 transition shadow-lg">
            Enviar Solicitud
        </button>

        <a href="{{ url('/') }}"
   class="block text-center mt-4 text-blue-700 font-medium hover:text-blue-900 transition">
    ← Volver atrás
        </a>

    </form>

</div>

</body>
</html>