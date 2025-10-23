@extends('layouts.adminLayout')

@section('title','Usuarios')

@section('content')
<div
  x-data="{ open:false, del:{username:'', id:'', role:'', created:'', action:''} }"
  class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10 text-gray-100">

  <!-- Header -->
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div>
      <h1 class="text-2xl font-bold flex items-center gap-2 text-white">
        <i class="bi bi-people-fill text-amber-400"></i> Gestión de usuarios
      </h1>
      <p class="text-sm text-emerald-200/70">Visualizá, editá o eliminá usuarios del sistema.</p>
    </div>
    <a href="{{ route('admin.usuarios.create') }}"
      class="inline-flex items-center gap-2 rounded-md bg-emerald-600 hover:bg-emerald-700 px-4 py-2 font-medium text-white shadow-sm transition">
      <i class="bi bi-person-plus-fill"></i> Nuevo usuario
    </a>
  </div>

  <!-- Tabla -->
  <div class="rounded-xl overflow-hidden border border-white/10 bg-gray-900/60 backdrop-blur-md shadow-lg">
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm text-gray-200">
        <thead class="bg-emerald-800/70 text-emerald-100 uppercase text-xs font-semibold tracking-wider">
          <tr>
            <th class="px-4 py-3 text-left">ID</th>
            <th class="px-4 py-3 text-left">Nombre</th>
            <th class="px-4 py-3 text-left">Usuario</th>
            <th class="px-4 py-3 text-left">Rol</th>
            <th class="px-4 py-3 text-left">Creado</th>
            <th class="px-4 py-3 text-right">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/10">
          @forelse($usuarios as $u)
          @php
          $badge = match($u->rol) {
          'admin' => 'bg-amber-500/20 text-amber-300 border border-amber-500/30',
          'jugador' => 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30'
          };
          @endphp
          <tr class="hover:bg-emerald-800/10 transition">
            <td class="px-4 py-3 font-semibold">{{ $u->id }}</td>
            <td class="px-4 py-3">{{ $u->nombre }}</td>
            <td class="px-4 py-3">{{ $u->nickname }}</td>
            <td class="px-4 py-3">
              <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $badge }}">
                {{ Str::upper($u->rol) }}
              </span>
            </td>
            <td class="px-4 py-3 text-emerald-100/80">
              {{ \Carbon\Carbon::parse($u->creado_en)->format('Y-m-d H:i') }}
            </td>
            <td class="px-4 py-3">
              <div class="flex justify-end gap-2">
                <!-- Editar -->
                <a href="{{ route('admin.usuarios.edit', $u) }}"
                  class="inline-flex h-9 w-9 items-center justify-center rounded-md border border-blue-400/30 text-blue-300 hover:bg-blue-500/10 focus:ring-2 focus:ring-blue-400/40 transition"
                  title="Editar">
                  <i class="bi bi-pencil-square"></i>
                </a>
                <!-- Eliminar -->
                <button type="button"
                  @click="
                      del = {
                        username: '{{ $u->nombre }}',
                        id: '{{ $u->id }}',
                        role: '{{ Str::upper($u->rol) }}',
                        created: '{{ \Carbon\Carbon::parse($u->creado_en)->format('Y-m-d H:i') }}',
                        action: '{{ route('admin.usuarios.destroy', $u) }}'
                      };
                      open = true;
                    "
                  class="inline-flex h-9 w-9 items-center justify-center rounded-md border border-red-400/30 text-red-300 hover:bg-red-500/10 focus:ring-2 focus:ring-red-400/40 transition"
                  title="Eliminar">
                  <i class="bi bi-trash3-fill"></i>
                </button>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="px-4 py-10 text-center text-emerald-100/70">
              <i class="bi bi-emoji-frown text-3xl mb-2"></i><br>
              No hay usuarios registrados.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Paginación -->
    <div class="p-4 border-t border-white/10">
      {{ $usuarios->links() }}
    </div>
  </div>

  <!-- Modal de eliminación -->
  <div x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="open=false"></div>

    <div x-transition.scale
      class="relative z-10 w-full max-w-md rounded-xl border border-white/10 bg-gray-900/90 text-gray-100 shadow-2xl overflow-hidden">
      <!-- Header -->
      <div class="flex items-center justify-between px-5 py-4 bg-gradient-to-r from-red-700 to-red-600">
        <h5 class="text-base font-semibold flex items-center gap-2">
          <i class="bi bi-exclamation-octagon-fill text-white"></i> Confirmar eliminación
        </h5>
        <button @click="open=false"
          class="rounded p-1 hover:bg-white/10 focus:ring-2 focus:ring-white/30">
          <i class="bi bi-x-lg"></i>
        </button>
      </div>

      <!-- Contenido -->
      <div class="px-6 py-5">
        <p class="mb-1">¿Seguro que querés eliminar a <strong x-text="del.username"></strong> (ID <span x-text="del.id"></span>)?</p>
        <ul class="text-sm text-gray-300 space-y-0.5 mb-4">
          <li>Rol: <span class="font-semibold text-emerald-300" x-text="del.role"></span></li>
          <li>Creado: <span class="font-semibold text-emerald-300" x-text="del.created"></span></li>
        </ul>
        <div class="rounded-md border border-red-500/30 bg-red-500/10 px-3 py-2 text-sm text-red-200">
          Esta acción es irreversible. El usuario será eliminado permanentemente.
        </div>
      </div>

      <!-- Botones -->
      <div class="flex justify-end gap-3 px-6 pb-6">
        <button @click="open=false"
          class="rounded-md border border-gray-400/30 bg-gray-800 px-4 py-2 text-gray-200 hover:bg-gray-700 focus:ring-2 focus:ring-gray-400/50 transition">
          Cancelar
        </button>
        <form :action="del.action" method="POST">
          @csrf @method('DELETE')
          <button type="submit"
            class="rounded-md bg-red-600 px-4 py-2 text-white hover:bg-red-700 focus:ring-2 focus:ring-red-500/60 transition">
            Sí, eliminar
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection