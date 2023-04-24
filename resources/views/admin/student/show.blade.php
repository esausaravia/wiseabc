<x-admin.layout>
  <h1 class="font-accent font-bold text-xl lg:text-2xl mb-5">Detalle de estudiante #{{$student->id}}</h1>

  <section class="flex flex-wrap">
    <div class="rounded-lg shadow-md p-3 bg-gray-50 mb-5 md:mr-5">

      <h3 class="font-medium text-xl mb-2">{{$student->name}}</h3>
      <p class="mb-2">
        <b class="block -mb-1 font-accent text-xs">e-mail</b>
        {{$student->email}}
      </p>
      <p class="mb-2">
        <b class="block -mb-1 font-accent text-xs">tel</b>
        {{$student->tel}}
      </p>

      <div class="flex flex-wrap -mx-2">
        <p class="mb-2 px-2">
          <b class="block -mb-1 font-accent text-xs">edad</b>
          {{$student->edad_label }}
        </p>
        <p class="mb-2 px-2">
          <b class="block -mb-1 font-accent text-xs">nivel</b>
          {{$student->nivel_label }}
        </p>
        <p class="mb-2 px-2">
          <b class="block -mb-1 font-accent text-xs">Ritmo</b>
          {{ $student->clase_tipo==1 ? 'Grupo' : 'Particular' }}
          {{ $student->ritmo_label }}
        </p>
        <p class="mb-2 px-2">
          <b class="block -mb-1 font-accent text-xs">nivel</b>
          {{$student->nivel_label }}
        </p>

        <p class="mb-2 px-2">
          <b class="block -mb-1 font-accent text-xs">registro</b>
          {{$student->created_at->isoFormat('D MMM Y')}}
        </p>

        @if (!empty($student->deleted_at))
        <p class="mb-2 px-2 text-rojo">
          <b class="block -mb-1 font-accent text-xs">eliminado</b>
          {{$student->created_at->isoFormat('D MMM Y')}}
        </p>
        @endif

        <p class="mb-2 px-2">
          <b class="block -mb-1 font-accent text-xs">timezone</b>
          {{$student->timezone }}
        </p>
      </div>

      <div class="horarios">
        <b class="block -mb-1 font-accent text-xs">horarios</b>
        <x-user-card-horarios :horarios="$student->getHorarioArray()" :user_type="2"></x-user-card-horarios>
      </div>
    </div>{{--/card--}}

    @if( !empty($student->billingplans) && ( $billPlan = $student->billingplans->first() )!==null )
    <section class="rounded-lg shadow-md p-3 bg-gray-50 mb-5 md:mr-5">
      <h3 class="font-bold font-accent text-xs">Suscripción:</h3>
      <p class="mb-2">{{ $billPlan->name }}</p>
      <p>$@money($billPlan->precio)</p>
    </section>
    @endif

    @if ( !empty( $student->currentClassroom ) )
    <section class="rounded-lg shadow-md p-3 bg-gray-50 mb-5 md:mr-5">
      <h4 class="font-accent font-bold text-xs">Classroom actual: #{{$student->currentClassroom->id}}</h4>

      <p class="mb-2">{{$student->currentClassroom->curso->name}}</p>

      <p class="mb-2">
        <b class="block -mb-1 font-accent text-xs">Teacher</b>
        {{$student->currentClassroom->teacher->name}}
      </p>

      <ul class="flex flex-wrap -mx-2 text-sm">
        <li class="mb-2 px-2"><strong class="block text-xs">Edad</strong>
          {{$student->currentClassroom->curso->edadLabel}}
        </li>
        <li class="mb-2 px-2"><strong class="block text-xs">Nivel</strong>
          {{$student->currentClassroom->curso->nivelLabel}}
        </li>
        <li class="mb-2 px-2"><strong class="block text-xs">Tipo</strong>
          {{$student->currentClassroom->tipoLabel}}
        </li>
        <li class="mb-2 px-2"><strong class="block text-xs">Ritmo</strong>
          {{$student->currentClassroom->ritmoLabel}}
        </li>
        <li class="mb-2 px-2"><strong class="block text-xs">Inició</strong>
          {{ $student->currentClassroom->start->isoFormat('D MMM Y') }}
        </li>
        <li class="mb-2 px-2"><strong class="block text-xs">Fin</strong>
          {{ $student->currentClassroom->ends_at->isoFormat('D MMM Y') }}
          ( {{ $student->currentClassroom->endsInWeeks() }}w )
        </li>
      </ul>

      <h4 class="font-bold text-xs">Horarios</h4>
      <x-user-card-horarios class="flex flex-wrap" :horarios="$student->currentClassroom->getHorarioArray()"></x-user-card-horarios>

    </section>{{--/card-currentClassroom--}}
    @endif
  </section>

  <div class="my-5 overflow-auto w-full">
    @foreach ($student->getAttributes() as $_key=>$_val )
      <p>
        <strong>{{$_key}}</strong>: {{ print_r($_val, true) }}
      </p>
    @endforeach

    @foreach ($student->usermetas as $meta )
      <p>
        <strong>{{$meta->metakey}}</strong>: {{ print_r($meta->metaval, true) }}
      </p>
    @endforeach
  </div>
</x-admin.layout>