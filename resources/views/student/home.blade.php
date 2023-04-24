<x-layout class="student-home">

  <section class="flex -mx-3">



    <div class="w-full max-w-3xl lg:w-1/2 px-3">
      <section class="shadow-md mb-8 rounded-xl border-[3px] p-4 border-azul-600 bg-white">
        <h2 class="text-lg md:text-xl font-medium font-accent text-azul-600">¡Paga tu subscripción ahora!</h2>
        <p class="my-4">Ahora que tienes profesor y clase asignados, el siguiente paso es acreditar el pago de tu subscripción.</p>

        <div class="">
          <div id="paypal-button-container-P-5UR330633P879011DMRCWHWY"></div>
<script src="https://www.paypal.com/sdk/js?client-id=AUnc7UCRYV0e9qHJpt9JHmTPo_u8qzwZmu9xtG48fGB6an783RCa_W2Uo4jiUH1IrtBAy0OF8jLYdwSY&vault=true&intent=subscription" data-sdk-integration-source="button-factory"></script>
<script>
  paypal.Buttons({
      style: {
          shape: 'pill',
          color: 'blue',
          layout: 'vertical',
          label: 'subscribe'
      },
      createSubscription: function(data, actions) {
        return actions.subscription.create({
          /* Creates the subscription */
          plan_id: 'P-5UR330633P879011DMRCWHWY'
        });
      },
      onApprove: function(data, actions) {
        alert(data.subscriptionID); // You can add optional success message for the subscriber here
        console.log(data);
      }
  }).render('#paypal-button-container-P-5UR330633P879011DMRCWHWY'); // Renders the PayPal button
</script>
        </div>
      </section>
    </div>

    @if ( empty( $user->email_verified_at ) )
    <div class="w-full max-w-3xl lg:w-1/2 px-3">
      <section class="shadow-md mb-8 rounded-xl p-4 bg-amber-100 text-amber-600">
        <h2 class="text-lg md:text-xl font-medium font-accent mb-4">Verificación de correo electrónico</h2>
        <p class="text-sm mb-2">
          Le enviamos un correo a su dirección {{$user->email}} para verificar su cuenta.
        </p>
        <p class="text-sm">
          Por favor, revisé la bandeja de su correo electrónico o su carpeta de correo no deseado (spam).
        </p>
      </section>
    </div>
    @endif

  </section>

  @if( !empty($clase) && class_basename($clase)=='Classroom')
  <h1 class="mb-4 font-accent text-2xl lg:text-3xl">Próxima clase</h1>
  <section class="shadow-md rounded-xl bg-white dark:bg-white/5 grid grid-cols-1 lg:grid-cols-2">

    <article class="p-4">
      <h4 class="text-lg font-medium">{{$clase->curso->name}}</h4>

      <div class="text-sm">
        <ul class="flex flex-wrap">
          <li class="mr-2"><strong>Edad</strong>: {{$clase->curso->edadLabel}} </li>
          <li class="mr-2"><strong>Nivel</strong>: {{$clase->curso->nivelLabel}} </li>
          <li class="mr-2"><strong>Tipo</strong>: {{$clase->tipoLabel}} </li>
          <li class="mr-2"><strong>Ritmo</strong>: {{$clase->ritmoLabel}} </li>
          @if( $clase->tipo===1 )
          <li class="mr-2"><strong>Estudiantes</strong>: {{$clase->students_count}}/3</li>
          @endif
        </ul>
        <ul class="flex flex-wrap">
          <li class="mr-2"><strong>Inició</strong>: {{ $clase->start }} </li>
          <li class="mr-2"><strong>Fin</strong>: {{ $clase->ends_at }} </li>
          <li class="mr-2">( {{ $clase->endsInWeeks() }} sem. )</li>
        </ul>
        <ul class="flex flex-wrap">
          @foreach ( $clase->getHorarioArray() as $dia=>$arrHr )
          <li class="mr-2">
            <strong>{{ !empty($weekdays[( $dia )]) ? $weekdays[( $dia )] : $dia }}</strong>
            @foreach ($arrHr as $hr )
              {{$hr}}:00,
            @endforeach
          </li>
          @endforeach
        </ul>
      </div>
    </article>

    <article class="md:flex border-t lg:border-t-0 border-l border-black/10 p-4">
      <div class="mb-5 md:mb-0 md:mr-5">
        <h4 class="mb-2 text-lg font-medium">Teacher: {{$clase->teacher->name}}</h4>
        <p class="mb-1 font-medium">{{ $sigClase->isoFormat('ddd D MMMM h:00 a') }} – {{ $sigClaseFin->isoFormat('h:mm a') }}</p>
      </div>
      <div>
        @if ( $hoy->lessThan( $sigClaseFin ) && $hoy->greaterThan( $sigClase->copy()->subMinutes(5) )  )

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

  @else {{-- SIN CLASE --}}
  <h1 class="text-2xl lg:text-3xl font-accent">
    Aun no tiene una clase asignada.
  </h1>
  @endif {{-- endif clase --}}
</x-layout>