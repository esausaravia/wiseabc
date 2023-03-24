@props([
  'name',
  'type'=>'text',
  'value'=>null,
  'label',
  'required'=>false,
  'id',
  'placeholder',
  'disabled'=>false
])
@php
$id = !empty( $id ) ? $id : 'i'.$name ;
$placeholder = !empty($placeholder) ? $placeholder : (!empty($label) ? $label : '');

$inputW = 'w-full';
if ( $type==='date' ) {
  $inputW = 'w-[10rem]';
}
if ( $type==='time' ) {
  $inputW = 'w-[7rem]';
}

@endphp
<div {{ $attributes->class(['fieldset'])}}>
  @if ( !empty($label) )
  <label for="{{$id}}" class="block @if ($required) after:content-['*'] after:text-rose-700 after:pl-1 @endif">{{$label}} </label>
  @endif

  <input type="{{$type}}" id="{{$id}}" name="{{$name}}" value="{{ old($name, $value) }}" placeholder="{{$placeholder}}" @if ($required) required @endif @if ($disabled) disabled @endif class="block {{$inputW}} shadow-sm p-3 xl:py-1 rounded-md border border-black/5 bg-white text-gray-600 disabled:bg-transparent disabled:text-inherit">
  @error($name)
  <span class="error-msg block text-sm text-rose-700">{{$message}}</span>
  @enderror
</div>