<x-admin.layout>
  <h1 class="text-3xl font-accent font-bold mb-5">Alumnos</h1>

  {{--
  <div class="flex justify-between items-center my-3">

    <a href="{{ route('admin.student.create') }}" class="h-12 xl:h-8 shadow-md rounded-2xl px-3 bg-rojo text-white text-center flex items-center">
      <i class="fa-light fa-plus mr-2"></i>
      <span>Agregar</span>
    </a>
  </div>--}}

  <form>
    <div class="flex items-center my-3">

    <div class="mr-2 mb-5">
      <label for="searchfor">Buscar:</label>
      <x-forms.input name="searchfor" id="searchfor" value="{{$search}}" type="search"/>
    </div>
    <div class="mr-3 mb-5">
      <x-forms.select label="Nivel" name="nivel" :options="config('wiseabc.nivel_labels')" ></x-forms.select>
    </div>
    <div class="mr-3 mb-5">
      <x-forms.select label="Edad" name="edad" :options="config('wiseabc.edad_labels')" ></x-forms.select>
    </div>
    <div class="mr-3 mb-5">
      <x-forms.select label="Estatus" name="estatus" :options="config('wiseabc.estatus')" ></x-forms.select>
    </div>
    <div class="mr-3">
      <button class="h-12 xl:h-8 shadow-md rounded-2xl px-3 bg-rojo text-white text-center flex items-center" type="submit">Buscar</button>
    </div>

    </div>
  </form>

  <table class="border border-black/5 dark:border-white/5">
    <thead class="text-left">
      <tr class="bg-black/5 dark:bg-white/5">
        <th class="p-2">ID</th>
        <th class="p-2">Nombre</th>
        <th class="p-2">Edad</th>
        <th class="p-2">Nivel</th>
        <th class="p-2">Suscripción</th>
        <th class="p-2">Classroom</th>
        <th class="p-2">Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($students as $alumno)
        <tr class="even:bg-black/5 dark:even:bg-white/5">
          <td class="p-2">{{ $alumno->id }}</td>
          <td class="p-2">
            <a href="{{ route('admin.student.show', ['student'=>$alumno->id]) }}">{{ $alumno->name }}</a>
          </td>
          <td class="p-2">{{ $alumno->edadLabel }}</td>
          <td class="p-2">{{ $alumno->nivelLabel }}</td>
          <td class="p-2">{{ ($_arr = config('wiseabc.suscripcion_labels')) && !empty($_arr[( $alumno->suscripcion )]) ? $_arr[( $alumno->suscripcion )] : $alumno->suscripcion }}</td>

          <td class="p-2">
            @if ( $alumno->suscripcion!==NULL )
              @if ( $alumno->classrooms()->count()>0 )
                @php $classroom = $alumno->classrooms()->get()->first(); @endphp

                #{{ $classroom->id }}
              @else
                <a href="{{ route('admin.student.assignclass', ['student'=>$alumno]) }}" class="px-2">
                  <i class="fa-light fa-plus"></i> Classroom </a>
              @endif
            @endif
          </td>

          <td class="px-2 text-sm ">
            <div class="flex">
              <a href="{{ route('admin.student.edit', ['student'=>$alumno]) }}" class="px-2">
                <i class="fa-light fa-pen-to-square"></i> Editar</a>

              <a href="{{ route('admin.student.destroy', ['student'=>$alumno]) }}" class="px-2">
                <i class="fa-light fa-trash"></i> Eliminar</a>
            </div>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</x-admin.layout>
