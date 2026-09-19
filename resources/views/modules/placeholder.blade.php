@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-400">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600">Inicio</a>
        <span>/</span>
        <span class="text-slate-600 font-medium">{{ $title }}</span>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">{{ $title }}</h1>
            <p class="text-slate-500">{{ $description }}</p>
        </div>
        <button class="rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/30 hover:bg-brand-700">+ Nuevo registro</button>
    </div>

    <!-- Tarjetas de resumen del módulo -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach (($stats ?? []) as $s)
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100">
                <p class="text-sm text-slate-500">{{ $s['label'] }}</p>
                <p class="mt-1 text-2xl font-bold text-slate-800">{{ $s['value'] }}</p>
            </div>
        @endforeach
    </div>

    <!-- Área de contenido -->
    <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 p-10">
        <div class="max-w-md mx-auto text-center py-10">
            <div class="mx-auto h-16 w-16 rounded-2xl bg-brand-50 flex items-center justify-center text-brand-500">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z"/>
                </svg>
            </div>
            <h3 class="mt-5 text-lg font-semibold text-slate-800">Módulo en construcción</h3>
            <p class="mt-2 text-slate-500">{{ $description }} Este módulo está listo en el menú y se implementará con su CRUD completo en la siguiente fase del proyecto.</p>
            <a href="{{ route('dashboard') }}" class="mt-6 inline-block rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">← Volver al dashboard</a>
        </div>
    </div>
</div>
@endsection
