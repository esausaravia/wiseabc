@foreach ($clases as $clase)
@php
  $arrDias = array();
  foreach($clase->horarios as $hr){
    if (!in_array($hr->dia, $arrDias))
      array_push($arrDias, $hr->dia);
  }
  sort($arrDias);
@endphp
<div class="flex px-5 py-3 rounded-xl shadow-md mb-3 bg-white text-gray-700" data-clase-id="{{$clase->id}}">
  <input type="hidden" name="clases[]" value="{{$clase->id}}">
  <div class="flex-grow">
    <h4>{{$clase->curso->name}}</h4>
    <p class="text-sm">
      <span class="inline-block mr-2"><strong>Tipo</strong>: {{$clase->tipo_label}}</span>
      <span class="inline-block mr-2"><strong>Ritmo</strong>: {{$clase->ritmo_label}}</span>
    </p>
    <p class="text-sm">
      <strong>Días</strong>:
      @foreach ($arrDias as $dia)
      <span class="inline-block mr-2">
        {{ $weekdays[($dia)] }}
        @foreach ($clase->horarios()->where('dia',$dia)->get() as $hr)
          {{ $hr->hr}}:00
        @endforeach
      </span>
      @endforeach
    </p>
  </div>
  @if ( $show_prices )
  <div class="ml-3 flex-grow-0 flex-shrink-0 font-accent flex flex-col">
    @switch($clase->ritmo)
      @case(1)
        <span class="text-2xl">$24.99</span>
        @break
      @case(2)
        <span class="text-2xl">$48.98</span>
        @break
      @case(3)
        <span class="text-2xl">$74.97</span>
        @break
      @case(3)
        <span class="text-2xl">$99.96</span>
        @break
      @default
    @endswitch
    <span class="text-sm leading-none">usd al mes</span>
  </div>
  @endif
</div>
@endforeach