<x-layout>
  @if( !empty($clase) && is_a($clase,'App\Models\Classroom') )
  <h1 class="mb-4 font-accent text-2xl lg:text-3xl">Próxima clase</h1>
  <section class="shadow-md rounded-xl bg-white dark:bg-white/5 grid grid-cols-1 lg:grid-cols-2">

    <article class="p-4">
      <h4 class="text-lg font-medium">{{$clase->curso->name}}</h4>

      <div class="text-sm">
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
          <li class="mr-2">( {{ $clase->endsInWeeks() }} sem. )</li>
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
    </article>

    <article class="md:flex border-t lg:border-t-0 border-l border-black/10 p-4">
      <div class="mb-5 md:mb-0 md:mr-5">
        <h4 class="mb-2 text-lg font-medium">Teacher: {{$clase->teacher->name}}</h4>
        <p class="mb-1 font-medium">{{ $sigClase->isoFormat('ddd D MMMM h:00 a') }} – {{ $sigClaseFin->isoFormat('h:mm a') }}</p>
      </div>
      <div>
        @if ( $hoy->lessThan( $sigClaseFin ) && $hoy->greaterThan( $sigClase->copy()->subMinutes(5) ) )

        <a href="https://teams.microsoft.com/l/meetup-join/19%3ameeting_YTliMzhiMjMtZmExZC00NTc2LWEwNWYtOTI4MWNhZDhhYjU5%40thread.v2/0?context=%7b%22Tid%22%3a%22e196ec1f-12cb-43bf-a0aa-f7d14741ec2e%22%2c%22Oid%22%3a%22467eb4b6-313a-4960-8367-d383828c9a41%22%7d" target="_blank" class="btn flex px-4 py-3 rounded-full border-2 border-white bg-violet-700 text-white">
          <span class="mr-2">Abrir</span>
          <img alt="Abrir Teams" src="{{ asset('img/mteams-white.svg')}}" >
        </a>

        @else

        <a class="btn flex px-4 py-3 rounded-full border-2 border-white bg-gray-600 text-white">
          <span class="mr-2">Abrir</span>
          <img alt="Abrir Teams" src="{{ asset('img/mteams-white.svg')}}" >
        </a>

        @endif
      </div>
    </article>
  </section>

  @else {{-- SIN CLASE --}}
  <h1 class="text-2xl lg:text-3xl font-accent">
    Aun no tiene una clase asignada.
  </h1>
  @endif {{-- endif clase --}}
</x-layout>