@props([
  'user'=>\Illuminate\Support\Facades\Auth::user()
])
<x-document >
  <div class="min-h-screen flex items-center justify-center">
    <section class="max-w-xl shadow-md rounded-xl p-5 bg-gray-50 text-center">
      <h2 class="mb-3 text-3xl lg:text-4xl font-semibold">@lang('¡Hola!')</h2>
      <h1 class="">@lang('Es necesario verificar su cuenta de correo electrónico')</h1>
      <p class="underline">{{ $user->email }}</p>
      <p>De esta manera podemos garantizar que recibirá las notificaciones de sus clases, subscripción, etc.</p>
    </section>
  </div>
</x-document>