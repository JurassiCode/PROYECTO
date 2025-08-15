@extends('admin.layout')

@section('title','Usuarios')

@section('content')
<div x-data="{ open:false, del:{username:'', id:'', role:'', created:'', action:''} }" class="space-y-4">

  <!-- Header -->
  <div class="grid grid-cols-1 md:grid-cols-3 items-center gap-2">
    <h1 class="text-xl font-semibold flex items-center gap-2 text-gray-800 md:col-span-2">
      <i class="bi bi-people-fill"></i> <span>Gestión de usuarios</span>
    </h1>
    <a href="{{ route('admin.usuarios.create') }}"
      class="inline-flex items-center justify-center gap-2 rounded-md bg-emerald-600 px-4 py-2 text-white shadow-sm hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500">
      <i class="bi bi-person-plus-fill"></i> <span>Nuevo usuario</span>
    </a>
  </div>

  <!-- Card -->
  <div class="rounded-lg border border-gray-200 bg-white shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-600">
          <tr>
            <th class="px-4 py-3 text-left">ID</th>
            <th class="px-4 py-3 text-left">Nombre</th>
            <th class="px-4 py-3 text-left">Usuario</th>
            <th class="px-4 py-3 text-left">Rol</th>
            <th class="px-4 py-3 text-left">Creado</th>
            <th class="px-4 py-3 text-right">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse($usuarios as $u)
          @php
          $badge = match($u->rol) {
          'admin' => 'bg-blue-100 text-blue-800 ring-blue-200',
          'jugador' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
          default => 'bg-gray-100 text-gray-800 ring-gray-200'
          };
          @endphp
          <tr class="hover:bg-gray-50">
            <td class="px-4 py-3 font-semibold text-gray-900">{{ $u->id_usuario }}</td>
            <td class="px-4 py-3">{{ $u->nombre }}</td>
            <td class="px-4 py-3">{{ $u->usuario }}</td>
            <td class="px-4 py-3">
              <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $badge }}">
                {{ Str::upper($u->rol) }}
              </span>
            </td>
            <td class="px-4 py-3 text-gray-700">{{ \Carbon\Carbon::parse($u->creado_en)->format('Y-m-d H:i') }}</td>
            <td class="px-4 py-3">
              <div class="flex justify-end gap-2">
                <a href="{{ route('admin.usuarios.edit', $u) }}"
                  class="inline-flex h-9 w-9 items-center justify-center rounded-md border border-blue-200 text-blue-700 hover:bg-blue-50 focus:ring-2 focus:ring-blue-500"
                  title="Editar">
                  <i class="bi bi-pencil-square"></i>
                </a>
                <button type="button"
                  @click="
                            del = {
                              username: '{{ $u->usuario }}',
                              id: '{{ $u->id_usuario }}',
                              role: '{{ Str::upper($u->rol) }}',
                              created: '{{ \Carbon\Carbon::parse($u->creado_en)->format('Y-m-d H:i') }}',
                              action: '{{ route('admin.usuarios.destroy', $u) }}'
                            };
                            open = true;
                          "
                  class="inline-flex h-9 w-9 items-center justify-center rounded-md border border-red-200 text-red-700 hover:bg-red-50 focus:ring-2 focus:ring-red-500"
                  title="Eliminar">
                  <i class="bi bi-trash3-fill"></i>
                </button>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
              <div class="flex flex-col items-center">
                <i class="bi bi-emoji-frown text-3xl mb-2"></i>
                <span>No hay usuarios registrados</span>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-4">
      {{ $usuarios->links() }}
    </div>
  </div>

  <!-- Modal -->
  <div x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/40" @click="open = false"></div>
    <div x-transition.scale class="relative z-10 w-full max-w-md rounded-lg bg-white shadow-2xl overflow-hidden">
      <div class="px-5 py-4 text-white" style="background:linear-gradient(135deg,#dc3545,#6f1d1b)">
        <div class="flex items-center justify-between">
          <h5 class="text-base font-semibold flex items-center gap-2">
            <i class="bi bi-exclamation-octagon-fill"></i> Eliminar usuario
          </h5>
          <button @click="open = false" class="rounded p-1 hover:bg-white/10 focus:ring-2 focus:ring-white">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>
      </div>
      <div class="px-5 py-4 text-gray-800">
        <p>Estás por borrar al usuario <strong x-text="del.username"></strong> (ID <span x-text="del.id"></span>)</p>
        <ul class="mt-1 text-sm text-gray-600">
          <li>Rol: <span class="font-medium" x-text="del.role"></span></li>
          <li>Creado: <span class="font-medium" x-text="del.created"></span></li>
        </ul>
        <div class="mt-4 rounded-md border border-red-200 bg-red-50 px-3 py-2 text-red-800">
          Esta acción es irreversible.
        </div>
      </div>
      <div class="flex justify-end gap-2 px-5 pb-5">
        <button @click="open = false"
          class="rounded-md border border-gray-300 bg-white px-4 py-2 text-gray-700 hover:bg-gray-50 focus:ring-2 focus:ring-gray-400">
          Cancelar
        </button>
        <form :action="del.action" method="POST">
          @csrf @method('DELETE')
          <button type="submit"
            class="rounded-md bg-red-600 px-4 py-2 text-white hover:bg-red-700 focus:ring-2 focus:ring-red-500">
            Sí, eliminar
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
@endsection