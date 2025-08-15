@extends('layouts.public')

@section('title','Jugar')

@section('content')
<div class="py-6">
  <div class="flex justify-center">
    <div class="w-full max-w-3xl">
      <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6">
          <h1 class="text-lg font-semibold mb-2">¡Hola, {{ auth()->user()->nombre }}!</h1>
          <p class="text-gray-500 mb-0">
            Acá va el panel para jugar / unirse a partidas. (Placeholder)
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
