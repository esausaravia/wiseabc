<x-admin.layout>
  <h2 class="font-accent font-medium text-xl">Detalle de estudiante</h2>
  <div class="my-5">
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

    <div class="my-5">
      <h3 class="font-medium font-accent">Horarios</h3>
      <x-user-card-horarios :horarios="$student->getHorarioArray()" :user_type="2"></x-user-card-horarios>
    </div>
  </div>
  <section class="my-5">
    <h3 class="font-accent font-medium text-lg">Clase asignada</h3>
    @foreach ($student->classrooms as $clase)
      <li data-id="{{$clase->id}}">
        {{$clase->curso->name}}
        <div class="text-sm">
          <ul class="flex flex-wrap">
            <li class="mr-2"><strong>Edad</strong>: {{$clase->curso->edad_label}}</li>
            <li class="mr-2"><strong>Nivel</strong>: {{$clase->curso->nivel_label}}</li>
            <li class="mr-2"><strong>Tipo</strong>: {{$clase->tipo_label}}</li>
            <li class="mr-2"><strong>Ritmo</strong>: {{$clase->ritmo_label}}</li>
          </ul>
          <ul class="flex flex-wrap">
            <li class="mr-2"><strong>Inicio</strong>: {{$clase->start}}</li>
            <li class="mr-2"><strong>Fin</strong>: {{ $clase->ends_at }}</li>
            <li class="mr-2"><strong>Quedan</strong>: {{$clase->endsInWeeks() }} semanas</li>
            <li class="mr-2"><strong>Asignada</strong>: {{$clase->pivot->created_at }}</li>
          </ul>
          <strong class="block">Horarios</strong>
          <x-user-card-horarios :horarios="$clase->getHorarioArray()"></x-user-card-horarios>
        </div>
      </li>
    @endforeach
  </section>
</x-admin.layout>