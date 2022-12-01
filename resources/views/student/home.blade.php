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
        <p class="mb-1 font-medium">{{ $next->isoFormat('dddd D MMMM h:00 a') }} – {{ $next->copy()->addHour()->isoFormat('h:00 a') }}</p>
        <p class="mb-2">Teacher: {{$clase->teacher->name}}</p>
      </div>
      <div>
        <a href="#" class="btn flex px-4 py-2 rounded-full border-2 border-white bg-azul text-white">
          <span class="mr-2">Abrir</span>
          <img alt="Abrir Teams" src="{{ asset('img/mteams-white.svg')}}" >
        </a>
      </div>
    </article>
  </section>

  @else {{-- SIN CLASE --}}
  <h1 class="text-2xl lg:text-3xl font-accent">
    Aun no tiene una clase asignada.
  </h1>
  @endif {{-- endif clase --}}
</x-layout>