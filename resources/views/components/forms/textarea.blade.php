@props([
  'label',
  'name',
  'required'=>false,
  'value'=>null,
  'id',
  'rows'=>6,
  'placeholder'=>null,
  'maxlenght'=>''
])
@php
  $id = !empty($id) ? $id : 'i'.(preg_replace('/\[.*\]/i', '', $name) );
  $placeholder = !empty($placeholder) ? $placeholder : ( !empty($label) ? $label : '' );
@endphp
<div {{ $attributes->class(['fieldset']) }} >

  @if ( !empty($label) )

    @if (is_object($label) )
    <label for="{{$id}}" {{ $label->attributes->class(["after:content-['*'] after:text-rose-700 after:pl-1"=>$required]) }} > {{$label}} </label>

    @else
    <label for="{{$id}}" class="block font-medium text-sm @if($required) after:content-['*'] after:text-rose-700 after:pl-1 @endif">{{$label}} </label>
    @endif

  @endif

  <textarea name="{{$name}}" id="{{$id}}" rows="{{$rows}}" maxlength="{{$maxlenght}}" @if ($required) required @endif class="block w-full shadow-sm rounded-md px-3 py-2 sm:text-sm bg-white border border-slate-300 placeholder-slate-400 dark:text-gray-600 focus:border-sky-500 focus:ring-sky-500 focus:ring-1 invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500" placeholder="{{$placeholder}}">{{ old($name, $slot) }}</textarea>

  @error($name)
  <span class="error-msg block text-xs text-rose-700">{{$message}}</span>
  @enderror
</div>