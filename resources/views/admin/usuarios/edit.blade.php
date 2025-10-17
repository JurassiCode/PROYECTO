@extends('layouts.adminLayout')

@section('title','Editar usuario')

@section('content')
<div class="rounded-lg border border-gray-200 bg-white shadow-sm">
  <div class="p-6">
    <h1 class="mb-4 flex items-center gap-2 text-lg font-semibold">
      <i class="bi bi-pencil-square text-blue-600"></i>
      <span>Editar usuario <span class="font-normal text-gray-500">#{{ $usuario->id }}</span></span>
    </h1>

    <form method="POST" action="{{ route('admin.usuarios.update', $usuario) }}" class="space-y-4">
      @csrf @method('PUT')

      {{-- Nombre --}}
      <div>
        <label class="mb-1 block text-sm font-semibold text-gray-700">Nombre</label>
        <input
          type="text" name="nombre" required
          value="{{ old('nombre', $usuario->nombre) }}"
          class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
        @error('nombre')
          <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
        @enderror
      </div>

      {{-- Nickname (usuario de login) --}}
      <div>
        <label class="mb-1 block text-sm font-semibold text-gray-700">Usuario (login)</label>
        <input
          type="text" name="nickname" required
          value="{{ old('nickname', $usuario->nickname) }}"
          class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
        @error('nickname')
          <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
        @enderror
      </div>

      {{-- Rol --}}
      <div>
        <label class="mb-1 block text-sm font-semibold text-gray-700">Rol</label>
        <select
          name="rol" required
          class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <option value="jugador" @selected(old('rol', $usuario->rol)==='jugador')>Jugador</option>
          <option value="admin"   @selected(old('rol', $usuario->rol)==='admin')>Admin</option>
        </select>
        @error('rol')
          <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
        @enderror
      </div>

      {{-- Contraseña --}}
      <div x-data="{ show:false }">
        <label class="mb-1 block text-sm font-semibold text-gray-700">
          Contraseña <small class="font-normal text-gray-500">(dejar vacío para no cambiar)</small>
        </label>
        <div class="relative">
          <input
            :type="show ? 'text' : 'password'"
            name="contrasena" id="contrasena"
            class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 pr-10 text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
          <button type="button"
                  @click="show = !show"
                  class="absolute inset-y-0 right-0 my-auto mr-1 inline-flex h-8 w-8 items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <i class="bi" :class="show ? 'bi-eye-slash' : 'bi-eye'"></i>
          </button>
        </div>
        @error('contrasena')
          <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
        @enderror
      </div>

      {{-- Repetir Contraseña --}}
      <div x-data="{ show:false }" class="mb-2">
        <label class="mb-1 block text-sm font-semibold text-gray-700">
          Repetir contraseña <small class="font-normal text-gray-500">(si ingresaste una nueva)</small>
        </label>
        <div class="relative">
          <input
            :type="show ? 'text' : 'password'"
            name="contrasena_confirmation" id="contrasena_confirmation"
            class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 pr-10 text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
          <button type="button"
                  @click="show = !show"
                  class="absolute inset-y-0 right-0 my-auto mr-1 inline-flex h-8 w-8 items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <i class="bi" :class="show ? 'bi-eye-slash' : 'bi-eye'"></i>
          </button>
        </div>
        @error('contrasena_confirmation')
          <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
        @enderror
      </div>

      {{-- Botones --}}
      <div class="flex gap-2 pt-2">
        <a href="{{ route('admin.usuarios.index') }}"
           class="inline-flex items-center justify-center gap-1.5 rounded-md border border-gray-300 bg-white px-4 py-2 text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-400">
          <i class="bi bi-arrow-left-circle"></i>
          <span>Volver</span>
        </a>
        <button
          class="inline-flex items-center justify-center gap-1.5 rounded-md bg-blue-600 px-4 py-2 text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
          <i class="bi bi-save"></i>
          <span>Actualizar</span>
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
