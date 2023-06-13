<x-admin.layout>
  <h1 class="text-3xl font-accent font-bold mb-5">Alumnos</h1>

  {{--
  <div class="flex justify-between items-center my-3">

    <a href="{{ route('admin.student.create') }}" class="h-12 xl:h-8 shadow-md rounded-2xl px-3 bg-rojo text-white text-center flex items-center">
      <i class="fa-light fa-plus mr-2"></i>
      <span>Agregar</span>
    </a>
  </div>--}}


    <div class="flex items-center my-3">
      <form class="inline-flex">
        <div class="mr-2 mb-5">
          <x-forms.input label="Buscar:" name="searchfor" id="searchfor" value="{{$search}}" type="search"/>
        </div>
        <div class="mr-3 mb-5">
          <x-forms.select label="Estatus" name="estatus" :value="$request->input('estatus')" :options="config('wiseabc.user_status_labels')" ></x-forms.select>
        </div>
        <div class="mr-3 mb-5">
          <x-forms.select label="Nivel" name="nivel" :value="$request->input('nivel')" :options="config('wiseabc.nivel_labels')" ></x-forms.select>
        </div>
        <div class="mr-3 mb-5">
          <x-forms.select label="Edad" name="edad" :value="$request->input('edad')" :options="config('wiseabc.edad_labels')" ></x-forms.select>
        </div>
        <div class="mr-3 mb-5">
          <x-forms.select label="Ritmo" name="ritmo" :value="$request->input('ritmo')" :options="config('wiseabc.ritmo_labels')" ></x-forms.select>
        </div>
        <div class="mr-3 mt-5">
          <button class="h-12 xl:h-8 shadow-md rounded-2xl px-3 bg-rojo text-white text-center flex items-center" type="submit">Buscar</button>
        </div>
      </form>
      <form class="inline-flex">
        <div class="mr-3 mb-1">
          <x-forms.input name="searchfor" id="searchfor" value="" type="hidden"></x-forms.input>
          <button class="h-12 xl:h-8 shadow-md rounded-2xl px-3 bg-blue-700 text-white text-center flex items-center" type="submit">Limpiar</button>
        </div>
      </form>
    </div>

  <table class="border border-black/5 dark:border-white/5">
    <thead class="text-left">
      <tr class="bg-black/5 dark:bg-white/5">
        <th class="p-2">ID</th>
        <th class="p-2">Nombre</th>
        <th class="p-2">Edad</th>
        <th class="p-2">Nivel</th>
        <th class="p-2">Ritmo</th>
        <th class="p-2">Classroom</th>
        <th class="p-2">Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($Students as $student)
        <tr class="even:bg-black/5 dark:even:bg-white/5">
          <td class="p-2">{{ $student->id }}</td>
          <td class="p-2">
            <a href="{{ route('admin.student.show', ['student'=>$student->id]) }}">{{ $student->name }}</a>
          </td>
          <td class="p-2">{{ $student->edadLabel }}</td>
          <td class="p-2">{{ $student->nivelLabel }}</td>
          <td class="p-2">
            @if ( is_null($student->clase_tipo) )
            No ha elegido
            @else
            {{ config('wiseabc.clase_tipo_labels.'.$student->clase_tipo) }}
            @endif
            {{ $student->ritmoLabel }}
          </td>

          <td class="p-2">
            @if( !empty($student->currentClassroom) )
            #{{ $student->currentClassroom->id }}
            @else
            <a href="{{ route('admin.student.assignclass', ['student'=>$student]) }}" class="px-2">
              <i class="fa-light fa-plus"></i> Classroom </a>
            @endif

          </td>

          <td class="px-2 text-sm ">
            <div class="flex">
              <a href="{{ route('admin.student.edit', ['student'=>$student]) }}" class="px-2">
                <i class="fa-light fa-pen-to-square"></i> Editar</a>

              <a href="{{ route('admin.student.destroy', ['student'=>$student]) }}" class="px-2">
                <i class="fa-light fa-trash"></i> Eliminar</a>
            </div>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</x-admin.layout>
