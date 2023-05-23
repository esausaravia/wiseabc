@props([
  'weekdays'=>config('wiseabc.weekdays')
])
<x-mail::message >
#### Estimad@ {{$student->name}},

# Ya tienes tu curso asignado.

Curso: **{{$classroom->curso->name}}**

Tipo: **{{ $classroom->tipo_label.' '.$classroom->ritmo_label }}**

Profesor: **{{$classroom->teacher->name}}**

Edad: **{{ $classroom->edad_label }}**

Ritmo: **{{ $classroom->ritmo }} {{ $classroom->ritmo>1 ? 'clases' : 'clase' }} por semana**

Inició: **{{ $classroom->start->setTimezone($student->timezone)->isoFormat('DD MMMM Y') }}**

Horarios:
@foreach ( $student->transformHorariosTimezone( $classroom->getHorariosArray() ) as $dia=>$arrHr)
**{{ $weekdays[( $dia )] }}**: @foreach ($arrHr as $hr) {{$hr}}:00, @endforeach
@endforeach


Paga tu subscripción lo antes posible para evitar perder tu primera clase.

<x-mail::button :url="config('app.url')" color="primary">
Suscríbete
</x-mail::button>

</x-mail::message>