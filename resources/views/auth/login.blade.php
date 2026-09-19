<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ingresar · {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            50:'#eef2ff',100:'#e0e7ff',200:'#c7d2fe',300:'#a5b4fc',
                            400:'#818cf8',500:'#6366f1',600:'#4f46e5',700:'#4338ca',
                            800:'#3730a3',900:'#312e81'
                        }
                    }
                }
            }
        }
    </script>
    <style>body{font-family:'Inter',sans-serif}</style>
</head>
<body class="h-full bg-slate-100">
<div class="min-h-full flex">

    <!-- Panel de marca -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-brand-600 via-brand-700 to-purple-800">
        <div class="absolute inset-0 opacity-20"
             style="background-image:radial-gradient(circle at 20% 30%, white 1px, transparent 1px);background-size:32px 32px"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute -top-16 -left-16 w-72 h-72 rounded-full bg-purple-400/20 blur-2xl"></div>

        <div class="relative z-10 flex flex-col justify-between p-12 text-white">
            <div class="flex items-center gap-3">
                <div class="h-12 w-12 rounded-2xl bg-white/15 backdrop-blur flex items-center justify-center">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h12A2.25 2.25 0 0120.25 6v2.25M3.75 6v12A2.25 2.25 0 006 20.25h12a2.25 2.25 0 002.25-2.25V6M3.75 12h16.5"/>
                    </svg>
                </div>
                <span class="text-xl font-extrabold tracking-tight">Taller Textil</span>
            </div>

            <div class="max-w-md">
                <h1 class="text-4xl font-extrabold leading-tight">Gestiona tu taller<br>de principio a fin.</h1>
                <p class="mt-4 text-brand-100 text-lg">Producción, inventario, pedidos, ventas y reportes en un solo sistema. Todo el control de tu negocio textil en un panel moderno.</p>
                <div class="mt-8 grid grid-cols-3 gap-4">
                    <div class="rounded-xl bg-white/10 backdrop-blur p-4">
                        <p class="text-2xl font-bold">+12</p>
                        <p class="text-xs text-brand-100">Módulos</p>
                    </div>
                    <div class="rounded-xl bg-white/10 backdrop-blur p-4">
                        <p class="text-2xl font-bold">24/7</p>
                        <p class="text-xs text-brand-100">Disponible</p>
                    </div>
                    <div class="rounded-xl bg-white/10 backdrop-blur p-4">
                        <p class="text-2xl font-bold">100%</p>
                        <p class="text-xs text-brand-100">Tu negocio</p>
                    </div>
                </div>
            </div>

            <p class="text-sm text-brand-200">© {{ date('Y') }} Taller Textil · Sistema de gestión</p>
        </div>
    </div>

    <!-- Formulario -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
        <div class="w-full max-w-md">
            <div class="lg:hidden flex items-center gap-3 mb-8">
                <div class="h-11 w-11 rounded-2xl bg-brand-600 flex items-center justify-center text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h12A2.25 2.25 0 0120.25 6v2.25M3.75 6v12A2.25 2.25 0 006 20.25h12a2.25 2.25 0 002.25-2.25V6M3.75 12h16.5"/>
                    </svg>
                </div>
                <span class="text-xl font-extrabold text-slate-800">Taller Textil</span>
            </div>

            <h2 class="text-2xl font-bold text-slate-800">Bienvenido de vuelta 👋</h2>
            <p class="mt-1 text-slate-500">Ingresa tus credenciales para acceder al sistema.</p>

            @if ($errors->any())
                <div class="mt-6 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Correo electrónico</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                            </svg>
                        </span>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                               placeholder="tucorreo@empresa.com"
                               class="w-full rounded-xl border border-slate-300 bg-white pl-11 pr-4 py-3 text-slate-800 placeholder-slate-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none transition">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Contraseña</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                            </svg>
                        </span>
                        <input id="password" name="password" type="password" required
                               placeholder="••••••••"
                               class="w-full rounded-xl border border-slate-300 bg-white pl-11 pr-11 py-3 text-slate-800 placeholder-slate-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none transition">
                        <button type="button" onclick="togglePwd()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                            <svg id="eye" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-slate-600 select-none">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                        Recordarme
                    </label>
                    <a href="#" class="text-sm font-medium text-brand-600 hover:text-brand-700">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit"
                        class="w-full rounded-xl bg-brand-600 py-3 font-semibold text-white shadow-lg shadow-brand-600/30 hover:bg-brand-700 active:scale-[.99] transition">
                    Ingresar al sistema
                </button>
            </form>

            <div class="mt-8 rounded-xl bg-slate-50 border border-slate-200 p-4 text-sm text-slate-500">
                <p class="font-semibold text-slate-600 mb-1">Cuenta de prueba</p>
                <p>admin@tallertextil.com · <span class="font-mono">password</span></p>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePwd() {
        const i = document.getElementById('password');
        i.type = i.type === 'password' ? 'text' : 'password';
    }
</script>
</body>
</html>
