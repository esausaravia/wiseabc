<x-layout class="student-home text-lg">
@push('scripts')
<script defer src="{{ asset('./js/students.js') }}"></script>
@endpush

  <section class="flex -mx-3">
    @if ( empty( $user->email_verified_at ) )
    <div class="w-full max-w-3xl lg:w-1/2 px-3">
      <section class="shadow-md mb-8 rounded-xl p-4 bg-amber-100 text-amber-600">
        <h2 class=" md:text-xl font-medium font-accent mb-4">Verificación de correo electrónico</h2>
        <p class="mb-2">
          Le enviamos un correo a su dirección {{$user->email}} para verificar su cuenta.
        </p>
        <p class="">
          Por favor, revisé la bandeja de su correo electrónico o su carpeta de correo no deseado (spam).
        </p>
      </section>
    </div>
    @endif {{--/email_verified_at --}}
  </section>

  @if( is_object($classroom) )
    <section class="mb-8 shadow-md rounded-xl overflow-hidden md:grid md:grid-cols-2 xl:grid-cols-4 ">
      <div class="bg-green-100 text-green-800 p-3 flex">
        <figure class="text-5xl mr-3 flex items-center justify-center text-green-700">
          <i class="fa-light fa-person-chalkboard"></i>
        </figure>
        <div>
          <strong class="block text-sm">Curso:</strong>
          <h4>{{$classroom->curso->name}}</h4>
          <strong class="block text-sm">Teacher:</strong>
          <h4>{{$classroom->teacher->name}}</h4>
        </div>
      </div>
      <div class="flex p-3 bg-yellow-100 text-amber-800">
        <figure class="text-5xl mr-3 flex items-center justify-center text-amber-600">
          <i class="fa-light fa-calendars"></i>
        </figure>
        <div>

          <ul class="">
            <li class="mr-2"><strong>Inicio</strong>: {{ $classroom->start->isoFormat('D MMM Y') }} </li>
            <li class="mr-2"><strong>Fin</strong>: {{ $classroom->ends_at->isoFormat('D MMM Y') }} </li>
            <!-- <li class="mr-2">( {{ $classroom->endsInWeeks() }} sem. )</li> -->
          </ul>

          <ul class="flex flex-wrap">
            @foreach ( $classroom->getHorarioArray() as $dia=>$arrHr )
            <li class="mr-2">
              <strong class="capitalize">{{ !empty($weekdays[( $dia )]) ? $weekdays[( $dia )] : $dia }}</strong>:
              @foreach ($arrHr as $hr )
                {{$hr}}:00,
              @endforeach
            </li>
            @endforeach
          </ul>
        </div>
      </div>
      <div class="flex items-center p-3 bg-violet-200 text-violet-800">

        <figure class="text-5xl mr-3 flex items-center justify-center">
          @if ($classroom->tipo==1)
          <i class="fa-light fa-screen-users"></i>
          @else
          <i class="fa-light fa-screen-users"></i>
          @endif
        </figure>

        <div>
          <p class="font-bold text-sm">Tipo:</p>
          <p class="">Clase {{$classroom->tipoLabel}}</p>
        </div>
      </div>
      <div class="p-3 bg-blue-200 text-center">
        <p class="font-bold text-sm">Ritmo:</p>
        <p class="mb-3">{{$classroom->ritmoLabel}}</p>
        <ul class="flex justify-around">
          <li class="rounded-full border-4 h-5 w-5 border-azul-600"></li>
          @switch($classroom->ritmo)
            @case(1)
            <li class="rounded-full border-4 h-5 w-5 border-azul-600"></li>
            <li class="rounded-full border-4 h-5 w-5 border-azul-600 bg-azul-600"></li>
            <li class="rounded-full border-4 h-5 w-5 border-azul-600"></li>
              @break

            @case(2)
            <li class="rounded-full border-4 h-5 w-5 border-azul-600 bg-azul-600"></li>
            <li class="rounded-full border-4 h-5 w-5 border-azul-600"></li>
            <li class="rounded-full border-4 h-5 w-5 border-azul-600 bg-azul-600"></li>

              @break

            @case(3)
            <li class="rounded-full border-4 h-5 w-5 border-azul-600 bg-azul-600"></li>
            <li class="rounded-full border-4 h-5 w-5 border-azul-600 bg-azul-600"></li>
            <li class="rounded-full border-4 h-5 w-5 border-azul-600 bg-azul-600"></li>

              @break

            @case(5)
            <li class="rounded-full border-4 h-5 w-5 border-azul-600 bg-azul-600"></li>
            <li class="rounded-full border-4 h-5 w-5 border-azul-600 bg-azul-600"></li>
            <li class="rounded-full border-4 h-5 w-5 border-azul-600 bg-azul-600"></li>
            <li class="rounded-full border-4 h-5 w-5 border-azul-600 bg-azul-600"></li>
            <li class="rounded-full border-4 h-5 w-5 border-azul-600 bg-azul-600"></li>

              @break

            @default

          @endswitch
          <li class="rounded-full border-4 h-5 w-5 border-azul-600"></li>
        </ul>
      </div>
    </section>

    @if( !is_object($subscripcion) && is_object($billPlan) )
      <div class="w-full max-w-3xl lg:w-2/3 xl:w-1/2 mb-8">
        <section class="shadow-md mb-8 rounded-xl border-[3px] p-4 border-azul-600 bg-white">
          <h2 class=" md:text-xl font-medium font-accent text-azul-600">¡Paga tu subscripción ahora!</h2>
          <p class="my-4">Ahora que tienes profesor y clase asignados, el siguiente paso es acreditar el pago de tu subscripción.</p>

          <div class="md:flex md:-mx-3">
            <div class="md:w-1/2 md:px-3 text-center">
              <h3 class="mb-3 text-2xl font-accent font-medium">{{ $billPlan->name }}</h3>
              {{--<p class="font-bold text-base">{{ ($billPlan->ritmo *4) }} clases</p> --}}
              <div class="flex items-top justify-center">
                <span class="font-bold">$</span>
                <span class="font-accent font-bold text-5xl">{{ $billPlan->price/100 }}</span>
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


          <div id="paypal-subscription-button-container" class="paypal-subscription-button-container max-w-sm my-5 mx-auto text-center" data-billing-plan-id="{{ $billPlan->id }}" data-billing-plan-api-id="{{ $billPlan->paypal->api_id }}" >
            <button class="btn-load leading-8 px-3 bg-azul-700 text-white" type="button" onclick="loadPaypalSDKforSubscription()">Cargar PayPal Subscriptions</button>
          </div>

          <form id="frmCreateCheckoutSession" action="{{ route('student.subscriptions.stripe.create-checkout-session') }}" method="POST" class="max-w-sm mx-auto text-center">
            @csrf
            <input type="hidden" name="billing_plan_id" value="{{ $billPlan->id }}" />
            <button type="submit" class="rounded-full px-3 leading-12 font-accent bg-azul-600 text-white">Stripe</button>
          </form>

        </section>
      </div>
    @endif{{--/falta-pago --}}

    @if( !empty($nextSchedule) && is_object($nextSchedule) )
    <section class="my-8 max-w-3xl lg:w-2/3 xl:w-1/2 ">

      <article class="shadow-md rounded-xl md:flex bg-gray-50 dark:bg-white/5 p-4">
        <div class="mb-5 md:mb-0 md:mr-5">
          <h2 class=" font-accent font-bold text-xl lg:text-2xl">Próxima clase</h2>
          <p class="">{{ $nextSchedule->fechahora->isoFormat('dddd D MMMM h:00 a') }} – {{ $nextSchedule->fechahora->isoFormat('h:40 a')}}</p>
        </div>
        <div>
          @if ( $activarSigClaseBtn )

          <a href="https://teams.microsoft.com/l/meetup-join/19%3ameeting_YTliMzhiMjMtZmExZC00NTc2LWEwNWYtOTI4MWNhZDhhYjU5%40thread.v2/0?context=%7b%22Tid%22%3a%22e196ec1f-12cb-43bf-a0aa-f7d14741ec2e%22%2c%22Oid%22%3a%22467eb4b6-313a-4960-8367-d383828c9a41%22%7d" target="_blank" class="btn flex px-4 py-3 rounded-full border-2 border-white bg-violet-700 text-white">
            <span class="mr-2">Abrir</span>
            <img alt="Abrir Teams" src="{{ asset('img/mteams-white.svg')}}" >
          </a>

          @else

          <a class="btn flex px-4 py-3 rounded-full border-2 border-white bg-gray-600 text-white">
            <span class="mr-2">Abrir</span>
            <img alt="Abrir Teams" src="{{ asset('img/mteams-white.svg')}}" >
          </a>

          @endif
        </div>
      </article>
    </section>
    @endif {{--/nextSchedule--}}

  @else {{-- SIN CLASE --}}
  <h1 class="text-2xl lg:text-3xl font-accent">
    Aun no tiene una clase asignada.
  </h1>
  @endif {{-- endif clase --}}

  @if ( is_object($subscripcion) )
  <section class="flex">
    <div data-subscription-id="{{ $subscripcion->id }}" class="shadow-md rounded-xl overflow-clip bg-gray-50 dark:bg-white/5">
      <div class="bg-blue-100 p-3">
        <h4 class="mb-5 font-accent font-semibold text-2xl ">Subscripción</h4>

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

      <div class="card-body px-3">
        <div class="flex my-1 py-1 border-b border-black/10">
          <span class="w-1/3 text-sm leading-7">Plataforma:</span>
          <span class="w-2/3 whitespace-nowrap">{{ !empty($subscripcion->paypal) ? 'PayPal' : 'Stripe' }}</span>
        </div>

        @if ( !empty($subscripcion->paypal) )
        <div class="flex my-1 py-1 border-b border-black/10">
          <span class="w-1/3 text-sm leading-7">PayPal Account:</span>
          <span class="w-2/3 whitespace-nowrap">{{ $subscripcion->paypal->api_object->subscriber->email_address }}</span>
        </div>
        @endif

        <div class="flex my-1 py-1 border-b border-black/10">
          <span class="w-1/3 text-sm leading-7">Inicio:</span>
          <span class="w-2/3">{{ $subscripcion->start->setTimezone('-0600')->locale('es')->isoFormat('MMMM DD, Y') }}</span>
        </div>

        <div class="flex my-1 py-1 border-b border-black/10">
          <span class="w-1/3 text-sm leading-7">Ciclo:</span>
          <span class="w-2/3">cada 4 semanas</span>
        </div>

      </div>



    </div>
  </section>
  @endif
</x-layout>