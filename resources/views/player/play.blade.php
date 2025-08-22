@extends('layouts.playLayout')

@section('title', 'Configurar partida')

@section('content')
    <div class="mx-auto max-w-3xl px-4 py-8">
        <div class="mb-6 flex items-center gap-3">
            <h1 class="text-2xl font-semibold text-gray-900">Configurar partida</h1>
        </div>

        @if (session('ok'))
            <div class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700">
                {{ session('ok') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 rounded-md border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Form -->
            <div class="lg:col-span-2 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <h2 class="text-sm font-semibold text-gray-700">Agregar jugador (ID o usuario)</h2>
                <form method="POST" action="{{ route('play.add') }}" class="mt-3 flex flex-col sm:flex-row gap-2">
                    @csrf
                    <input type="text" name="identificador" value="{{ old('identificador') }}"
                        placeholder="Ej: 12  •  o  •  nacho123" required
                        class="flex-1 rounded-md border border-gray-300 px-3 py-2 shadow-sm placeholder:text-gray-400 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600">
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-md bg-emerald-600 px-4 py-2 text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-600">
                        Agregar
                    </button>
                </form>

                <p class="mt-2 text-xs text-gray-500">
                    Se valida contra la tabla <code>usuarios</code> (por <code>id_usuario</code> o <code>usuario</code>).
                    Máximo 6.
                </p>
            </div>

            <!-- Acciones -->
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <h2 class="text-sm font-semibold text-gray-700">Acciones</h2>
                <div class="mt-3 grid grid-cols-1 gap-2">
                    <form method="POST" action="{{ route('play.clear') }}">
                        @csrf
                        <button
                            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm hover:bg-gray-50">
                            Vaciar lista
                        </button>
                    </form>
                    <form method="POST" action="{{ route('partidas.store') }}" class="space-y-2">
                        @csrf
                        <input type="text" name="nombre" required placeholder="Nombre de la sala (ej. ABCD-1234)"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm placeholder:text-gray-400 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600">
                        <button
                            class="w-full inline-flex items-center justify-center rounded-md bg-emerald-600 px-3 py-2 text-white text-sm shadow-sm hover:bg-emerald-700">
                            Crear partida
                        </button>
                    </form>

                    @if (count($jugadores) === 0)
                        <p class="text-xs text-gray-500">Agregá al menos 1 jugador para habilitar el trackeo.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Lista -->
        <div class="mt-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-700">Jugadores en partida</h2>
                <span class="text-xs text-gray-500">{{ count($jugadores) }} / 6</span>
            </div>

            @if (empty($jugadores))
                <p class="mt-3 text-sm text-gray-500">Aún no hay jugadores agregados.</p>
            @else
                <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach ($jugadores as $j)
                        <div
                            class="rounded-lg border border-gray-200 bg-white p-3 shadow-sm flex items-center justify-between">
                            <div>
                                <div class="text-sm font-medium">{{ $j['nombre'] ?: $j['usuario'] }}</div>
                                <div class="text-xs text-gray-500">ID: {{ $j['id_usuario'] }} · Usuario: {{ $j['usuario'] }}
                                </div>
                            </div>
                            <form method="POST" action="{{ route('play.remove', $j['id_usuario']) }}">
                                @csrf
                                <button
                                    class="rounded-md border border-red-300 px-2 py-1 text-xs text-red-700 hover:bg-red-50">
                                    Quitar
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
