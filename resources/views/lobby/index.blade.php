@extends('layouts.playLayout')

@section('title', 'Lobby de partida')

@section('content')
<section class="min-h-[100vh] bg-gradient-to-br from-emerald-800 via-emerald-900 to-gray-900 text-gray-100 py-10">
    <div class="mx-auto max-w-6xl px-6">

        {{--  Encabezado --}}
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-extrabold mb-2 tracking-tight">
                Lobby de <span class="text-emerald-300">Partida</span>
            </h1>
            <p class="text-sm text-emerald-200/80">Agregá jugadores, nombrá la sala y preparate para comenzar el trackeo.</p>
        </div>

        {{-- Mensajes --}}
        @if (session('ok'))
        <div class="mb-5 rounded-md border border-emerald-400/40 bg-emerald-800/40 p-3 text-sm text-emerald-200 backdrop-blur-md">
            <i class="bi bi-check-circle-fill mr-2"></i> {{ session('ok') }}
        </div>
        @endif
        @if ($errors->any())
        <div class="mb-5 rounded-md border border-red-400/40 bg-red-900/40 p-3 text-sm text-red-200 backdrop-blur-md">
            <i class="bi bi-exclamation-triangle-fill mr-2"></i> {{ $errors->first() }}
        </div>
        @endif

        {{--  GRID PRINCIPAL --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Formulario principal --}}
            <div class="lg:col-span-2 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-md shadow-md p-6">
                <h2 class="text-lg font-semibold mb-3 flex items-center gap-2 text-white">
                    <i class="bi bi-person-plus-fill text-emerald-400"></i>
                    Agregar jugador
                </h2>
                <form method="POST" action="{{ route('lobby.add') }}" class="flex flex-col sm:flex-row gap-3">
                    @csrf
                    <input
                        type="text"
                        name="identificador"
                        value="{{ old('identificador') }}"
                        placeholder="Ej: 12 • o • nacho_demo"
                        required
                        class="flex-1 rounded-md border border-white/10 bg-gray-800/70 px-3 py-2 text-white placeholder:text-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 outline-none transition" />
                    <button type="submit"
                        class="rounded-md bg-emerald-600 hover:bg-emerald-700 px-5 py-2 text-white font-medium shadow focus:ring-2 focus:ring-emerald-500 transition">
                        <i class="bi bi-plus-circle"></i> Agregar
                    </button>
                </form>
                <p class="mt-2 text-xs text-gray-400">
                    Se valida contra la tabla <code>usuarios</code> (por <code>id</code> o <code>nickname</code>). Máximo 6 jugadores.
                </p>
            </div>

            {{-- Acciones --}}
            <div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-md shadow-md p-6">
                <h2 class="text-lg font-semibold mb-3 flex items-center gap-2 text-white">
                    <i class="bi bi-gear text-amber-400"></i> Acciones
                </h2>

                {{-- Vaciar lista --}}
                <form method="POST" action="{{ route('lobby.clear') }}" class="mb-3">
                    @csrf
                    <button
                        class="w-full rounded-md border border-white/10 bg-gray-800/60 px-3 py-2 text-gray-200 hover:bg-gray-700/60 transition">
                        <i class="bi bi-trash3"></i> Vaciar lista
                    </button>
                </form>

                {{-- Crear partida --}}
                <form method="POST" action="{{ route('partidas.store') }}" class="space-y-3">
                    @csrf
                    <input
                        type="text"
                        name="nombre"
                        required
                        placeholder="Nombre de la sala (ej. ABCD-1234)"
                        class="w-full rounded-md border border-white/10 bg-gray-800/70 px-3 py-2 text-white placeholder:text-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 outline-none transition" />
                    <button
                        class="w-full rounded-md bg-emerald-600 hover:bg-emerald-700 px-3 py-2 text-white font-medium shadow focus:ring-2 focus:ring-emerald-500 transition">
                        <i class="bi bi-controller"></i> Crear partida
                    </button>
                </form>

                @if (count($jugadores) === 0)
                <p class="text-xs text-gray-400 mt-3">Agregá al menos 1 jugador para habilitar el trackeo.</p>
                @endif
            </div>
        </div>

        {{--  Lista de jugadores --}}
        <div class="mt-8 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-md shadow-md p-6">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold flex items-center gap-2 text-white">
                    <i class="bi bi-people-fill text-blue-400"></i> Jugadores en partida
                </h2>
                <span class="text-sm text-gray-400">{{ count($jugadores) }} / 6</span>
            </div>

            @if (empty($jugadores))
            <p class="mt-2 text-sm text-gray-400 italic">Aún no hay jugadores agregados.</p>
            @else
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($jugadores as $j)
                <div class="rounded-lg border border-white/10 bg-gray-800/60 p-3 flex items-center justify-between shadow-sm hover:bg-gray-800/80 transition">
                    <div>
                        <div class="text-sm font-medium text-white">
                            {{ $j['nombre'] ?: $j['nickname'] }}
                        </div>
                        <div class="text-xs text-gray-400">
                            ID: {{ $j['id'] }} · Usuario: {{ $j['nickname'] }}
                        </div>
                    </div>
                    <form method="POST" action="{{ route('lobby.remove', ['id' => $j['id']]) }}">
                        @csrf
                        <button
                            class="rounded-md border border-red-400/40 bg-red-900/40 text-red-200 px-3 py-1 text-xs hover:bg-red-800/60 focus:ring-2 focus:ring-red-500 transition">
                            <i class="bi bi-x-circle"></i> Quitar
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
            @endif
        </div>

    </div>
</section>
@endsection
