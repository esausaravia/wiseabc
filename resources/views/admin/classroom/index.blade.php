<x-admin.layout>

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
          @if( $clase->tipo===1 )
          <li class="mr-2"><strong>Estudiantes</strong>: {{$clase->students_count}}/3</li>
          @endif
        </ul>
        <ul class="flex flex-wrap">
          <li class="mr-2"><strong>Inició</strong>: {{ $clase->start }} </li>
          <li class="mr-2"><strong>Fin</strong>: {{ $clase->ends_at }} </li>
          <li class="mr-2">( {{ $clase->endsInWeeks() }}w )</li>
        </ul>
        <ul class="flex flex-wrap">
          @foreach ( $clase->getHorarioArray() as $dia=>$arrHr )
          <li class="mr-2">
            <strong>{{ !empty($weekdays[( $dia )]) ? $weekdays[( $dia )] : $dia }}</strong>
            @foreach ($arrHr as $hr )
              {{$hr}}:00,
            @endforeach
          </li>
          @endforeach
        </ul>
      </div>
      <div class="leading-8">
        <a href="{{ route('admin.classroom.edit', ['classroom'=>$clase->id]) }}" class="rounded-full px-3 shadow-md">Editar</a>
      </div>
    </article>
    @endforeach
  </div>

</x-admin.layout>