<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel') · {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: { extend: {
                fontFamily: { sans: ['Inter','sans-serif'] },
                colors: { brand: {
                    50:'#eef2ff',100:'#e0e7ff',200:'#c7d2fe',300:'#a5b4fc',400:'#818cf8',
                    500:'#6366f1',600:'#4f46e5',700:'#4338ca',800:'#3730a3',900:'#312e81'
                }}
            }}
        }
    </script>
    <style>
        body{font-family:'Inter',sans-serif}
        ::-webkit-scrollbar{width:8px;height:8px}
        ::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:8px}
        #sidebar ::-webkit-scrollbar-thumb{background:rgba(255,255,255,.15)}
        .nav-link.active{background:linear-gradient(135deg,#6366f1,#a855f7);color:#fff;box-shadow:0 10px 22px -8px rgba(139,92,246,.75)}
        .nav-link.active svg{color:#fff}
    </style>
</head>
<body class="h-full bg-slate-100 text-slate-700">
@php
    $groups = [
        'Principal' => [
            ['route'=>'dashboard','label'=>'Dashboard','icon'=>'home'],
        ],
        'Operaciones' => [
            ['route'=>'pedidos.index','label'=>'Pedidos','icon'=>'clipboard'],
            ['route'=>'produccion.index','label'=>'Producción','icon'=>'cog'],
            ['route'=>'clientes.index','label'=>'Clientes','icon'=>'users'],
        ],
        'Inventario' => [
            ['route'=>'productos.index','label'=>'Productos','icon'=>'tag'],
            ['route'=>'materiaprima.index','label'=>'Materia Prima','icon'=>'cube'],
            ['route'=>'almacen.index','label'=>'Almacén','icon'=>'archive'],
        ],
        'Comercial' => [
            ['route'=>'ventas.index','label'=>'Ventas','icon'=>'cash'],
            ['route'=>'compras.index','label'=>'Compras','icon'=>'cart'],
            ['route'=>'proveedores.index','label'=>'Proveedores','icon'=>'truck'],
        ],
        'Administración' => [
            ['route'=>'empleados.index','label'=>'Empleados','icon'=>'badge'],
            ['route'=>'reportes.index','label'=>'Reportes','icon'=>'chart'],
            ['route'=>'usuarios.index','label'=>'Usuarios','icon'=>'shield'],
            ['route'=>'configuracion.index','label'=>'Configuración','icon'=>'settings'],
        ],
    ];

    $icons = [
        'home'=>'M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.5a.75.75 0 00.75.75h4.5v-6a.75.75 0 01.75-.75h3a.75.75 0 01.75.75v6h4.5a.75.75 0 00.75-.75V9.75',
        'clipboard'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'cog'=>'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
        'users'=>'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z',
        'tag'=>'M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z M6 6h.008v.008H6V6z',
        'cube'=>'M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9',
        'archive'=>'M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z',
        'cash'=>'M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0z',
        'cart'=>'M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z',
        'truck'=>'M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12',
        'badge'=>'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z',
        'chart'=>'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z',
        'shield'=>'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z',
        'settings'=>'M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.855.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.855-.108-1.204l-.526-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
    ];
@endphp

<div class="min-h-full flex" x-data>
    <!-- Overlay móvil -->
    <div id="overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/40 z-30 hidden lg:hidden"></div>

    <!-- SIDEBAR -->
    <aside id="sidebar" class="fixed lg:sticky top-0 z-40 h-screen w-72 shrink-0 -translate-x-full lg:translate-x-0 transition-transform bg-gradient-to-b from-[#1e1b4b] via-[#2a2568] to-[#3b0d63] border-r border-white/5 flex flex-col">
        <div class="h-16 flex items-center gap-3 px-6 border-b border-white/10">
            <div class="h-10 w-10 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center text-white shadow-lg">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h12A2.25 2.25 0 0120.25 6v2.25M3.75 6v12A2.25 2.25 0 006 20.25h12a2.25 2.25 0 002.25-2.25V6M3.75 12h16.5"/>
                </svg>
            </div>
            <div>
                <p class="font-extrabold text-white leading-tight">Taller Textil</p>
                <p class="text-xs text-indigo-200/80">Sistema de gestión</p>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-6">
            @foreach ($groups as $groupName => $items)
                <div>
                    <p class="px-3 mb-2 text-[11px] font-semibold uppercase tracking-wider text-indigo-300/70">{{ $groupName }}</p>
                    <div class="space-y-1">
                        @foreach ($items as $item)
                            @php $active = Route::has($item['route']) && request()->routeIs($item['route']); @endphp
                            <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                               class="nav-link flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-indigo-100 hover:bg-white/10 transition {{ $active ? 'active' : '' }}">
                                <svg class="h-5 w-5 shrink-0 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$item['icon']] }}"/>
                                </svg>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </nav>

        <div class="p-4 border-t border-white/10">
            <div class="rounded-2xl bg-white/10 ring-1 ring-white/10 p-4 text-white">
                <p class="text-sm font-semibold">¿Necesitas ayuda?</p>
                <p class="text-xs text-indigo-200/80 mt-1">Consulta la guía del sistema.</p>
                <button class="mt-3 w-full rounded-lg bg-white/15 hover:bg-white/25 py-2 text-xs font-semibold transition">Ver documentación</button>
            </div>
        </div>
    </aside>

    <!-- CONTENIDO -->
    <div class="flex-1 min-w-0 flex flex-col">
        <!-- TOPBAR -->
        <header class="sticky top-0 z-20 h-16 bg-white/80 backdrop-blur border-b border-slate-200 flex items-center gap-4 px-4 sm:px-6">
            <button onclick="toggleSidebar()" class="lg:hidden text-slate-500 hover:text-slate-700">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
            </button>

            <div class="relative hidden sm:block flex-1 max-w-md">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                </span>
                <input type="text" placeholder="Buscar pedidos, clientes, productos…"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 py-2.5 text-sm focus:bg-white focus:border-brand-400 focus:ring-2 focus:ring-brand-100 outline-none transition">
            </div>

            <div class="flex-1 sm:hidden"></div>

            <div class="flex items-center gap-2 sm:gap-3">
                <button class="relative h-10 w-10 rounded-xl hover:bg-slate-100 flex items-center justify-center text-slate-500">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                    <span class="absolute top-2 right-2.5 h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                </button>

                <div class="relative" x-data="{open:false}">
                    <button onclick="toggleMenu('userMenu')" class="flex items-center gap-2.5 rounded-xl hover:bg-slate-100 pl-1.5 pr-2 py-1.5">
                        <span class="h-9 w-9 rounded-full bg-gradient-to-br from-brand-500 to-purple-600 text-white text-sm font-semibold flex items-center justify-center">{{ auth()->user()->initials() }}</span>
                        <span class="hidden sm:block text-left">
                            <span class="block text-sm font-semibold text-slate-700 leading-tight">{{ auth()->user()->name }}</span>
                            <span class="block text-xs text-slate-400 capitalize">{{ auth()->user()->role }}</span>
                        </span>
                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    </button>
                    <div id="userMenu" class="hidden absolute right-0 mt-2 w-52 rounded-xl bg-white shadow-xl ring-1 ring-slate-200 py-1.5 z-30">
                        <div class="px-4 py-2 border-b border-slate-100">
                            <p class="text-sm font-semibold text-slate-700">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-400">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('configuracion.index') }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Mi perfil</a>
                        <a href="{{ route('configuracion.index') }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Configuración</a>
                        <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100 mt-1 pt-1">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Cerrar sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            @if (session('ok'))
                <div class="max-w-7xl mx-auto mb-5 flex items-center gap-3 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('ok') }}
                </div>
            @endif
            @if (session('error'))
                <div class="max-w-7xl mx-auto mb-5 flex items-center gap-3 rounded-xl bg-rose-50 border border-rose-200 px-4 py-3 text-sm text-rose-700">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

<script>
    function toggleSidebar(){
        document.getElementById('sidebar').classList.toggle('-translate-x-full');
        document.getElementById('overlay').classList.toggle('hidden');
    }
    function toggleMenu(id){
        document.getElementById(id).classList.toggle('hidden');
    }
    document.addEventListener('click', function(e){
        const menu = document.getElementById('userMenu');
        if(menu && !menu.classList.contains('hidden') && !e.target.closest('[onclick^="toggleMenu"]') && !e.target.closest('#userMenu')){
            menu.classList.add('hidden');
        }
    });
</script>
@yield('scripts')
</body>
</html>
