@props([
  'subscripcion' => null
])

<section class="shadow-md rounded-xl overflow-clip bg-gray-50 dark:bg-white/5" >

  @if ( is_object($subscripcion) )
  <div class="bg-blue-100 p-3 lg:p-4" data-subscription-id="{{ $subscripcion->id }}">
    <h4 class="mb-5 font-accent font-semibold text-2xl ">Subscripción: {{ $subscripcion->billingPlan->name }}</h4>

    <div class="my-1 -mx-3 py-1 md:flex md:flex-wrap">
      <div class="px-3 border-b md:border-b-0 md:border-r border-r-black/10">
        <span class="text-sm leading-7" >ID Subscripción:</span>

        @if ( !empty($subscripcion->paypal) )
        <span class="font-semibold">{{ $subscripcion->paypal->api_id }}</span>
        @elseif( !empty($subscripcion->stripe) )
        <span class="font-semibold">{{ $subscripcion->stripe->api_id }}</span>
        @endif

      </div>
      <div class="px-3">
        <span class="text-sm leading-7">Estatus:</span>
        <span class="font-bold text-green-600">{{ __($subscripcion->status.'a') }}</span>
      </div>
    </div>

    <div class="my-1 py-1 border-t border-black/10 flex items-center justify-center bg-gray-50 ">
      <span class="mr-2 text-sm leading-7">Siguiente pago:</span>
      <span class="font-semibold">{{ $subscripcion->next_billing->setTimezone('-0600')->locale('es')->isoFormat('MMMM DD, Y') }}</span>
    </div>
  </div>{{--/bg-blue --}}

  <div class="card-body px-3 lg:px-4">
    <div class="flex my-1 py-1 border-b border-black/10">
      <span class="w-1/3 text-sm leading-7">Plataforma:</span>
      <span class="w-2/3 whitespace-nowrap">{{ !empty($subscripcion->paypal) ? 'PayPal' : 'Stripe' }}</span>
    </div>

    <div class="flex my-1 py-1 border-b border-black/10">
      <span class="w-1/3 text-sm leading-7">Inicio:</span>
      <span class="w-2/3">{{ $subscripcion->start->setTimezone('-0600')->locale('es')->isoFormat('MMMM DD, Y') }}</span>
    </div>

    <div class="flex my-1 py-1 border-b border-black/10">
      <span class="w-1/3 text-sm leading-7">Ciclo:</span>
      <span class="w-2/3">cada 4 semanas</span>
    </div>

    @if ( !empty($subscripcion->paypal) )
    <div class="flex my-1 py-1 border-b border-black/10">
      <span class="w-1/3 text-sm leading-7">PayPal Account:</span>
      <span class="w-2/3 whitespace-nowrap">{{ $subscripcion->paypal->api_object->subscriber->email_address }}</span>
    </div>
    <div class="p-3 lg:p-4">
      {{__('Para cancelar su subscripción, debe ingresar a su cuenta de PayPal.')}}
    </div>
    @endif

    @if( !empty($subscripcion->stripe) )
    <div class="py-3 leading-12 text-base font-accent font-medium text-center">
      <a href="{{ route('student.stripe.create-portal-session') }}" class="inline-block rounded-full shadow-md px-4 bg-gray-100 text-gray-500 dark:bg-white/10 dark:text-white/50">Administrar</a>
    </div>
    @endif

  </div>
  @else
  <div class="p-3">
    <h3>Sin subscripción activa</h3>
  </div>
  @endif{{--/falta-pago --}}

</section>
