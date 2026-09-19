@props([
    'name',
    'label',
    'value' => '',
    'type' => 'text',
    'required' => false,
    'placeholder' => '',
    'step' => null,
])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-slate-700 mb-1.5">
        {{ $label }} @if($required)<span class="text-rose-500">*</span>@endif
    </label>
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}"
           value="{{ old($name, $value) }}"
           @if($placeholder) placeholder="{{ $placeholder }}" @endif
           @if($step !== null) step="{{ $step }}" @endif
           @if($required) required @endif
           class="w-full rounded-xl border {{ $errors->has($name) ? 'border-rose-400 ring-2 ring-rose-100' : 'border-slate-300' }} bg-white px-4 py-2.5 text-slate-800 placeholder-slate-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none transition">
    @error($name)
        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
    @enderror
</div>
