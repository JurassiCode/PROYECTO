@extends('layouts.publicLayout')

@section('title', __('Home'))

@section('content')
<!-- HERO -->
<section class="relative overflow-hidden bg-gradient-to-br from-emerald-700 via-emerald-800 to-gray-900 text-white py-20">
  <div class="absolute inset-0 opacity-10 bg-repeat" style="background-image: url(/images/pattern_dinos.svg);"></div>

  <div class="relative mx-auto max-w-7xl px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
    <div class="space-y-8">
      <div>
        <h1 class="text-5xl md:text-6xl font-extrabold leading-tight drop-shadow-sm">
          {{ __('Welcome to') }} <span class="text-emerald-300">JurassiDraft</span>!
        </h1>
        <p class="mt-4 text-lg text-emerald-100 max-w-lg">
          {{ __('The modern way to play Draftosaurus: manage your games, turns and scores digitally, quickly and fun.') }}
        </p>
      </div>

      <div class="flex flex-wrap gap-3">
        <a href="{{ route('ranking.index') }}"
          class="flex items-center gap-2 rounded-md border border-amber-400 text-amber-400 hover:bg-amber-500/10 px-6 py-3 font-semibold transition">
          <i class="bi bi-trophy"></i> {{ __('View Ranking') }}
        </a>

        @auth
          @if (auth()->user()->rol === 'admin')
          <a href="{{ route('admin.usuarios.index') }}"
            class="flex items-center gap-2 rounded-md bg-amber-500 hover:bg-amber-600 px-6 py-3 font-semibold shadow-md transition">
            <i class="bi bi-speedometer2"></i> {{ __('Admin Panel') }}
          </a>
          @endif

          <a href="{{ route('lobby') }}"
            class="flex items-center gap-2 rounded-md bg-emerald-500 hover:bg-emerald-600 px-6 py-3 font-semibold shadow-md transition">
            <i class="bi bi-play-fill"></i> {{ __('Start Game') }}
          </a>

          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
              class="flex items-center gap-2 rounded-md border border-red-400 text-red-400 hover:bg-red-600/10 px-6 py-3 font-semibold transition">
              <i class="bi bi-box-arrow-right"></i> {{ __('Logout') }}
            </button>
          </form>

          <p class="w-full mt-4 text-sm text-emerald-200">
            👋 {{ __('Hello') }}, <span class="font-semibold">{{ auth()->user()->nombre ?? auth()->user()->usuario }}</span> · ID {{ auth()->user()->id }}
          </p>
        @endauth

        @guest
          <a href="{{ route('login') }}"
            class="flex items-center gap-2 rounded-md bg-emerald-600 hover:bg-emerald-700 px-6 py-3 font-semibold shadow-md transition">
            <i class="bi bi-box-arrow-in-right"></i> {{ __('Login') }}
          </a>

          <a href="{{ route('register') }}"
            class="flex items-center gap-2 rounded-md border border-emerald-400 text-emerald-400 hover:bg-emerald-500/10 px-6 py-3 font-semibold transition">
            <i class="bi bi-person-plus"></i> {{ __('Register') }}
          </a>
        @endguest
      </div>
    </div>

    <div class="relative">
      <div class="rounded-2xl bg-white/10 border border-white/20 backdrop-blur-md shadow-xl overflow-hidden">
        <img src="{{ asset('images/logojuego_nobg.png') }}" alt="JurassiDraft Logo"
          class="object-contain w-full h-full p-10">
      </div>
      <div class="absolute -z-10 -top-12 -right-12 w-80 h-80 bg-emerald-500/30 rounded-full blur-3xl"></div>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="py-20 bg-gray-50 border-t border-gray-200">
  <div class="mx-auto max-w-7xl px-6 lg:px-8 text-center">
    <h2 class="text-4xl font-bold text-gray-900 mb-3">{{ __('How does it work?') }}</h2>
    <p class="text-gray-600 mb-12">{{ __('Learn to play in just four steps.') }}</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
      @foreach ([
      ['🦕', __('Choose your dinosaurs'), __('Select one per turn and plan your strategy.')],
      ['🎲', __('Load the dice restriction'), __('Limiting the placements.')],
      ['🏞️', __('Place them in enclosures'), __('Each type scores differently: think before you act.')],
      ['🏆', __('Score points and win'), __('At the end of the second round, the system calculates the winner.')]
      ] as [$icon,$title,$desc])
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8 hover:shadow-md hover:-translate-y-1 transition">
        <div class="text-4xl mb-4">{{ $icon }}</div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $title }}</h3>
        <p class="text-gray-600 text-sm">{{ $desc }}</p>
      </div>
      @endforeach
    </div>

    @guest
    <a href="{{ route('register') }}"
      class="mt-12 inline-flex items-center gap-2 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 text-lg font-semibold shadow transition">
      <i class="bi bi-person-plus"></i> {{ __('Create account and play for free') }}
    </a>
    @endguest
  </div>
