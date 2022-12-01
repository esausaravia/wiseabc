<x-admin.layout>
  <h1 class="text-3xl font-accent font-bold">Cursos</h1>

  <div class="flex justify-between items-center my-3">

    <a href="{{ route('admin.cursos.create') }}" class="h-12 xl:h-8 shadow-md rounded-2xl px-3 bg-rojo text-white text-center flex items-center">
      <i class="fa-light fa-plus mr-2"></i>
      <span>Agregar</span>
    </a>
  </div>

  <table class="border border-black/5 dark:border-white/5">
    <thead class="text-left">
      <tr class="bg-black/5 dark:bg-white/5">
        <th class="p-2">Nombre</th>
        <th class="px-2">Edad</th>
        <th class="px-2">Nivel</th>
        <th class="px-2">Clases</th>
        <th class="px-2">Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($cursos as $curso)
        <tr class="even:bg-black/5 dark:even:bg-white/5">
          <td class="p-2">{{ $curso->name }}</td>
          <td class="px-2">{{ $curso->edad_label }}</td>
          <td class="px-2">{{ $curso->nivel_label }}</td>
          <td class="px-2">{{ $curso->clases()->count() }}</td>
          <td class="px-2 text-sm ">
            <div class="flex">
              {{--
              <a href="{{ route('admin.classroom.createforcurso', ['curso'=>$curso]) }}" class="px-2">
                <i class="fa-light fa-plus"></i> Classroom
              </a>--}}
              <a href="{{ route('admin.cursos.edit',['curso'=>$curso]) }}" class="px-2"><i class="fa-light fa-pen-to-square"></i> Editar</a>
              <a href="{{ route('admin.cursos.destroy', ['curso'=>$curso]) }}" class="px-2">
                <i class="fa-light fa-trash"></i> Eliminar</a>
            </div>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</x-admin.layout>