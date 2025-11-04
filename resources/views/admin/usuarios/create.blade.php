@extends('layouts.adminLayout')

@section('title', __('New user'))

@section('content')
<div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-10">
  <div class="rounded-xl border border-white/10 bg-gray-900/70 backdrop-blur-md shadow-xl overflow-hidden">
    <div class="px-6 py-5 border-b border-white/10">
      <h1 class="flex items-center gap-2 text-xl font-bold text-white">
        <i class="bi bi-person-plus-fill text-emerald-400"></i>
        <span>{{ __('New user') }}</span>
      </h1>
      <p class="text-sm text-emerald-200/80 mt-1">
        {{ __('Create a new user with their role and access credentials.') }}
      </p>
    </div>

    <div class="p-6 text-gray-100">
      <form method="POST" action="{{ route('admin.usuarios.store') }}" class="space-y-5">
        @csrf

        {{-- Full name --}}
        <div>
          <label class="mb-1 block text-sm font-semibold text-emerald-200">{{ __('Full name') }}</label>
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

        {{-- Username --}}
        <div>
          <label class="mb-1 block text-sm font-semibold text-emerald-200">{{ __('Username (login name)') }}</label>
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

        {{-- Role --}}
        <div>
          <label class="mb-1 block text-sm font-semibold text-emerald-200">{{ __('Role') }}</label>
          <select
            name="rol"
            required
            class="block w-full rounded-md border border-white/10 bg-gray-800/70 px-3 py-2 text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 outline-none transition">
            <option value="jugador" @selected(old('rol')==='jugador' )>{{ __('Player') }}</option>
            <option value="admin" @selected(old('rol')==='admin' )>{{ __('Administrator') }}</option>
          </select>
          @error('rol')
          <div class="mt-1 text-sm text-red-400">{{ $message }}</div>
          @enderror
        </div>

        {{-- Password --}}
        <div x-data="{ show:false }">
          <label class="mb-1 block text-sm font-semibold text-emerald-200">{{ __('Password') }}</label>
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

        {{-- Repeat password --}}
        <div x-data="{ show:false }">
          <label class="mb-1 block text-sm font-semibold text-emerald-200">{{ __('Repeat password') }}</label>
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

        {{-- Buttons --}}
        <div class="flex gap-3 pt-4">
          <a href="{{ route('admin.usuarios.index') }}"
            class="inline-flex items-center gap-1.5 rounded-md border border-white/10 bg-gray-800/60 px-4 py-2 text-gray-200 hover:bg-gray-700/60 focus:ring-2 focus:ring-gray-500 transition">
            <i class="bi bi-x-circle"></i> {{ __('Cancel') }}
          </a>
          <button
            class="inline-flex items-center gap-1.5 rounded-md bg-emerald-600 hover:bg-emerald-700 px-4 py-2 text-white font-medium shadow focus:ring-2 focus:ring-emerald-500 transition">
            <i class="bi bi-check-lg"></i> {{ __('Save') }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
