@props([
  'dia'=>"",
  'horarios'=>array(),
  'tachar'=>array(),
  'user_type'=>3
])
@if ( !empty($horarios) )
  <li class="mr-2">
    @if($user_type==3 && !empty($dia))
    <strong>{{ $dia }}</strong>:
    @endif
    @foreach( $horarios AS $hr )
      {{ $hr }}:00,
    @endforeach
  </li>
@endif