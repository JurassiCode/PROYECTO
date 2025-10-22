@extends('layouts.publicLayout')

@section('title', 'Documentación')

@section('content')
<section class="min-h-[80vh] flex flex-col bg-gradient-to-br from-emerald-800 via-emerald-900 to-gray-900 text-gray-100">
  <div class="flex-grow">
    <div class="mx-auto max-w-7xl px-6 lg:px-8 py-12">

      {{-- Header --}}
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
          <h1 class="text-3xl font-bold flex items-center gap-3">
            <i class="bi bi-folder2-open text-amber-400"></i>
            <span>Documentación JurassiDraft</span>
          </h1>
          <p class="text-sm text-emerald-200/80 mt-1">
            Explorá los materiales, entregas y recursos del proyecto.
          </p>
        </div>

        {{-- Volver (si hay path relativo) --}}
        @if ($relativePath)
        <a href="{{ route('documentacion', dirname($relativePath)) }}"
          class="inline-flex items-center gap-2 rounded-md border border-white/10 bg-gray-800/60 hover:bg-gray-700/60 px-4 py-2 text-sm font-medium text-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
          <i class="bi bi-arrow-left-circle"></i> Volver
        </a>
        @endif
      </div>

      {{-- Grid --}}
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($items as $item)
        @php
        $estaBloqueada = $item['isDir'] && in_array($item['name'], $bloqueadas);
        @endphp

        <div class="relative group">
          <div
            class="h-full flex flex-col rounded-xl border border-white/10 bg-white/5 backdrop-blur-md shadow-md hover:shadow-lg hover:-translate-y-1 transition-all duration-200 overflow-hidden {{ $estaBloqueada ? 'opacity-50 pointer-events-none' : '' }}">
            {{-- IMAGEN / VISTA PREVIA --}}
            @if ($item['isImage'])
            <img
              src="{{ asset('docs/' . $item['link']) }}"
              alt="{{ $item['name'] }}"
              class="object-cover w-full h-40 rounded-t-xl">
            @elseif ($item['isPDF'])
            <div class="relative bg-gray-900 flex items-center justify-center h-40">
              <i class="bi bi-file-earmark-pdf text-5xl text-red-400"></i>
              <div class="absolute inset-0 bg-gradient-to-t from-gray-900/50 to-transparent"></div>
            </div>
            @elseif ($item['isDir'])
            <div class="relative bg-emerald-900/60 flex items-center justify-center h-40">
              <i class="bi bi-folder-fill text-6xl text-amber-400 drop-shadow-md"></i>
              <div class="absolute inset-0 bg-gradient-to-t from-emerald-900/60 to-transparent"></div>
            </div>
            @else
            <div class="relative bg-gray-800 flex items-center justify-center h-40">
              <i class="bi bi-file-earmark text-5xl text-gray-300"></i>
              <div class="absolute inset-0 bg-gradient-to-t from-gray-900/60 to-transparent"></div>
            </div>
            @endif

            {{-- CONTENIDO --}}
            <div class="flex-grow flex flex-col justify-between p-4">
              <div>
                <h6 class="text-sm font-semibold truncate mb-2 flex items-center gap-1">
                  @if ($item['isDir'])
                  📂
                  @elseif ($item['isImage'])
                  🖼
                  @elseif ($item['isPDF'])
                  📄
                  @else
                  📎
                  @endif
                  <span>{{ $item['name'] }}</span>
                </h6>
              </div>

              {{-- BOTONES --}}
              <div class="mt-auto">
                @if ($estaBloqueada)
                <button class="w-full rounded-md border border-white/10 bg-gray-700/50 text-gray-400 py-2 text-sm cursor-not-allowed">
                  🔒 Aún no disponible
                </button>
                @elseif ($item['isDir'])
                <a href="{{ route('documentacion', $item['link']) }}"
                  class="inline-flex items-center justify-center gap-2 w-full rounded-md border border-emerald-400/40 text-emerald-200 hover:bg-emerald-600/20 py-2 text-sm transition">
                  <i class="bi bi-folder2-open"></i> Abrir carpeta
                </a>
                @elseif ($item['isImage'])
                <a href="{{ asset('docs/' . $item['link']) }}" target="_blank"
                  class="inline-flex items-center justify-center gap-2 w-full rounded-md border border-blue-400/40 text-blue-200 hover:bg-blue-600/20 py-2 text-sm transition">
                  <i class="bi bi-eye"></i> Ver imagen
                </a>
                @elseif ($item['isPDF'])
                <a href="{{ asset('docs/' . $item['link']) }}" target="_blank"
                  class="inline-flex items-center justify-center gap-2 w-full rounded-md border border-red-400/40 text-red-200 hover:bg-red-600/20 py-2 text-sm transition">
                  <i class="bi bi-file-earmark-pdf"></i> Ver PDF
                </a>
                @else
                <a href="{{ asset('docs/' . $item['link']) }}" target="_blank"
                  class="inline-flex items-center justify-center gap-2 w-full rounded-md border border-gray-400/40 text-gray-200 hover:bg-gray-700/30 py-2 text-sm transition">
                  <i class="bi bi-download"></i> Descargar
                </a>
                @endif
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>

      {{-- VACÍO --}}
      @if (count($items) === 0)
      <div class="flex flex-col items-center justify-center h-[60vh] text-center text-gray-400">
        <i class="bi bi-folder-x text-5xl mb-3 text-gray-500"></i>
        <p class="text-lg font-semibold">No hay documentos disponibles en esta carpeta.</p>
      </div>
      @endif
    </div>
  </div>
</section>
@endsection