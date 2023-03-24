<x-admin.layout>
  <h1 class="text-xl md:text-2xl font-accent font-bold mb-5">Crear classroom para curso</h1>

  <div class="lg:flex">
    <div class="lg:flex-grow lg:flex-shrink-0">
      <section class="flex mb-5">
        <div class="shadow-md rounded-lg bg-white dark:bg-white/10 p-5">
          <h4 class="mb-3"><span>[#{{$curso->id}}]</span> {{ $curso->name }}</h4>

          <div class="text-sm">
            <span class="inline-block mr-3"><strong>Edad</strong>: {{$curso->edad_label}}</span>
            <span class="inline-block mr-3"><strong>Nivel</strong>: {{$curso->nivel_label}} [{{$curso->nivel}}]</span>
            <span class="inline-block">({{$curso->duracion}} hrs)</span>
          </div>
        </div>

      </section>{{--/curso--}}

      <form action="{{ route('admin.classroom.create') }}" method="POST">
        @csrf
        <input type="hidden" name="curso_id" value="{{$curso->id}}" />

        <section class="lg:grid lg:grid-cols-2 xl:grid-cols-3 mb-5">

          <x-forms.option-group name="tipo" label="Tipo" required :options="\App\Models\Classroom::$arrTipo" class="mb-5"></x-forms.option-group>

          <x-forms.option-group name="ritmo" label="Intensidad" required :options="config('wiseabc.ritmo_labels')" class="mb-5"></x-forms.option-group>

          <x-forms.input class="mb-5" type="date" name="start" label="Fecha de inicio" :value="old('start', date('Y-m-d'))" required></x-forms.input>

        </section>

        <section class="fieldset mb-5">
          <label for="" class="block after:content-['*'] after:text-rose-700 after:pl-1">Elegir profesor</label>

          <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-4 lg:gap-5">
            @foreach ( $profes as $prof )
            <div>
              <input type="radio" name="teacher_id" id="iprof{{$prof->id}}" value="{{$prof->id}}" class="sr-only peer" @checked( old('teacher_id')==$prof->id ) />
              <label for="iprof{{$prof->id}}" class="block shadow-md rounded-lg bg-white dark:bg-white/10 p-3 lg:p-4 peer-checked:shadow-rose-400 peer-checked:border-2 peer-checked:border-rose-600">
                <div>{{$prof->name}} [{{$prof->id}}]</div>
                <div class="text-sm">
                  <strong class="block">Horarios:</strong>
                  <x-user-card-horarios :user="$prof" :disponibles="true"></x-user-card-horarios>
                </div>
              </label>
            </div>
            @endforeach
          </div>
        </section>{{--/teacher_id--}}

        <section class="mb-5">
          <h3 class="font-accent font-medium text-lg mb-5">Seleccionar horario:</h3>
          <div class="grid grid-cols-4 md:grid-cols-7 gap-4 my-5 text-center">
            @php
              $oldHorarios = old('horarios');
              $arrHorariosDias = array(1,2,3,4,5);
              $arrHorarios = array(
                1=>[9,10,11,12,13],
                2=>[9,10,11,12,13],
                3=>[9,10,11,12,13],
                4=>[9,10,11,12,13],
                5=>[9,10,11,12,13]
              );
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
        <section class="flex justify-around">
          <a href="{{ route('admin.teacher.index')}}" class="btn inline-block rounded-full px-4 shadow-md bg-white text-gray-500 leading-12 font-accent font-medium text-sm">Regresar</a>

          <button type="submit" class="btn rounded-full px-4 bg-rojo text-white leading-12 font-accent font-medium text-sm">Guardar</button>
        </section>
      </form>


    </div>
    <aside class="hidden lg:block lg:w-[300px] lg:flex-shrink-0 ml-5 bg-white p-2">
      <div class="sticky top-[68px]">
        <h3 class="font-accent font-medium">Estudiantes disponibles (<span>33</span>)</h3>

        <ul>
          @for ($_si = 0; $_si<11; $_si++)
          <li class="py-2 border-b border-b-black/10 text-sm last:border-b-0">
            <p>Ricardo Saravia</p>
            <div class="text-xs">
              <p><b>Tipo: </b> <span>Grupal</span> |
                <b>Intensidad: </b> <span>Relax</span>
              </p>
              <p><b class="">Horarios:</b>
                <span>10am, 7pm</span>
              </p>
            </div>
          </li>
          @endfor
        </ul>
      </div>
    </aside>
  </div>
</x-admin.layout>