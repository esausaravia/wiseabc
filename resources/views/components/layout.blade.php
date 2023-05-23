@props([
  'user'=>\Illuminate\Support\Facades\Auth::user(),
  'body'=>null
])
<x-document :body=$body>
  <header id="MainHeader" class="fixed top-0 left-0 w-full bg-gray-50 dark:bg-azulw shadow-md z-20 flex justify-between items-center">
    <button class="btn btn-toggle-aside w-12 text-azul-600 dark:text-gray-200 text-center text-[22px] leading-12"><i class="fa-light fa-bars"></i></button>

    <div class="flex text-sm font-accent font-medium">
      <span class="p-2">Hola {{ !empty($user->fname) ? $user->fname : $user->name }}</span>
      <a href="{{ route('salir') }}" class="p-2">Salir</a>

      <label for="darkmode-toggler1" class="inline-flex relative items-center cursor-pointer ml-2">
        <span class="mr-2">
            <svg id="theme-toggle-light-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
            </svg>
        </span>
        <input type="checkbox" value="" id="darkmode-toggler1" class="darkmode-toggler sr-only peer">
        <div class="peer relative w-11 h-6 rounded-full bg-gray-300 dark:bg-gray-800 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
        <span class="ml-2">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" ></path></svg>
        </span>
      </label>
    </div>
  </header>
  <aside id="MainAside" class="fixed top-0 left-0 -translate-x-full lg:translate-x-0 h-full shadow-md pt-[68px] px-5 pb-6 bg-azulw dark:bg-black/20 text-gray-400 font-accent font-medium z-10">
    <nav class="flex flex-col font-accent text-center uppercase text-[10px]">
      @php
        $route_group = $user->user_type==2 ? 'student' : 'teacher';
      @endphp
      <a href="{{ route($route_group.'.home') }}">
        <figure class="flex items-center justify-center">
          <img src="{{ asset('img/wiseabc-logo-white.svg') }}" alt="WiseABC" class="w-auto max-w-full max-h-full h-auto">
        </figure>
      </a>
      <a href="{{ route($route_group.'.home') }}" class="flex flex-col py-4">
        <span class="ico text-2xl"><i class="fa-light fa-calendar-day"></i></span>
        <span class="">Calendario</span>
      </a>

      <a href="{{ route($route_group.'.pagos') }}" class="flex flex-col py-4">
        <span class="ico text-2xl"><i class="fa-light fa-file-invoice-dollar"></i></span>
        <span class="">Pagos</span>
      </a>
      <a href="{{ route($route_group.'.perfil') }}" class="flex flex-col py-4">
        <span class="ico text-2xl"><i class="fa-light fa-user-gear"></i></span>
        <span class="">Perfil</span>
      </a>
      <a href="{{ route('salir') }}" class="flex flex-col py-4">
        <span class="ico text-2xl"><i class="fa-light fa-arrow-right-from-arc"></i></span>
        <span class="">SALIR</span>
      </a>
    </nav>
  </aside>
  <div id="MainContainer" class="p-3 pt-16 lg:pt-20 lg:pl-[130px] lg:pr-5 lg:pb-5">
    {{ $slot }}

  </div>
  <footer class="text-center">{{ __('All rights reserved.') }}</footer>
</x-document>