<x-admin.layout>
  <h1 class="text-2xl font-medium mb-5">Pagos para profesores</h1>

  <section class="">

    <table class="w-full">
      <thead class="text-sm">
        <tr>
          <th class="px-2 py-1 text-left">Profesor</th>
          <th class="px-2 py-1 w-2/12">Cantidad</th>
          <th class="px-2 py-1 w-2/12">Estatus / Fecha</th>
          <th class="px-2 py-1 w-2/12">Acciones</th>
        </tr>
      </thead>
      <tbody class=" grid grid-cols-1 gap-4 md:table-row-group">
        @foreach( $TeachersNotPaid AS $teacher )
        <tr class="block md:table-row odd:bg-white even:bg-white/50 dark:odd:bg-white/10 dark:even:bg-white/5 text-center">
          <td class="px-2 py-2 text-left">{{ $teacher->name }}</td>
          <td class="px-2 py-2">${{$teacher->saldo_pendiente}}</td>
          <td class="px-2 py-2 text-sm">
            <p class="">
              <span class="inline-block rounded-2xl px-3 leading-6 bg-orange-100 text-orange-400">
                <span class="ico"><i class="fa-light fa-hourglass-clock"></i></span>
                Pendiente
              </span>
            </p>
            <p class="">{{ $cortePasado }}</p>
          </td>
          <td class="">
            <div class="flex justify-center">
              <a href="{{ route('admin.pagoparaprofe', $teacher->id) }}" class="btn rounded-3xl px-3 py-1 bg-white text-gray-600 text-sm font-accent font-medium shadow-md">detalle</a>
            </div>
          </td>
        </tr>
        @endforeach

        @foreach($Payments AS $payment)
        <tr class="block md:table-row odd:bg-white even:bg-white/50 dark:odd:bg-white/10 dark:even:bg-white/5 text-center">
          <td class="px-2 py-2 text-left">{{ $payment->user->name }}</td>
          <td class="px-2 py-2">${{$payment->amount}}</td>
          <td class="px-2 py-2 text-sm">
            <p class="">
              <span class="inline-block rounded-2xl px-3 leading-6 bg-green-100 text-green-600">
                <span class="ico"><i class="fa-regular fa-check"></i></span>
                Pagado
              </span>
            </p>
            <p class="">{{ $cortePasado }}</p>
          </td>
          <td class="">
            <div class="flex justify-center">
              <a href="{{ route('admin.pagos.show', $payment->id) }}" class="btn rounded-3xl px-3 py-1 bg-white text-gray-600 text-sm font-accent font-medium shadow-md">detalle</a>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </section>
</x-admin.layout>