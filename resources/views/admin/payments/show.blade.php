<x-admin.layout>
  <section class="grid grid-cols-2 mb-5">
    <div>
      <h1 class="">Pago #{{$Payout->id}}</h1>
      <h3 class="">Para: {{$Payout->user->name}}</h3>
      <p>Fecha: {{ $Payout->created_at->locale('es')->isoFormat('d MMM Y') }}</p>
      <p>Referencia: {{ $Payout->reference }}</p>
    </div>
    <div class="flex justify-end text-center items-start">
      <div class="rounded-full px-4 py-1 bg-green-200 text-green-700">
        <h3>Pagado</h3>
      </div>
    </div>
  </section>
  <section class="my-5 shadow-md rounded-full px-5 py-3 flex flex-wrap justify-around bg-white dark:bg-white/10 text-center ">
    <div class="">
      <h4>Clases</h4>
      <p class="text-3xl font-serif">{{ $num_clases }}</p>
    </div>
    @foreach($arrPagosPorConcepto AS $concepto=>$cant)
    <div class="">
      <h4>{{ $concepto }}</h4>
      <p class="text-3xl font-serif">$@money($cant)</p>
    </div>
    @endforeach
    <div class="">
      <h4>Total</h4>
      <p class="text-3xl font-serif">$@money($Payout->amount)</p>
    </div>
  </section>

  <section class="my-5 ">
    <h2 class="text-2xl font-medium mt-10 mb-5">Clases del periodo</h2>


    <table>
      <thead class=" text-left text-sm font-medium">
        <tr>
          <th class="px-2">Fecha / hora</th>
          <th class="px-2">Clase</th>
          <th class="px-2">Curso</th>
        </tr>
      </thead>
      <tbody>
        @foreach($Payout->attendances AS $attendance)
        <tr>
          <td class="px-2">{{$attendance->fechahora->isoFormat('DD MMM / HH:mm')}}</td>
          <td class="px-2">#{{ $attendance->classroom->id }}</td>
          <td class="px-2">{{ $attendance->classroom->curso->name }}</td>
          <td class="px-2">$@money($attendance->subtotal)</td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <section class="my-5 font-accent font-medium text-sm leading-7">
      <span>
        <a href="{{ route('admin.pagos.index') }}" class="inline-block rounded-full border-2 border-white px-4 bg-white text-gray-600 shadow-md">Regresar</a>
      </span>
    </section>

</x-admin.layout>