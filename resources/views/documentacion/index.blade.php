@extends('layouts.publicLayout')

@section('title', 'Documentación')

@section('content')
<section class="py-6 bg-white">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between">
      <h2 class="text-xl font-semibold">📁 Documentación JurassiDraft</h2>
    </div>

    {{-- Volver (si hay path relativo) --}}
    @if ($relativePath)
    <div class="mb-3">
      <a href="{{ route('documentacion', dirname($relativePath)) }}"
        class="inline-flex items-center justify-center gap-2 rounded-md border border-gray-300 px-3 py-1.5 text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-400">
        ⬅ Volver
      </a>
    </div>
    @endif

    {{-- Grid de items --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
      @foreach ($items as $item)
      @php
      $estaBloqueada = $item['isDir'] && in_array($item['name'], $bloqueadas);
      @endphp

      <div class="h-full">
        <div class="h-full rounded-lg border border-gray-200 bg-white shadow-sm {{ $estaBloqueada ? 'opacity-50' : '' }}">
          {{-- Directorio --}}
          @if ($item['isDir'])
          <div class="p-4">
            <h6 class="mb-2 text-sm font-semibold">📂 {{ $item['name'] }}</h6>

            @if ($estaBloqueada)
            <button class="w-full rounded-md border border-gray-300 px-3 py-1.5 text-gray-600 bg-white cursor-not-allowed" disabled>
              🔒 Aún no disponible
            </button>
            @else
            <a href="{{ route('documentacion', $item['link']) }}"
              class="w-full inline-flex items-center justify-center rounded-md border border-emerald-600 px-3 py-1.5 text-emerald-700 hover:bg-emerald-50">
              Abrir carpeta
            </a>
            @endif
          </div>

          {{-- Imagen --}}
          @elseif ($item['isImage'])
          <img
            src="{{ asset('docs/' . $item['link']) }}"
            alt="{{ $item['name'] }}"
            class="w-full h-40 object-cover rounded-t-lg">
          <div class="p-4">
            <h6 class="mb-2 truncate text-sm font-semibold">🖼 {{ $item['name'] }}</h6>
            <a href="{{ asset('docs/' . $item['link']) }}" target="_blank"
              class="w-full inline-flex items-center justify-center rounded-md border border-blue-600 px-3 py-1.5 text-blue-700 hover:bg-blue-50">
              Ver imagen
            </a>
          </div>

          {{-- PDF --}}
          @elseif ($item['isPDF'])
          <div class="relative w-full overflow-hidden rounded-t-lg">
            <div class="aspect-[4/3] bg-gray-50">
              <iframe src="{{ asset('docs/' . $item['link']) }}" class="w-full h-full" frameborder="0"></iframe>
            </div>
          </div>
          <div class="p-4">
            <h6 class="mb-2 truncate text-sm font-semibold">📄 {{ $item['name'] }}</h6>
            <a href="{{ asset('docs/' . $item['link']) }}" target="_blank"
              class="w-full inline-flex items-center justify-center rounded-md border border-red-600 px-3 py-1.5 text-red-700 hover:bg-red-50">
              Ver PDF
            </a>
          </div>

          {{-- Otros archivos --}}
          @else
          <div class="p-4">
            <h6 class="mb-2 truncate text-sm font-semibold">📎 {{ $item['name'] }}</h6>
            <a href="{{ asset('docs/' . $item['link']) }}" target="_blank"
              class="w-full inline-flex items-center justify-center rounded-md border border-gray-800 px-3 py-1.5 text-gray-800 hover:bg-gray-50">
              Descargar
            </a>
          </div>
          @endif
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endsection