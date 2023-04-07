<x-admin.layout>
  <section class="lg:flex my-5">
    <form class="flex flex-wrap form-filters">

      <x-forms.input label="Profesor:" name="searchfor" type="search" :value="$request->input('searchfor')" class="mr-3" />

      <x-forms.select label="Cursos" name="cursos" :options="$cursos" :value="$request->input('cursos')" class="mr-3" ></x-forms.select>

      <x-forms.select label="Edad" name="edad" :value="$request->input('edad')" :options="config('wiseabc.edad_labels')" class="mr-3" ></x-forms.select>

      <x-forms.select label="Nivel" name="nivel" :value="$request->input('nivel')" :options="config('wiseabc.nivel_labels')" class="mr-3" ></x-forms.select>

      <x-forms.select label="Tipo" name="tipo" :value="$request->input('tipo')" :options="\App\Models\Classroom::$arrTipos" class="mr-3" ></x-forms.select>

      <x-forms.select label="Ritmo" name="ritmo" :value="$request->input('ritmo')" :options="config('wiseabc.ritmo_labels')" class="mr-3" ></x-forms.select>

      <button class="self-end mb-1 mr-3 shadow-md rounded-full border-2 border-transparent bg-blue-900 px-3 text-center leading-7 text-sm font-accent font-medium text-gray-100" type="submit">Buscar</button>
    </form>
    <form class="flex">
      <x-forms.input name="searchfor" value="" type="hidden"></x-forms.input>
      <button class="self-end mb-1 mr-3 shadow-md rounded-full border-2 border-transparent bg-white px-3 text-center leading-7 text-sm font-accent font-medium" type="submit">Limpiar</button>
    </form>
  </section>

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
        @if ( $clase->students_count<1 )
        <a href="{{ route('admin.classroom.edit', ['classroom'=>$clase->id]) }}" class="rounded-full border-2 border-gray-100 py-2 xl:py-0 px-3 shadow-md">Editar</a>
        @else
        <a class="rounded-full border-2 border-gray-300 bg-gray-300 text-white py-2 xl:py-0 px-3">Editar</a>
        @endif
        <a href="{{ route('admin.classroom.assignStudents', $clase->id) }}" class="rounded-full border-2 border-gray-100 py-2 xl:py-0 px-3 shadow-md">Asignar estudiantes</a>
      </div>
    </article>
    @endforeach
  </div>

</x-admin.layout>
