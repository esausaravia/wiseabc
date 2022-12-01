@if ($message = Session::get('success'))
<div class="alert fixed bottom-0 left-1/2 -translate-x-1/2 shadow-lg rounded-t-xl p-5 bg-green-200 text-green-900" role="alert">
  <span class="block">{{ $message }}</span>
  <button type="button" class="btn-close absolute top-0 right-0 w-6" aria-label="Close"><i class="fa-light fa-times"></i></button>
</div>
@endif

@if ($message = Session::get('error'))
<div class="alert fixed bottom-0 left-1/2 -translate-x-1/2 shadow-lg rounded-t-xl p-5 bg-rose-200 text-rose-700" role="alert">
  <span class="block">{{ $message }}</span>
  <button type="button" class="btn-close absolute top-0 right-0 w-6" aria-label="Close"><i class="fa-light fa-times"></i></button>
</div>
@endif

@if ($message = Session::get('warning'))
<div class="alert fixed bottom-0 left-1/2 -translate-x-1/2 shadow-lg rounded-t-xl p-5 bg-amber-200 text-amber-700" role="alert">
  <span class="block">{{ $message }}</span>
  <button type="button" class="btn-close absolute top-0 right-0 w-6" aria-label="Close"><i class="fa-light fa-times"></i></button>
</div>
@endif

@if ($message = Session::get('info'))
<div class="alert fixed bottom-0 left-1/2 -translate-x-1/2 shadow-lg rounded-t-xl p-5 bg-sky-200 text-sky-700" role="alert">
  <span class="block">{{ $message }}</span>
  <button type="button" class="btn-close absolute top-0 right-0 w-6" aria-label="Close"><i class="fa-light fa-times"></i></button>
</div>
@endif

@if ($errors->any())
<div class="alert fixed bottom-0 left-1/2 -translate-x-1/2 shadow-lg rounded-t-xl p-5 bg-rose-200 text-rose-700" role="alert">
  <span class="block">Verifique la información y corrija los errores de arriba.</span>
  <button type="button" class="btn-close absolute top-0 right-0 w-6" aria-label="Close"><i class="fa-light fa-times"></i></button>
</div>
@endif