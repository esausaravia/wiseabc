<x-admin.layout class="pagos-paraprofe">
  <h1 class="mb-5 font-bold font-accent text-xl lg:text-2xl">Resumen de pago para profesor</h1>
  <section class="grid grid-cols-2 mb-5">
    <div>
      <h2 class="font-medium text-xl">{{ $Teacher->name }}</h2>
      <p>Antiguedad: {{$Teacher->antiguedad}} meses</p>
    </div>
    <div class="flex justify-end text-center items-start">
      <div class="rounded-full px-4 py-1 bg-orange-200 text-orange-600">
        <h3>Pendiente / {{ $cortePasado }}</h3>
      </div>
    </div>
  </section>
  <section class="my-5 shadow-md rounded-full px-3 flex flex-wrap justify-around bg-white dark:bg-white/10 text-center ">
    <div class="m-3">
      <h4>Clases</h4>
      <p class="text-3xl font-serif">{{ $num_clases }}</p>
    </div>
    @foreach($PaymentConcepts AS $pconcept)
    <div class="m-3">
      <h4>{{ $pconcept->concept }}</h4>
      <p class="text-3xl font-serif">$<span data-pconcept-subtotal="{{$pconcept->id}}">@money( $arrPagosPorConcepto[($pconcept->id)])</span></p>
    </div>
    @endforeach
    <div class="m-3">
      <h4>Total</h4>
      <p class="text-3xl font-serif">$<span class="payment-amount">@money($payment_amount)</span> </p>
    </div>
  </section>

  <form action="{{ route('admin.pagos.store') }}" method="post">
    @csrf
    <input type="hidden" name="user_id" value="{{$Teacher->id}}" />

    <section class="my-5 ">
      <h2 class="text-xl lg:text-2xl font-accent font-medium mt-10 mb-5">Clases del periodo</h2>

      @foreach( $AttendanceCollectionsByDay AS $_dia=>$_Attendances )
      @php
        $dayLabel = $arrDias[($_dia)];
      @endphp
      <section class="mb-10">
        <h3 class="text-lg font-medium">{{ $dayLabel->isoFormat('D MMM') }} </h3>
        <div class="md:grid md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 md:gap-4 lg:gap-5">

          @foreach( $_Attendances AS $attendance )
          <div class="attendance bg-white shadow rounded-xl p-3" data-attendance-id="{{$attendance->id}}">
            <input type="hidden" name="attendance_id[]" value="{{$attendance->id}}" />

            <p class=" mb-2">#{{ $attendance->classroom->id }} {{ $attendance->classroom->curso->name }}, {{$attendance->classroom->tipoLabel}}, {{$attendance->classroom->ritmoLabel}}, {{$attendance->fechahora->format('H:i')}}</p>

            <table class="text-sm leading-8 mx-auto">
              @foreach($attendance->pconcepts AS $pconcept)
                @if($pconcept->id<3)
                <tr data-pconcept-id="{{$pconcept->id}}">
                  <td>
                    <label class="-ml-[18px]">
                      <input type="checkbox" name="attendance_pconcept[{{$attendance->id}}][]" checked value="{{$pconcept->id}}" >
                      <input type="hidden" name="attendance_pconcept_{{$attendance->id}}_{{$pconcept->id}}" value="{{$pconcept->recibo->amount}}">
                      <span> {{$pconcept->concept}}: </span>
                    </label>
                  </td>
                  <td>$@money($pconcept->recibo->amount)</td>
                </tr>
                @else
                <tr class="leading-normal" data-pconcept-id="{{$pconcept->id}}">
                  <input type="hidden" name="attendance_pconcept[{{$attendance->id}}][]" value="{{$pconcept->id}}" >
                  <input type="hidden" name="attendance_pconcept_{{$attendance->id}}_{{$pconcept->id}}" value="{{$pconcept->recibo->amount}}">
                  <td>{{$pconcept->concept}}:</td>
                  <td>$@money($pconcept->recibo->amount)</td>
                </tr>
                @endif
              @endforeach
              <tr class="hidden">
                <td>
                  <label class="-ml-[18px]">
                    <input type="checkbox" name="" checked >
                    <span> Puntualidad: </span>
                  </label>
                </td>
                <td>$6</td>
              </tr>
              <tr class="leading-normal font-medium text-base">
                <td>Subtotal</td>
                <td class="attendance-subtotal">$@money($attendance->subtotal)</td>
              </tr>

            </table>
          </div>
          @endforeach
        </div>
      </section><!--/dia-->
      @endforeach

    </section><!--/clases-->

    <section class="lg:w-3/4 xl:w-1/2 2xl:w-1/3 my-5">
      <h2 class="text-xl lg:text-2xl font-accent font-medium mb-5">Datos de pago</h2>
      <div class="grid grid-cols-2 gap-3 lg:gap-4 xl:gap-5 mb-3">
        <x-forms.input label="Fecha" name="fecha" type="date" required :value="date('Y-m-d')" />

        <x-forms.input label="Hora" name="hora" type="time" required :value="date('H:i')" />
      </div>

      <x-forms.input label="Método de pago" name="metodopago" class="mb-3" value="PayPal" />

      <x-forms.input label="Monto" name="amount" class="mb-3" required :value="$payment_amount" />

      <x-forms.input label="Referencia" name="reference" class="mb-3" required />

      <div class="flex justify-around font-accent font-medium text-sm leading-7">
        <a href="{{ route('admin.pagos.index') }}" class="inline-block rounded-full border-2 py-2 xl:py-0 px-4 border-white bg-white text-gray-600 shadow-md">Regresar</a>

        <button class="rounded-full border-2 py-2 xl:py-0 px-4 border-rojo bg-rojo text-white shadow-md" data-toggle="modal" data-target="modal-pago-form">Pagar</button>
      </div>
    </section>

  </form>

  @pushOnce('scripts')
  <script>
    const PageFile = 'pagos-paraprofe';
    const Attendances = @json($Attendances);
    const arrPagosPorConcepto = @json($arrPagosPorConcepto);
    let PaymentAmount = {{ $payment_amount }};
  </script>
  <script defer src="{{ asset('js/admin.pagos.js') }}"></script>
  @endPushOnce
</x-admin.layout>