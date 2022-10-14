@php
  $user = \Illuminate\Support\Facades\Auth::user();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>WiseABC</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&family=Open+Sans:wght@400;500;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
  <script src="{{ mix('js/app.js') }}" defer></script>
  <script>
    window.app = window.app || {};
    window.app.home = '{{ route('home') }}';
  </script>
</head>
<body class="bg-gray-100 text-gray-700 dark:bg-azul dark:text-gray-200 pt-14 md:pt-0">
  <header id="HeaderMain" class="fixed md:relative top-0 left-0 w-full bg-gray-100 text-gray-700 dark:bg-azul dark:text-gray-200 shadow-md md:shadow-none z-10">
    <div class="container flex justify-between md:items-center py-[2px] px-3 xl:p-0">

      <a href="https://mezcalent.com/wiseabc">
        <img src="{{asset('img/wiseabc-logo.svg')}}" alt="" class="dark:hidden w-auto h-[52px] md:h-[120px]" />
        <img src="{{asset('img/wiseabc-logo-white.svg')}}" alt="" class="hidden dark:block w-auto h-[52px] md:h-[120px]" />
      </a>

      <button class="btn btn-toggle-mobilemenu md:hidden w-12 text-azul dark:text-gray-200 text-center text-[22px] leading-none"><i class="fa-regular fa-bars"></i></button>

      <section id="MobileMenu" class="hidden bg-black/30 fixed top-0 right-0 z-20 flex h-[100vh] w-full">
        <div class="backdrop btn-toggle-mobilemenu flex-grow"></div>
        <div class=" bg-gray-100 text-gray-700 dark:bg-zinc-900 dark:text-gray-200 shadow-md md:shadow-none min-w-[300px] flex flex-col">
          <div class="flex justify-between mb-3">

            <label for="darkmode-toggler1" class="flex items-center cursor-pointer ml-2">
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

            <button class="btn btn-toggle-mobilemenu md:hidden w-12 text-azul dark:text-gray-200 text-center text-[22px] leading-12"><i class="fa-regular fa-times"></i></button>
          </div>
          <nav class="flex-grow flex flex-col font-accent font-medium text-lg">

            <a href="https://mezcalent.com/wiseabc/" class="px-5 py-10px">Inicio</a>
            <a href="https://mezcalent.com/wiseabc/#home-cursos" class="px-5 py-10px">Cursos</a>
            <a href="https://mezcalent.com/wiseabc/#home-clases" class="px-5 py-10px">Clases</a>
            <a href="https://mezcalent.com/wiseabc/#home-blog" class="px-5 py-10px">Blog</a>
            <a href="https://mezcalent.com/wiseabc/#home-contact" class="px-5 py-10px">Contacto</a>
          </nav>
          <div class="p-5 grid grid-cols-1 gap-4 text-center font-accent font-medium">
            <a href="{{ route('register') }}" class="rounded-lg bg-rojo text-white px-5 py-10px">
              Registro
            </a>
            <a href="{{ route('login') }}" class="rounded-lg border-2 border-rojo text-rojo dark:border-white dark:text-white py-10px">Iniciar sesión</a>
          </div>
        </div>
      </section>

      <div class="hidden md:block font-accent font-medium">
        <nav class="flex justify-end text-sm">
          @guest
            <a href="{{ route('login') }}" class="p-2">Acceso</a>
            <a href="{{ route('register') }}" class="p-2">Registro</a>
          @else
            <span class="p-2">Hola {{ $user->fname }}</span>
            <a href="{{ route('logout') }}" class="p-2">Salir</a>
          @endguest
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
        </nav>
        <nav class="border-t-2 border-gray-400 flex font-bold">
          <a href="https://mezcalent.com/wiseabc/" class="px-5 py-4">Inicio</a>
          <a href="https://mezcalent.com/wiseabc/#home-cursos" class="px-5 py-4">Cursos</a>
          <a href="https://mezcalent.com/wiseabc/#home-clases" class="px-5 py-4">Clases</a>
          <a href="https://mezcalent.com/wiseabc/#home-blog" class="px-5 py-4">Blog</a>
          <a href="https://mezcalent.com/wiseabc/#home-contact" class="px-5 py-4">Contacto</a>
        </nav>
      </div>
    </div>
  </header>
  <div class="container px-3 xl:px-0 {{ $attributes->get('container-class') }}" style="min-height: calc(100vh - 360px)">
    {{$slot}}
  </div>
  <footer id="MainFooter" class="bg-azul text-gray-200 dark:bg-gray-900 dark:border-t dark:border-gray-900">
    <div class="container md:flex md:justify-between md:items-end py-5">
      <div class="hidden md:block">
        <nav class="border-b-2 border-gray-400 flex text-lg font-accent">
          <a href="https://mezcalent.com/wiseabc/" class="px-5 py-4">Inicio</a>
          <a href="https://mezcalent.com/wiseabc/#home-cursos" class="px-5 py-4">Cursos</a>
          <a href="https://mezcalent.com/wiseabc/#home-clases" class="px-5 py-4">Clases</a>
          <a href="https://mezcalent.com/wiseabc/#home-blog" class="px-5 py-4">Blog</a>
          <a href="https://mezcalent.com/wiseabc/#home-contact" class="px-5 py-4">Contacto</a>
        </nav>
        <nav class="flex text-sm leading-6">
          <a href="#" class="p-3">Registro de profesor</a>
          <a href="#" class="p-3">Términos y condiciones</a>
          <a href="#" class="p-3">Política de privacidad</a>
        </nav>
      </div>
      <div class="text-center flex flex-col items-center justify-center">
        <a href="#" class="block">
          <img src="{{asset('img/wiseabc-logo-footer.svg')}}" alt="" class="w-auto h-[120px] opacity-75" />
        </a>
        <nav class="flex justify-center text-xl leading-[3rem] mt-3 border-t-2 border-gray-300">
          <a href="#" class="w-12">
            <i class="fa-brands fa-facebook-f"></i>
          </a>
          <a href="#" class="w-12">
            <i class="fa-brands fa-twitter"></i>
          </a>
        </nav>
      </div>
    </div>
    <p class="text-center text-xs pb-2">
      &copy; 2022 Todos los derechos reservados
    </p>
  </footer>
  @stack('scripts')
</body>
</html>