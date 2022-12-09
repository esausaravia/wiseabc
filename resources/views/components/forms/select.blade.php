@props([
  'label',
  'name',
  'required'=>false,
  'value'=>null,
  'options'=>array(),
  'id',
  'multiple'=>false
])
@php
  $id = !empty($id) ? $id : 'i'.(preg_replace('/\[.*\]/i', '', $name) );
  if ( !empty($multiple) && substr($name, -2)!=='[]' ) {
    $name = $name.'[]';
  }
@endphp
<div {{ $attributes->class(['fieldset'])}} >

  @if ( !empty($label) && is_object($label) )
  <label {{ $label->attributes->class(['block', "after:content-['*'] after:text-rose-700 after:pl-1"=>$required]) }}>{{ $label }}</label>

  @elseif ( !empty($label) )
  <label class="block @if ($required) after:content-['*'] after:text-rose-700 after:pl-1 @endif">{{ $label }}</label>
  @endif

  <select name="{{$name}}" id="{{$id}}" class="block rounded-md shadow-sm h-12 xl:h-8 px-2 border border-black/10 bg-white text-gray-600" value="{{ old($name, $value) }}" >
    <option value="">--</option>
  @foreach ( $options as $_key=>$_val)
      <option value="{{ $_key }}">{{ $_val }}</option>
    @endforeach
  </select>
  @error($name)
  <span class="error-msg block text-sm text-rose-700">{{$message}}</span>
  @enderror
</div>
