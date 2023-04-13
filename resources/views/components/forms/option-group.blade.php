@props([
  'label',
  'name',
  'type'=>'radio',
  'required'=>false,
  'value'=>array(),
  'options'=>array(),
  'readonly'=>array(),
  'id',
  'optcont',
  'helper'=>'',
  'fullwidth'=>false
])
@php
  $id = !empty($id) ? $id : 'i'.(preg_replace('/\[.*\]/i', '', $name) );
  if ( $type==='checkbox' && substr($name, -2)!=='[]' ) {
    $name = $name.'[]';
  }
  if ( !is_array($value) ) {
    $value = array($value);
  }
  if ( !is_array($readonly) ) {
    $readonly = array($readonly);
  }
@endphp
<div {{ $attributes->class(['fieldset'])}} >

  @if ( !empty($label) )

    @if (is_object($label) )
    <label for="{{$id}}" {{ $label->attributes->class(["after:content-['*'] after:text-rose-700 after:pl-1"=>$required]) }} > {{$label}} </label>

    @else
    <label for="{{$id}}" class="block font-medium text-sm @if($required) after:content-['*'] after:text-rose-700 after:pl-1 @endif">{{$label}} </label>
    @endif

  @endif

  @unless($fullwidth)
    <div class="flex">
  @endunless

    @if ( !empty($optcont) && is_object($optcont) )
      <div {{$optcont->attributes->class(['options-container'])}} >
    @else
      <div class="options-container flex flex-wrap rounded-lg shadow-md bg-white text-gray-500 leading-12 xl:leading-8 text-center">
    @endif

      @foreach ( $options as $_ok=>$_option )
        @php $_readonly = in_array($_ok, $readonly) @endphp
        <div class="border-r border-black/5">

          <input class="peer sr-only" type="{{$type}}" name="{{$name}}" id="{{$id.$_ok}}" value="{{$_ok}}" @required($required) @checked( in_array($_ok, $value) )  @if( $_readonly ) readonly aria-readonly="true" onclick="return false;" @endif  />

          <label for="{{$id.$_ok}}" class="cursor-pointer block px-3 peer-checked:bg-blue-500 peer-checked:text-gray-100 peer-aria-readonly:bg-orange-500 peer-aria-readonly:text-gray-100">
            {{$_option}}
          </label>
        </div>
      @endforeach

    </div>{{--/options-cont--}}

  @unless($fullwidth)
    </div>{{--/width--}}
  @endunless

  @if( !empty($helper) )
    <small class="text-xs">{{$helper}}</small>
  @endif

  @error($name)
    <span class="error-msg block text-sm text-rose-700">{{$message}}</span>
  @enderror
</div>