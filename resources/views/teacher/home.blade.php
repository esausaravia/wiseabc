<x-layout>
  <div class="max-w-[1200px] mx-auto">a</div>

  <section class=" mb-5 grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-4 text-center">
    <div class="rounded-xl p-4 bg-rose-700 text-white">
      @if ( $clases_para_hoy>0 )
      <p class="mb-2 text-2xl">{{ $clases_para_hoy }}</p>
      <div class="text-sm">
        <p>clases para hoy</p>
      </div>
      @else
      <p class="mb-2 text-2xl"><i class="fa-light fa-snooze"></i></p>
      <div class="text-sm">
        <p>No tienes clases para hoy</p>
      </div>
      @endif
    </div>

    <div class="rounded-xl p-4 bg-blue-700 text-white">
      <p class="mb-2 text-2xl">
        88% <span class="text-white/60 text-xl">/ 88%</span>
      </p>
      <div class="text-sm">
        <p>Asistencia promedio del mes <br>
          <span class="text-white/60">/ asistencia promedio</span>
        </p>
      </div>
    </div>

    <div class="rounded-xl p-4 bg-violet-700 text-white">
      <p class="mb-2 text-2xl">
        $ 999.99
      </p>
      <div class="text-sm">
        <p>Acumulado este mes</p>
      </div>
    </div>
  </section>

  @if ( !empty($clase) )
  <h1 class="mb-5 font-accent font-bold text-xl lg:text-2xl">Próxima clase</h1>
  <section class="mb-5 hover:shadow-md rounded-xl bg-blue-100 dark:bg-white/5 grid grid-cols-1 lg:grid-cols-2">

    <article class="p-4">
      <h4 class="text-lg font-medium ">{{$clase->curso->name}} {{$clase->next->isoFormat('d')}}</h4>

      <div class="text-sm">
        <ul class="flex flex-wrap">
          <li class="mr-2"><strong>Edad</strong>: {{$clase->curso->edadLabel}} </li>
          <li class="mr-2"><strong>Nivel</strong>: {{$clase->curso->nivelLabel}} </li>
          <li class="mr-2"><strong>Tipo</strong>: {{$clase->tipoLabel}} </li>
          <li class="mr-2"><strong>Ritmo</strong>: {{$clase->ritmoLabel}} </li>
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
        <p class="mb-1">
          <i class="fa-light fa-calendar-day"></i>
          {{ $clase->next->isoFormat('ddd D MMM h:00 a') }}
        </p>
        @if ( $clase->students_count<1 )
        SIN ESTUDIANTES
        @else
        <ul>
          @foreach ($clase->students as $student)
            <li>{{$student->name}}</li>
          @endforeach
        </ul>
        @endif
      </div>
      <div class="flex items-start">

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
  @endif

  @if ( empty($clases) || $clases->count()<1 )
  <h1 class="text-2xl lg:text-3xl font-accent">
    Aun no tiene una clase asignada.
  </h1>

  @else

  <section class="grid gap-5 grid-cols-1 lg:grid-cols-2">
    <div>
      <h2 class="mb-4 text-xl lg:text-2xl font-accent font-bold">Siguientes clases</h2>
      <ul>
        @foreach ($clases as $clase)
          <li class="mb-3 hover:shadow-md rounded-lg py-3 px-4 bg-white dark:bg-white/10">
            <p> [{{$clase->id}}] {{$clase->curso->name}}</p>
            <p class="mb-2"><i class="fa-light fa-calendar-day"></i>
              {{ $clase->next->isoFormat('ddd D MMM h:mm a') }}</p>

            <ul class="flex flex-wrap text-sm">
              <li class="mr-2"><strong>Edad</strong>: {{$clase->curso->edadLabel}} </li>
              <li class="mr-2"><strong>Nivel</strong>: {{$clase->curso->nivelLabel}} </li>
              <li class="mr-2"><strong>Tipo</strong>: {{$clase->tipoLabel}} </li>
              <li class="mr-2"><strong>Ritmo</strong>: {{$clase->ritmoLabel}} </li>
              @if( $clase->tipo==1)
              <li class="mr-2"><strong>Estudiantes</strong>: {{$clase->students_count}}/3 </li>
              @endif
            </ul>

            <ul class="flex flex-wrap text-sm">
              @foreach ( $clase->getHorarioArray() as $dia=>$arrHr )
              <li class="mr-2">
                <strong>{{ !empty($weekdays[( $dia )]) ? $weekdays[( $dia )] : $dia }}</strong>
                @foreach ($arrHr as $hr )
                  {{$hr}}:00,
                @endforeach
              </li>
              @endforeach
            </ul>
          </li>
        @endforeach
      </ul>
    </div>
  </section>


  @endif
</x-layout>