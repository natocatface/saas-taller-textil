@extends('layouts.app')

@section('title', 'Configuración')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-400">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600">Inicio</a><span>/</span>
        <span class="text-slate-600 font-medium">Configuración</span>
    </div>

    <div>
        <h1 class="text-2xl font-bold text-slate-800">Configuración</h1>
        <p class="text-slate-500">Datos de la empresa y parámetros del sistema.</p>
    </div>

    <form method="POST" action="{{ route('configuracion.update') }}" class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 p-6 sm:p-8 space-y-5">
        @csrf
        @method('PUT')

        <div class="flex items-center gap-4 pb-5 border-b border-slate-100">
            <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-brand-500 to-purple-600 flex items-center justify-center text-white shadow-lg">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h12A2.25 2.25 0 0120.25 6v2.25M3.75 6v12A2.25 2.25 0 006 20.25h12a2.25 2.25 0 002.25-2.25V6M3.75 12h16.5"/></svg>
            </div>
            <div>
                <p class="font-semibold text-slate-800">Datos de la empresa</p>
                <p class="text-sm text-slate-500">Se usan en comprobantes y reportes.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="sm:col-span-2">
                <x-field name="empresa" label="Nombre / Razón social" :value="$config->empresa" required />
            </div>
            <x-field name="ruc" label="RUC" :value="$config->ruc" />
            <x-field name="telefono" label="Teléfono" :value="$config->telefono" />
            <x-field name="email" label="Email" type="email" :value="$config->email" />
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Moneda <span class="text-rose-500">*</span></label>
                <select name="moneda" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
                    @foreach(['PEN'=>'Soles (PEN)','USD'=>'Dólares (USD)'] as $val=>$lbl)
                        <option value="{{ $val }}" @selected(old('moneda', $config->moneda)===$val)>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>
            <x-field name="igv" label="IGV (%)" type="number" step="0.01" :value="$config->igv" required />
            <div class="sm:col-span-2">
                <x-field name="direccion" label="Dirección" :value="$config->direccion" />
            </div>
        </div>

        <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
            <button type="submit" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/30 hover:bg-brand-700">Guardar cambios</button>
        </div>
    </form>

    <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 p-6 sm:p-8">
        <p class="font-semibold text-slate-800 mb-3">Acerca del sistema</p>
        <dl class="grid grid-cols-2 gap-y-2 text-sm">
            <dt class="text-slate-500">Sistema</dt><dd class="text-slate-700 font-medium">Taller Textil</dd>
            <dt class="text-slate-500">Versión</dt><dd class="text-slate-700 font-medium">1.0</dd>
            <dt class="text-slate-500">Framework</dt><dd class="text-slate-700 font-medium">Laravel 12 · MySQL</dd>
        </dl>
    </div>
</div>
@endsection
