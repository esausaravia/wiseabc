<x-admin.layout>
  <form>
    <div class="flex items-center my-3">
      <form class="inline-flex">
        <div class="mr-2 mb-5">
          <x-forms.input label="Profesor:"  name="searchfor" id="searchfor"  :value="$request->input('searchfor')" type="search"/>
        </div>
        <div class="mr-3 mb-5">
          <x-forms.select label="Cursos" name="cursos" :value="$request->input('cursos')" :options="$cursos" ></x-forms.select>
        </div>
        <div class="mr-3 mb-5">
          <x-forms.select label="Edad" name="edad"  :value="$request->input('edad')"  :options="config('wiseabc.edad_labels')" ></x-forms.select>
        </div>
        <div class="mr-3 mb-5">
          <x-forms.select label="Nivel" name="nivel"  :value="$request->input('nivel')"  :options="config('wiseabc.nivel_labels')" ></x-forms.select>
        </div>
        <div class="mr-3 mb-5">
          <x-forms.select label="Tipo" name="tipo"  :value="$request->input('tipo')"  :options="\App\Models\Classroom::$arrTipos" ></x-forms.select>
        </div>
        <div class="mr-3 mb-5">
          <x-forms.select label="Ritmo" name="ritmo"  :value="$request->input('ritmo')"  :options="config('wiseabc.ritmo_labels')" ></x-forms.select>
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
  <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
    @foreach($clases AS $clase)
    <article class="shadow-md rounded-lg p-3 bg-white dark:bg-white/5">
      <p class="mb-2">{{$clase->curso->name}}</p>
      <p class="mb-2">{{$clase->teacher->name}}</p>
      <div class="text-sm mb-2">
        <ul class="flex flex-wrap">
          <li class="mr-2"><strong>Edad</strong>: {{$clase->curso->edadLabel}} </li>
          <li class="mr-2"><strong>Nivel</strong>: {{$clase->curso->nivelLabel}} </li>
          <li class="mr-2"><strong>Tipo</strong>: {{$clase->tipoLabel}} </li>
          <li class="mr-2"><strong>Ritmo</strong>: {{$clase->ritmoLabel}} </li>
        </ul>

        <ul class="flex flex-wrap">
          <li class="mr-2"><strong>Inició</strong>: {{ $clase->start }} </li>
          <li class="mr-2"><strong>Fin</strong>: {{ $clase->ends_at }} </li>
          <li class="mr-2">( {{ $clase->endsInWeeks() }}w )</li>
        </ul>

        <x-user-card-horarios class="flex flex-wrap" :horarios="$clase->getHorarioArray()"></x-user-card-horarios>

        <ul>
          <li><strong>Estudiantes</strong>: {{$clase->students_count}} {{ $clase->tipo==1 ? '/3' : '' }}</li>
          @foreach ($clase->students as $student)
            <li>{{$student->name}}</li>
          @endforeach
        </ul>

      </div>
      <div class="flex justify-between font-accent font-medium text-sm leading-7">
        <a href="{{ route('admin.classroom.edit', ['classroom'=>$clase->id]) }}" class="rounded-full border-2 border-gray-100 py-2 xl:py-0 px-3 shadow-md">Editar</a>
        <a href="{{ route('admin.classroom.assignStudents', $clase->id) }}" class="rounded-full border-2 border-gray-100 py-2 xl:py-0 px-3 shadow-md">Asignar estudiantes</a>
      </div>
    </article>
    @endforeach
  </div>

</x-admin.layout>
