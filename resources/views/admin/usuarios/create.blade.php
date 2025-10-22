@extends('layouts.adminLayout')

@section('title','Nuevo usuario')

@section('content')
<div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-10">
  <div class="rounded-xl border border-white/10 bg-gray-900/70 backdrop-blur-md shadow-xl overflow-hidden">
    <div class="px-6 py-5 border-b border-white/10">
      <h1 class="flex items-center gap-2 text-xl font-bold text-white">
        <i class="bi bi-person-plus-fill text-emerald-400"></i>
        <span>Nuevo usuario</span>
      </h1>
      <p class="text-sm text-emerald-200/80 mt-1">Creá un nuevo usuario con su rol y credenciales de acceso.</p>
    </div>

    <div class="p-6 text-gray-100">
      <form method="POST" action="{{ route('admin.usuarios.store') }}" class="space-y-5">
        @csrf

        {{-- Nombre --}}
        <div>
          <label class="mb-1 block text-sm font-semibold text-emerald-200">Nombre y apellido</label>
          <input
            type="text"
            name="nombre"
            required
            value="{{ old('nombre') }}"
            class="block w-full rounded-md border border-white/10 bg-gray-800/70 px-3 py-2 text-white placeholder:text-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 outline-none transition" />
          @error('nombre')
          <div class="mt-1 text-sm text-red-400">{{ $message }}</div>
          @enderror
        </div>

        {{-- Usuario --}}
        <div>
          <label class="mb-1 block text-sm font-semibold text-emerald-200">Usuario (nombre de login)</label>
          <input
            type="text"
            name="nickname"
            required
            value="{{ old('nickname') }}"
            class="block w-full rounded-md border border-white/10 bg-gray-800/70 px-3 py-2 text-white placeholder:text-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 outline-none transition" />
          @error('nickname')
          <div class="mt-1 text-sm text-red-400">{{ $message }}</div>
          @enderror
        </div>

        {{-- Rol --}}
        <div>
          <label class="mb-1 block text-sm font-semibold text-emerald-200">Rol</label>
          <select
            name="rol"
            required
            class="block w-full rounded-md border border-white/10 bg-gray-800/70 px-3 py-2 text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 outline-none transition">
            <option value="jugador" @selected(old('rol')==='jugador' )>Jugador</option>
            <option value="admin" @selected(old('rol')==='admin' )>Administrador</option>
          </select>
          @error('rol')
          <div class="mt-1 text-sm text-red-400">{{ $message }}</div>
          @enderror
        </div>

        {{-- Contraseña --}}
        <div x-data="{ show:false }">
          <label class="mb-1 block text-sm font-semibold text-emerald-200">Contraseña</label>
          <div class="relative">
            <input
              :type="show ? 'text' : 'password'"
              name="contrasena"
              id="contrasena"
              required
              class="block w-full rounded-md border border-white/10 bg-gray-800/70 px-3 py-2 pr-10 text-white placeholder:text-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 outline-none transition" />
            <button type="button"
              @click="show = !show"
              class="absolute inset-y-0 right-0 my-auto mr-2 inline-flex h-8 w-8 items-center justify-center rounded-md text-gray-400 hover:bg-gray-700/50 focus:ring-2 focus:ring-emerald-500 transition">
              <i class="bi" :class="show ? 'bi-eye-slash' : 'bi-eye'"></i>
            </button>
          </div>
          @error('contrasena')
          <div class="mt-1 text-sm text-red-400">{{ $message }}</div>
          @enderror
        </div>

        {{-- Repetir contraseña --}}
        <div x-data="{ show:false }">
          <label class="mb-1 block text-sm font-semibold text-emerald-200">Repetir contraseña</label>
          <div class="relative">
            <input
              :type="show ? 'text' : 'password'"
              name="contrasena_confirmation"
              id="contrasena_confirmation"
              required
              class="block w-full rounded-md border border-white/10 bg-gray-800/70 px-3 py-2 pr-10 text-white placeholder:text-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 outline-none transition" />
            <button type="button"
              @click="show = !show"
              class="absolute inset-y-0 right-0 my-auto mr-2 inline-flex h-8 w-8 items-center justify-center rounded-md text-gray-400 hover:bg-gray-700/50 focus:ring-2 focus:ring-emerald-500 transition">
              <i class="bi" :class="show ? 'bi-eye-slash' : 'bi-eye'"></i>
            </button>
          </div>
          @error('contrasena_confirmation')
          <div class="mt-1 text-sm text-red-400">{{ $message }}</div>
          @enderror
        </div>

        {{-- Botones --}}
        <div class="flex gap-3 pt-4">
          <a href="{{ route('admin.usuarios.index') }}"
            class="inline-flex items-center gap-1.5 rounded-md border border-white/10 bg-gray-800/60 px-4 py-2 text-gray-200 hover:bg-gray-700/60 focus:ring-2 focus:ring-gray-500 transition">
            <i class="bi bi-x-circle"></i> Cancelar
          </a>
          <button
            class="inline-flex items-center gap-1.5 rounded-md bg-emerald-600 hover:bg-emerald-700 px-4 py-2 text-white font-medium shadow focus:ring-2 focus:ring-emerald-500 transition">
            <i class="bi bi-check-lg"></i> Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection