@if ($message = Session::get('success'))
  <x-alert-toast type="success" :message="$message" />
@endif

@if ($message = Session::get('warning'))
<x-alert-toast type="warning" :message="$message" />
@endif

@if ($message = Session::get('info'))
<x-alert-toast type="info" :message="$message" />
@endif


@if ($message = Session::get('error'))
<x-alert-toast type="error" :message="$message" />
@elseif ($errors->any())
  @error('alert')
    <x-alert-toast type="error" :message="$message" />
  @else
    <x-alert-toast type="error" message="Verifique la información y corrija los errores de señalados arriba." />
  @enderror
@endif