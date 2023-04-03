<x-admin.layout>
  <h1 class="text-3xl font-accent font-bold">Profesores</h1>


  <div class="flex justify-between items-center my-3 mr-3">

    <a href="{{ route('admin.teacher.create') }}" class="h-12 xl:h-8 shadow-md rounded-2xl px-3 bg-blue-700 text-white text-center flex items-center">
      <i class="fa-light fa-plus mr-2"></i>
      <span>Agregar Profesor</span>
    </a>
  </div>
  <form>
    <div class="flex items-center my-3">
      <form class="inline-flex">
        <div class="mr-2 mb-5">
          <x-forms.input label="Buscar:"  name="searchfor" id="searchfor" value="{{$search}}" type="search"/>
        </div>
        <div class="mr-3 mb-5">
          <x-forms.select label="Estatus" name="estatus" :value="$request->input('estatus')" :options="config('wiseabc.user_status_abels')" ></x-forms.select>
        </div>
        <div class="mr-3 mb-5">
          <x-forms.select label="Clases" name="classroom" :value="$request->input('classroom')" :options="config('wiseabc.classrooms_exist')" ></x-forms.select>
        </div>
        <div class="mr-3 ">
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

  </form>
  <table class="border border-black/5 dark:border-white/5">
    <thead class="text-left">
      <tr class="bg-black/5 dark:bg-white/5">
        <th class="px-2">ID</th>
        <th class="p-2">Nombre</th>
        <th class="px-2">Classrooms</th>
        <th class="px-2">Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($profes as $profe)
        <tr class="even:bg-black/5 dark:even:bg-white/5">
          <td class="px-2">{{ $profe->id }}</td>
          <td class="p-2">{{ $profe->name }}</td>
          <td class="px-2 text-center"> {{$profe->teachclasses()->count()}} </td>
          <td class="px-2 text-sm ">
            <div class="flex">
              <a href="{{ route('admin.classroom.createforteacher', ['teacher'=>$profe]) }}" class="px-2">
                <i class="fa-light fa-plus"></i> Classroom
              </a>
              <a href="{{ route('admin.teacher.edit', ['teacher'=>$profe]) }}" class="px-2"><i class="fa-light fa-pen-to-square"></i> Editar</a>
              <a href="{{ route('admin.teacher.destroy', ['teacher'=>$profe]) }}" class="px-2">
                <i class="fa-light fa-trash"></i> Eliminar</a>
            </div>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

</x-admin.layout>
