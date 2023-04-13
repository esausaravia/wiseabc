<x-admin.layout>
  <div class="max-w-[1200px]">
    <h1 class="text-2xl font-accent font-bold mb-5">
      @empty( $student->id )
        Nuevo estudiante
      @else
        Editar estudiante #{{$student->id}}
      @endempty
    </h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

      <form action="{{ route('admin.student.update', ['student'=>$student]) }}" method="POST" class="">
        @csrf
        @method('PUT')

        <section class="">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

            <x-forms.input name="fname" label="Nombre" :value="$student->fname" required></x-forms.input>

            <x-forms.input name="lname" label="Apellido" :value="$student->lname" required></x-forms.input>

            <x-forms.input type="email" name="email" label="Correo electrónico" :value="$student->email" required></x-forms.input>

            <x-forms.input type="tel" name="tel" label="Teléfono" :value="$student->tel" required></x-forms.input>

            <x-forms.input type="password" name="password" label="Contraseña" ></x-forms.input>

            <x-forms.select label="Estatus" name="status" :options="['active'=>'Activo', 'disabled'=>'Desactivado']" :value="$student->status" required></x-forms.select>

            <x-forms.option-group label="Edad" name="edad" :options="config('wiseabc.edad_labels')" :value="$student->edad" required></x-option-group>

            <x-forms.select label="Nivel" name="nivel" :options="config('wiseabc.nivel_labels')" :value="$student->nivel" required></x-forms.select>
          </div>
        </section>

        <section class="my-5">
          <x-forms.option-group label="Horarios" type="checkbox" name="horarios[1]" :options="config('wiseabc.horarios_labels')" :value="$student->getHorarioArray(1)" :readonly="$arrHorariosOcupados" class="mb-3">
            <x-slot:optcont class="rounded-lg shadow-md grid grid-cols-4 md:grid-cols-8 bg-white text-gray-500 leading-12 xl:leading-8 text-center"></x-slot>
          </x-option-group>

        </section>

        <section class="col-span-2 flex justify-around font-accent font-medium text-sm leading-7">
          <a href="{{ route('admin.student.index') }}" class="btn inline-block rounded-full border-2 py-2 xl:py-0 px-4 border-white bg-white text-gray-600 shadow-md">Regresar</a>

          <button type="submit" class="btn rounded-full border-2 py-2 xl:py-0 px-4 border-rojo bg-rojo text-white shadow-md">Guardar</button>
        </section>
      </form>

      <section>
        @if ( !empty( $student->currentClassroom ) )
        <div class="rounded-lg shadow-md p-3 bg-gray-50 my-5 ">
          <h4 class="font-accent font-lg font-bold">Classroom actual: #{{$student->currentClassroom->id}}</h4>

          <p class="mb-2">{{$student->currentClassroom->curso->name}}</p>
          <p class="mb-2">Teacher: {{$student->currentClassroom->teacher->name}}</p>
          <div class="text-sm mb-2">
            <ul class="flex flex-wrap">
              <li class="mr-2"><strong>Edad</strong>: {{$student->currentClassroom->curso->edadLabel}} </li>
              <li class="mr-2"><strong>Nivel</strong>: {{$student->currentClassroom->curso->nivelLabel}} </li>
              <li class="mr-2"><strong>Tipo</strong>: {{$student->currentClassroom->tipoLabel}} </li>
              <li class="mr-2"><strong>Ritmo</strong>: {{$student->currentClassroom->ritmoLabel}} </li>
            </ul>

            <ul class="flex flex-wrap">
              <li class="mr-2"><strong>Inició</strong>: {{ $student->currentClassroom->start->isoFormat('D MMM Y') }} </li>
              <li class="mr-2"><strong>Fin</strong>: {{ $student->currentClassroom->ends_at->isoFormat('D MMM Y') }} </li>
              <li class="mr-2">( {{ $student->currentClassroom->endsInWeeks() }}w )</li>
            </ul>

            <x-user-card-horarios class="flex flex-wrap" :horarios="$student->currentClassroom->getHorarioArray()"></x-user-card-horarios>

          </div>

        </div>
        @endif

        @php $suscripcion = $student->suscription() @endphp
        @if( !empty($suscripcion) )
        <div class="rounded-lg shadow-md p-3 bg-gray-50 my-5 ">
          <h4 class="font-medium">Suscripción: {{ $suscripcion->name }}</h4>
          <p class="text-sm">{{ $suscripcion->tipo==1 ? 'Grupal' : 'Particular' }} | {{ $ritmo_labels[( $suscripcion->ritmo )] }}</p>
          <p>$@money($suscripcion->precio)</p>
        </div>
        @endif
        <textarea rows="10" class="w-full" readonly>@foreach($student->usermetas AS $meta){{$meta->metakey}}: {{$meta->metaval}}
@endforeach</textarea>
      </section>
    </div>


  </div>
</x-admin.layout>