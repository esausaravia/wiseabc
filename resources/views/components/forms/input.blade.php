@props([
  'id' => null,
  'label' => null,
  'name',
  'placeholder' => null,
  'type' => 'text',
  'value' => null,
  'disabled'=>false,
  'readonly'=>false,
  'required'=>false,
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

    @if (is_object($label) )
    <label for="{{$id}}" {{ $label->attributes->class(["after:content-['*'] after:text-rose-700 after:pl-1"=>$required]) }} > {{$label}} </label>

    @else
    <label for="{{$id}}" class="block font-medium text-sm @if($required) after:content-['*'] after:text-rose-700 after:pl-1 @endif">{{$label}} </label>
    @endif

  @endif

  <input type="{{$type}}" id="{{$id}}" name="{{$name}}" value="{{ old($name, $value) }}" placeholder="{{$placeholder}}" @required($required) @disabled($disabled) @readonly($readonly) class="block {{$inputW}} shadow-sm p-3 xl:py-1 rounded-md border border-black/5 bg-white text-gray-600 disabled:bg-black/5 disabled:dark:bg-white/5 disabled:text-inherit" />

  @error($name)
  <span class="error-msg block text-sm text-rose-700">{{$message}}</span>
  @enderror
</div>