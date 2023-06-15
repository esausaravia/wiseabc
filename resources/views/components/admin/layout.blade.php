@props([
  'user' => request()->user(),
  'body' => null
])
<x-document :body="$body">
  <header id="AdminHeader" class="fixed top-0 left-0 w-full bg-gray-100 dark:bg-azulw shadow-md z-20 flex justify-between items-center">
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
  <aside id="AdminAside" class="fixed top-0 left-0 h-full -translate-x-full xl:translate-x-0 shadow-md z-10 pt-[68px] px-3 md:px-4 xl:px-5 pb-5 bg-azulw dark:bg-black/30 font-accent text-gray-200 ">
    <ul class="font-accent">
      <li><a href="{{ route('admin.home') }}" class="block p-2">Inicio</a></li>
      <li><a href="{{ route('admin.cursos.index') }}" class="block p-2">Cursos</a></li>
      <li><a href="{{ route('admin.classroom.index') }}" class="block p-2">Clases</a></li>
      <li><a href="{{ route('admin.teacher.index') }}" class="block p-2">Profesores</a></li>
      <li><a href="{{ route('admin.student.index') }}" class="block p-2">Alumnos</a></li>
      <li><a href="{{ route('admin.pagos.index') }}" class="block p-2">Pagos</a></li>
    </ul>
  </aside>
  <div id="AdminMainContainer" class="pt-16 px-3 pb-5 lg:pt-20 xl:pl-[170px]">
    {{ $slot }}
  </div>

  <div id="modal-delete-entity" class="modal fixed inset-0 w-full h-full z-50 flex items-center justify-center bg-black/50 hidden" role="dialog">
    <form action="" method="post" class="modal-content rounded-2xl bg-gray-100 text-gray-600">
      @csrf
      @method('delete')

      <section class="modal-body py-5 px-3">¿Está seguro que desea eliminar <b class="delete-name underline"></b>?</section>

      <section class="modal-footer border-t border-t-gray-300 py-2 px-3 flex justify-between font-accent font-medium leading-12 xl:leading-8">
        <span>
          <button type="button" class="modal-close rounded-full px-4 bg-white shadow-md">Cancelar</button>
        </span>
        <span>
          <button type="submit" class="rounded-full px-4 bg-rojo text-white shadow-md">Eliminar</button>
        </span>
      </section>
    </form>
  </div>

  @push('scripts')
    <script src="{{ asset('js/admin.js') }}" defer></script>
  @endpush
</x-document>