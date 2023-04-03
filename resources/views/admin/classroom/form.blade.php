<x-admin.layout>
  <div class="max-w-[1200px]">
    <h1 class="text-xl font-accent font-bold mb-5">
      @empty( $clase->id )
        Nueva clase
      @else
        Editar clase #{{$clase->id}}
      @endempty
    </h1>
    <form action="{{route('admin.classroom.update', ['classroom'=>$clase->id])}}" method="POST" class="grid grid-cols-1 gap-5 lg:grid-cols-2">
      @csrf
      @method('PUT')
      <div class="flex">
        <article class="shadow-md rounded-lg p-3 bg-white dark:bg-white/5">
          <p>{{$clase->curso->name}}</p>
          <ul class="text-sm flex flex-wrap">
            <li class="mr-2"><strong>Edad</strong>: {{$clase->curso->edadLabel}}</li>
            <li class="mr-2"><strong>Nivel</strong>: {{$clase->curso->nivel_label}}</li>
            <li class="mr-2"><strong>Duracion</strong>: {{$clase->curso->duracion}}</li>
          </ul>
        </article>
      </div>
      <div class="flex">

        <article class="shadow-md rounded-lg p-3 flex bg-white dark:bg-white/5">
          <figure class="rounded-full w-20 h-20 mr-4 flex-shrink-0 flex-grow-0 bg-black/25"></figure>
          <div>
            <p>{{$clase->teacher->name}}</p>
          </div>
        </article>
      </div>

      <section>
        <div>
          <x-forms.option-group name="tipo" label="Tipo" :options="\App\Models\Classroom::$arrTipos" :value="$clase->tipo" class="mb-5" required></x-forms.option-group>

          <x-forms.option-group name="ritmo" label="Intensidad" :options="config('wiseabc.ritmo_labels')" :value="$clase->ritmo" class="mb-5" required></x-forms.option-group>

          <x-forms.input class="mb-5" type="date" name="start" label="Fecha de inicio" :value="$clase->start" required></x-forms.input>
        </div>
      </section>

      <section>
        <h3 class="font-accent font-medium mb-5">Seleccionar horario:</h3>
        <div class="grid grid-cols-4 md:grid-cols-7 gap-4 my-5 text-center">
          @php
            $oldHorarios = old('horarios');
            if ( empty($oldHorarios) || !is_array($oldHorarios) ) {
              $oldHorarios = array();
              foreach( $clase->getHorarioArray() AS $_dia=>$arrHr) {
                $oldHorarios[($_dia)] = $arrHr;
              }
            }
          @endphp
          @foreach ( $arrHorariosDias as $dia )
          <div class="bg-white text-gray-600 rounded-lg overflow-hidden">
            <p class="px-2 font-accent font-bold text-md pt-1">{{ $weekdays[( $dia )] }}.</p>

            @foreach ($arrHorarios[($dia)] as $hr)
            <label for="idia{{$dia}}hr{{$hr}}" class="cursor-pointer block border-b border-gray-400 last:border-b-0">
              <input type="checkbox" id="idia{{$dia}}hr{{$hr}}" name="horarios[{{$dia}}][]" value="{{$hr}}" class="sr-only peer" @checked( !empty($oldHorarios[( $dia )]) && in_array($hr, $oldHorarios[( $dia )] ) ) />
              <div class="px-2 peer-checked:bg-azul peer-checked:text-white leading-12 xl:leading-8">{{$hr}}:00</div>
            </label>
            @endforeach
          </div>
          @endforeach
        </div>
      </section>
      <section class="col-span-2 flex justify-around leading-12 font-accent font-medium text-sm">
        <a href="{{ route('admin.classroom.index') }}" class="btn rounded-full mx-4 px-4 shadow-md bg-white text-gray-500">Regresar</a>
        <button type="submit" class="btn rounded-full mx-4 px-4 shadow-md bg-rojo text-white">Guardar</button>
      </section>
    </form>{{--/grid --}}
  </div>
</x-admin.layout>