</section>

<!-- FEATURES -->
<section class="py-20 bg-white border-t border-gray-200">
  <div class="mx-auto max-w-7xl px-6 lg:px-8">
    <div class="text-center mb-12">
      <h2 class="text-4xl font-bold text-gray-900 mb-3">{{ __('Main features') }}</h2>
      <p class="text-gray-600">{{ __('Everything you need to enjoy a digital match.') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      @foreach ([
      ['🔐', __('User and role management'), __('Players and admins with defined permissions.')],
      ['🧩', __('Configurable matches'), __('Create, edit and relaunch games at any time.')],
      ['📊', __('Automated scoring'), __('The system validates plays and calculates points instantly.')]
      ] as [$icon,$title,$desc])
      <div class="rounded-2xl bg-gray-50 border border-gray-200 p-8 shadow-sm hover:shadow-md transition">
        <div class="text-4xl mb-3">{{ $icon }}</div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $title }}</h3>
        <p class="text-gray-600">{{ $desc }}</p>
      </div>
      @endforeach
    </div>

    @auth
    <div class="text-center mt-12">
      <a href="{{ route('lobby') }}"
        class="inline-flex items-center gap-2 rounded-md bg-emerald-600 hover:bg-emerald-700 px-6 py-3 text-white text-lg font-semibold shadow transition">
        <i class="bi bi-controller"></i> {{ __('Create new game') }}
      </a>
    </div>
    @endauth
  </div>
</section>

<!-- TEAM -->
<section class="py-20 bg-gray-50 border-t border-gray-200">
  <div class="mx-auto max-w-7xl px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 items-center gap-12">
    <div>
      <h2 class="text-4xl font-bold mb-4 text-gray-900">{{ __('Who are we?') }}</h2>
      <p class="text-gray-700 mb-4 leading-relaxed">
        <strong>JurassiDraft</strong> {{ __('is a creation of the team') }} <strong>JurassiCode</strong>, {{ __('a group of students passionate about programming, innovation and board games.') }}
      </p>
      <ul class="space-y-2 text-gray-700">
        <li><strong>{{ __('Mission') }}:</strong> {{ __('Make game tracking simple and fun.') }}</li>
        <li><strong>{{ __('Vision') }}:</strong> {{ __('Become the digital reference tool for Draftosaurus.') }}</li>
        <li><strong>{{ __('Values') }}:</strong> {{ __('Innovation, clarity, accessibility and passion for the game.') }}</li>
      </ul>
    </div>

    <div class="text-center">
      <div class="inline-block rounded-2xl bg-white shadow-lg border border-gray-200 overflow-hidden">
        <div class="bg-emerald-50 p-6">
          <img src="https://jurassicode.vercel.app/images/logo.png" alt="Equipo JurassiCode"
            class="mx-auto max-h-64 object-contain">
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
