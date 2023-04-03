<x-admin.layout>
  <div class="max-w-[1200px]">
    <h1 class="text-2xl font-accent font-bold mb-5">Asignar clase</h1>

    <form method="POST" class="">
      @csrf
      <input type="hidden" name="user_id" value="{{$Student->id}}">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">

        <section class="">
          <h3 class="text-lg font-accent font-medium">Estudiante</h3>
          <div class="rounded-lg shadow-md bg-white dark:bg-white/10 p-3">

            <p class="text-lg">{{$Student->name}}</p>
            <div class="text-sm my-2 flex">
              <p class="mr-2"><strong>Email</strong>: <a href="mailto:{{ $Student->email }}">{{ $Student->email }}</a></p>
              <p class="mr-2"><strong>Tel</strong>: {{$Student->tel }}</p>
              <p class="mr-2"><strong>Edad</strong>: {{$Student->edadLabel }} </p>
              <p class="mr-2"><strong>Nivel</strong>: {{$Student->nivelLabel }}</p>
            </div>
            <x-user-card-horarios class="flex flex-wrap" :horarios="$Student->getHorarioArray()" :user_type="2"></x-user-card-horarios>
          </div>

        </section>

        <section class="">
          <h3 class="text-lg font-accent font-medium">Suscripción</h3>
          <div class="rounded-lg shadow-md bg-white dark:bg-white/10 p-3">
            <p><b>{{$Suscripcion->name}}</b></p>
            <p class="text-sm">
              <b>Tipo: </b> {{ $Suscripcion->tipo==1 ? 'Grupal' : 'Particular' }} | <b>Ritmo: {{ $arrRitmos[( $Suscripcion->ritmo )] }} </b>
            </p>
          </div>
        </section>
      </div>

      <section id="clases-disponibles">

        @if( empty($clases) || $clases->count()<1 )
          <h3 class="text-lg font-accent font-medium text-rose-600 mb-5">No hay clases disponibles</h3>
          <h4 class="font-accent font-medium">Otras clases similares</h4>

          <ul class="results grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

            @foreach ($otrasClases as $clase)
            <li class="">
              <label for="iclase-{{ $clase->id }}" class="block shadow-md rounded-lg p-4 bg-white dark:bg-white/10 peer-checked:shadow-blue-500 peer-checked:border-2 peer-checked:border-blue-500">
                <p class="">{{$clase->curso->name}}</p>
                <p class="mb-2">{{ $clase->teacher->name }}</p>
                <div class="text-sm">

                  <ul class="flex flex-wrap">
                    <li class="mr-2"><strong>Tipo</strong>: {{$clase->tipoLabel}} </li>
                    <li class="mr-2"><strong>Ritmo</strong>: {{$clase->ritmoLabel}} </li>
                    <li class="mr-2"><strong>Estudiantes</strong>: {{$clase->students_count}}/3</li>
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
              </label>
            </li>
            @endforeach
          </ul>
        @else
        <h3 class="text-lg font-accent font-medium">Clases disponibles</h3>
        <ul class="results grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

          @foreach ($clases as $clase)
          <li class="">
            <input type="radio" name="class_id" id="iclase-{{ $clase->id }}" value="{{ $clase->id }}" required class="sr-only peer" />
            <label for="iclase-{{ $clase->id }}" class="block cursor-pointer shadow-md rounded-lg p-4 bg-white dark:bg-white/10 peer-checked:shadow-blue-500 peer-checked:border-2 peer-checked:border-blue-500">
              <p class="">{{$clase->curso->name}}</p>
              <p class="mb-2">{{ $clase->teacher->name }}</p>
              <div class="text-sm">

                <ul class="flex flex-wrap">
                  <li class="mr-2"><strong>Tipo</strong>: {{$clase->tipoLabel}} </li>
                  <li class="mr-2"><strong>Ritmo</strong>: {{$clase->ritmoLabel}} </li>
                  <li class="mr-2"><strong>Estudiantes</strong>: {{$clase->students_count}}/3</li>
                </ul>
                <ul class="flex flex-wrap">
                  <li class="mr-2"><strong>Inició</strong>: {{ $clase->start }} </li>
                  <li class="mr-2"><strong>Fin</strong>: {{ $clase->ends_at }} </li>
                  <li class="mr-2"><strong>Quedan</strong>: {{ $clase->endsInWeeks() }} semanas</li>
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
            </label>
          </li>
          @endforeach
        </ul>
        @endempty
      </section>

      <section class="flex justify-around">
        @if( !empty($clases) && $clases->count()>0 )
          <button type="submit" class="btn rounded-full px-4 leading-12 bg-rojo text-white font-accent font-medium text-sm ">Guardar</button>
        @endif
      </section>
    </form>
  </div>
</x-admin.layout>