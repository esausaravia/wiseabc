@props([
  'label',
  'name',
  'type'=>'radio',
  'required'=>false,
  'value'=>null,
  'options'=>array(),
  'id',
  'optcont'
])
@php
  $id = !empty($id) ? $id : 'i'.(preg_replace('/\[.*\]/i', '', $name) );
  if ( $type==='checkbox' && substr($name, -2)!=='[]' ) {
    $name = $name.'[]';
  }
@endphp
<div {{ $attributes->class(['fieldset'])}} >

  @if ( !empty($label) && is_object($label) )
  <label {{ $label->attributes->class(['block', "after:content-['*'] after:text-rose-700 after:pl-1"=>$required]) }}>{{ $label }}</label>

  @elseif ( !empty($label) )
  <label class="block @if ($required) after:content-['*'] after:text-rose-700 after:pl-1 @endif">{{ $label }}</label>
  @endif

  <div class="flex">

    @if ( !empty($optcont) && is_object($optcont) )
    <div  {{$optcont->attributes->class(['rounded-lg shadow-md bg-white text-gray-500 leading-12 xl:leading-8'])}} >
    @else
    <div class="flex flex-wrap rounded-lg shadow-md bg-white text-gray-500 leading-12 xl:leading-8">
    @endif

      @foreach ( $options as $_ok=>$_option )
      <div class="border-r border-black/5">
        <input class="peer sr-only" type="{{$type}}" name="{{$name}}" id="{{$id.$_ok}}" value="{{$_ok}}" @if($required) required @endif  @checked( $value==$_ok || ( is_array($value) && in_array($_ok, $value) ) ) />
        <label for="{{$id.$_ok}}" class="cursor-pointer block px-3 peer-checked:font-bold peer-checked:text-rojo">
          {{$_option}}
        </label>
      </div>
      @endforeach

    </div>{{--/options-cont--}}
  </div>{{--/width--}}
  @error($name)
  <span class="error-msg block text-sm text-rose-700">{{$message}}</span>
  @enderror
</div>