<x-admin.layout>
  <h1 class="text-xl md:text-2xl font-accent font-bold mb-5">Asignar estudiantes</h1>

  <section class="my-5 md:flex">
    <article class="shadow-md rounded-lg p-3 bg-white dark:bg-white/5">
      <p class="mb-2 font-medium">Classroom #{{$Classroom->id}}</p>
      <p class="">{{$Classroom->curso->name}}</p>
      <p class="mb-2">{{$Classroom->teacher->name}}</p>
      <div class="text-sm">
        <ul class="flex flex-wrap">
          <li class="mr-2"><strong>Edad</strong>: {{$Classroom->curso->edadLabel}} </li>
          <li class="mr-2"><strong>Nivel</strong>: {{$Classroom->curso->nivelLabel}} </li>
          <li class="mr-2"><strong>Tipo</strong>: {{$Classroom->tipoLabel}} </li>
          <li class="mr-2"><strong>Ritmo</strong>: {{$Classroom->ritmoLabel}} </li>
        </ul>

        <ul class="flex flex-wrap">
          <li class="mr-2"><strong>Inició</strong>: {{ $Classroom->start }} </li>
          <li class="mr-2"><strong>Fin</strong>: {{ $Classroom->ends_at }} </li>
          <li class="mr-2">( {{ $Classroom->endsInWeeks() }}w )</li>
        </ul>

        <x-user-card-horarios class="flex flex-wrap" :horarios="$Classroom->getHorarioArray()"></x-user-card-horarios>

        <ul>
          <li><strong>Estudiantes</strong>: {{$Classroom->students()->count()}} {{ $Classroom->tipo==1 ? '/3' : '' }}</li>
          @foreach ($Classroom->students as $student)
            <li>{{$student->name}}</li>
          @endforeach
        </ul>

      </div>
    </article>
  </section>

  <form action="{{ route('admin.classroom.assignStudents', $Classroom->id)}}" method="POST">
    @csrf
    <input type="hidden" name="classroom_id" value="{{ $Classroom->id }}">
    <section class="my-6 md:my-8">
      <h2 class="font-accent text-lg font-medium mb-3">Elegir estudiantes</h2>
      <ul class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:grid-cols-3 xl:grid-cols-4">
        @foreach ($Classroom->students as $student)
        <li>
          <input type="checkbox" name="students[]" id="istudent-id-{{$student->id}}" value="{{$student->id}}" class="peer sr-only" checked aria-checked="true" />
          <label for="istudent-id-{{$student->id}}" class="cursor-pointer block rounded-lg border-2 border-transparent peer-checked:border-green-600 bg-white peer-checked:bg-green-100 p-3">
            <p>#{{$student->id}} {{$student->name}}</p>
            <b class="text-sm">Horarios: </b>
            <x-user-card-horarios :horarios="$student->getHorarioArray()" user_type="2"></x-user-card-horarios>
          </label>

        </li>
        @endforeach

        @foreach ($StudentSinClase as $student)
          <li>
            <input type="checkbox" name="students[]" id="istudent-id-{{$student->id}}" value="{{$student->id}}" class="peer sr-only" aria-checked="false" />

            <label for="istudent-id-{{$student->id}}" class="cursor-pointer block rounded-lg border-2 border-transparent peer-checked:border-green-600 bg-white peer-checked:bg-green-100 p-3">
              <p>#{{$student->id}} {{$student->name}}</p>
              <b class="text-sm">Horarios: </b>
              <x-user-card-horarios :horarios="$student->getHorarioArray()" user_type="2"></x-user-card-horarios>
            </label>
          </li>
        @endforeach
      </ul>
    </section>
    <section class="xl:w-1/2 flex justify-around font-accent font-medium text-sm leading-7">
      <a href="{{ route('admin.classroom.index') }}" class="rounded-full border-2 border-gray-100 py-2 xl:py-0 px-3 shadow-md">Regresar</a>
      <button type="submit" class="rounded-full border-2 border-rojo bg-rojo text-white py-2 xl:py-0 px-3 shadow-md">Guardar</button>
    </section>
  </form>

  @pushOnce('scripts')
  <script>
    console.log('assign-students.blade')
  </script>
  <!--<script defer src="{{ asset('js/admin.classroom.cforcurso.js') }}"></script>-->
  @endPushOnce
</x-admin.layout>