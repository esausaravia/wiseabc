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
@endphp
<div {{ $attributes->class(['fieldset'])}}>
  @if ( !empty($label) )
  <label for="{{$id}}" class="block @if ($required) after:content-['*'] after:text-rose-700 after:pl-1 @endif">{{$label}} </label>
  @endif

  <input type="{{$type}}" id="{{$id}}" name="{{$name}}" value="{{ old($name, $value) }}" placeholder="{{$placeholder}}" @if ($required) required @endif @if ($disabled) disabled @endif class="block w-full shadow-sm p-3 xl:py-1 rounded-md border border-black/5 bg-white text-gray-600 disabled:bg-transparent disabled:text-inherit">
  @error($name)
  <span class="error-msg block text-sm text-rose-700">{{$message}}</span>
  @enderror
</div>