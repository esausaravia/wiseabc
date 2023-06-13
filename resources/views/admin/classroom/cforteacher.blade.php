<x-admin.layout>
  <div class="max-w-[1200px]">
    <h1 class="text-2xl font-accent font-bold mb-5">Crear classroom para profesor</h1>

    <form id="frm-classroom-cforteacher" action="{{ route('admin.classroom.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-2 gap-5">
      @csrf
      <input type="hidden" name="teacher_id" value="{{$profe->id}}">

      <section>

        <section class="flex">
          <div class="rounded-lg shadow-md p-4 lg:p-5 bg-white dark:bg-white/20 flex">
            <figure class="flex-grow-0 flex-shrink-0 rounded-full bg-gray-500 mr-4 overflow-hidden">
              @if( $profe->getProfilePic()!==null )
              <x-img :alt="$profe->name" class="w-[5rem] h-[5rem]" width="80" height="80" :src="$profe->getProfilePic()" srcset="{{$profe->getProfilePic() }} 80w, {{$profe->getProfilePic(160) }} 160w" ></x-img>
              @endif
            </figure>
            <div class="flex-grow">
              <h4>{{$profe->name}}</h4>
            </div>
          </div>
        </section>
      </section>

      <section>

        <x-forms.option-group name="tipo" label="Tipo" required :options="\App\Models\Classroom::$arrTipos" class="mb-5"></x-forms.option-group>

        <x-forms.option-group name="ritmo" label="Intensidad" required :options="config('wiseabc.ritmo_labels')" class="mb-5"></x-forms.option-group>

        <x-forms.input class="mb-5" type="date" name="start" label="Fecha de inicio" :value="old('start', date('Y-m-d'))" required></x-forms.input>


      </section>

      <section>
        <h3 class="font-accent font-medium text-lg mb-5">Seleccionar curso:</h3>
        @foreach ($cursos as $curso)
        <label for="icurso{{$curso->id}}" class="block cursor-pointer mb-5">
          <input type="radio" name="curso_id" id="icurso{{$curso->id}}" value="{{$curso->id}}" class="sr-only peer" @checked( old('curso_id')==$curso->id ) />
          <div class="block shadow-md rounded-xl p-3 lg:p-4 bg-white text-gray-600 peer-checked:shadow-rose-500 peer-checked:border-rose-600 peer-checked:border-2">
            <span>{{$curso->name}}</span>

            <div class="text-sm">
              <span><strong>Edad:</strong> {{$curso->edad_label}}</span>
              <span><strong>Nivel:</strong> {{$curso->nivel_label}}</span>
            </div>
          </div>
        </label>
        @endforeach
      </section>

      <section>
        <h3 class="font-accent font-medium text-lg mb-5">Seleccionar horario:</h3>
        <div class="grid grid-cols-4 md:grid-cols-7 gap-4 my-5 text-center">
          @php
            $oldHorarios = old('horarios');
          @endphp
          @foreach ( $arrHorariosDias as $dia )
          <div class="bg-white text-gray-600 rounded-lg overflow-hidden">
            <p class="px-2 font-accent font-bold text-md pt-1">{{ $weekdays[( $dia )] }}.</p>

            @foreach ($arrHorarios[($dia)] as $hr)
            <label for="idia{{$dia}}hr{{$hr}}" class="cursor-pointer block border-b border-gray-400 last:border-b-0">
              <input type="checkbox" id="idia{{$dia}}hr{{$hr}}" name="horarios[{{$dia}}][]" value="{{$hr}}" class="sr-only peer" @checked( !empty($oldHorarios[( $dia )]) && in_array($hr, $oldHorarios[( $dia )] ) ) />
              <div class="px-2 peer-checked:bg-azulw peer-checked:text-white leading-12 xl:leading-8">{{$hr}}:00</div>
            </label>
            @endforeach
          </div>
          @endforeach
        </div>
      </section>

      <span>
        <a href="{{ route('admin.teacher.index')}}" class="btn inline-block rounded-full px-4 shadow-md bg-white text-gray-500 leading-12 font-accent font-medium text-sm">Regresar</a>
      </span>
      <div class="">
        <button type="submit" class="btn rounded-full px-4 bg-rojo text-white leading-12 font-accent font-medium text-sm">Guardar</button>
      </div>

    </form>
    <textarea name="" id="" rows="10" class="block w-full font-mono my-12">{{ var_dump(old('horarios')) }}</textarea>
  </div>
</x-admin.layout>