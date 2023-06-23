@props([
  'billplan' => null,
  'subscriptionQty' => null,
  'subscriptionStartDate' => null,
  'subscripcion' => null
])
@if ( is_object($subscripcion) )

  <section class="shadow-md rounded-xl overflow-clip bg-gray-50 dark:bg-white/5" data-subscription-id="{{ $subscripcion->id }}" >
    <div class="bg-blue-100 p-3 lg:p-4">
      <h4 class="mb-5 font-accent font-semibold text-2xl ">Subscripción: {{ $subscripcion->billingPlan->name }}</h4>

      <div class="my-1 -mx-3 py-1 md:flex md:flex-wrap">
        <div class="px-3 border-b md:border-b-0 md:border-r border-r-black/10">
          <span class="text-sm leading-7">ID Subscripción:</span>

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

  </section>

@elseif( is_object($billplan) )

  <section class="shadow-md mb-8 rounded-xl border-[3px] p-4 text-center border-azul-600 bg-gray-50 dark:bg-azulw text-">

    <h3 class="mb-5 text-xl md:text-2xl font-semibold font-accent text-azul-600 dark:text-gray-300">Un Paso Más: Activa tu Suscripción</h3>

    <p class="my-4">Por favor, haz tu pago ahora para comenzar tus clases en los horarios y curso que elegiste, ¡nos vemos en línea!</p>

    <div class="md:flex md:-mx-3">
      <div class="md:w-1/2 md:px-3 dark:text-gray-300">
        <h3 class="mb-2 text-xl font-accent font-medium">{{ $billplan->name }}</h3>
        {{--<p class="font-bold text-base">{{ ($billplan->ritmo *4) }} clases</p> --}}
        <div class="flex items-top justify-center">
          <span class="font-bold">$</span>
          <span class="font-accent font-bold text-5xl">{{ $billplan->price*.8/100 }}</span>
        </div>
        <p>cada 4 semanas</p>
      </div>
      <div class="md:w-1/2 md:px-3">
        <ul class="mx-5 pl-5 list-disc text-left text-base">
          <li class="">$9 usd por clase</li>
          <li>1 clase por semana</li>
          <li>Clase grupal de 40 min.</li>
          <li>Grupo de hasta 3 estudiantes</li>
        </ul>
      </div>
    </div>

    {{--PAYPAL CHECKOUT
    <div class="paypal paypal-container max-w-sm my-5 mx-auto loading" data-paypal-plan-id="{{ $billplan->paypal->api_id }}" data-billing-plan-id="{{ $billplan->id }}" data-quantity="{{ $subscriptionQty }}" data-start="{{ $subscriptionStartDate }}" >
    </div>
    --}}
    <form id="frmCreateCheckoutSession" action="{{ route('student.stripe.create-checkout-session') }}" method="POST" class="max-w-sm my-8 mx-auto text-center">
      @csrf
      <input type="hidden" name="billing_plan_stripe_id" value="{{ $billplan->stripe->api_id }}" />
      @if( !empty($subscriptionQty) )
      <input type="hidden" name="subscription_qty" value="{{ $subscriptionQty }}">
      @endif

      @if( !empty($subscriptionStartDate) )
      <input type="hidden" name="start_date" value="{{ $subscriptionStartDate }}">
      @endif

      <input type="hidden" name="coupon" value="AqH3o0Fl" />

      <button type="submit" class="btn rounded-full shadow-md px-5 leading-12 font-accent font-medium bg-rojo text-white">PAGAR</button>
    </form>
    <div class="">
      <a href="{{ route('student.elegir-ritmo') }}" class="btn rounded-full border-2 py-2 px-4 leading-7 font-accent font-medium text-base border-azul-600 text-azul-600 dark:border-gray-400 dark:text-gray-300">{{__('Cambiar subscripción')}}</a>
    </div>

  </section>

@endif{{--/falta-pago --}}