<x-admin.layout>
  <h1 class="text-2xl mb-5">Pagos</h1>
  <section class="grid grid-cols-1 gap-4 md:grid-cols-3 xl:gap-5 my-5">
    <div class="rounded-2xl bg-black/10 dark:bg-white/10 py-4 px-5 text-center">
      <h3>Ingresos acumulados</h3>
      <p class="text-[2rem] font-medium">$9,999.99</p>
      <p class="text-sm">estimados a fin de mes</p>
      <p>$99,999.99</p>
    </div>

    <div class="rounded-2xl bg-black/10 dark:bg-white/10 py-4 px-5 text-center">
      <h3>Egresos acumulados</h3>
      <p class="text-[2rem] font-medium">$9,999.99</p>
      <p class="text-sm">estimados a fin de mes</p>
      <p>$99,999.99</p>
    </div>

    <div class="rounded-2xl bg-black/10 dark:bg-white/10 py-4 px-5 text-center">
      <h3>Próximo corte</h3>
      <p class="text-[2rem] font-medium">30 dic</p>
    </div>
  </section>

  <section class="">
    <table>
      <thead class="text-sm">
        <tr>
          <th class="px-2 py-1 w-6/12">Profesor</th>
          <th class="px-2 py-1 w-2/12">Cantidad</th>
          <th class="px-2 py-1 w-2/12">Estatus / Fecha</th>
          <th class="px-2 py-1 w-2/12">Acciones</th>
        </tr>
      </thead>
      <tbody class=" grid grid-cols-1 gap-4 md:table-row-group">
        @for ($loop=0; $loop<12; $loop++)
        <tr class="block md:table-row odd:bg-white even:bg-white/50 dark:odd:bg-white/10 dark:even:bg-white/5 text-center">
          <td class="px-2 py-2 w-6/12 text-left">Prof. Mario Jimenez</td>
          <td class="px-2 py-2 w-2/12">$999.99</td>
          <td class="px-2 py-2 w-2/12 text-sm">
            <p class="">
              @if ( $loop<6 )
              <span class="inline-block rounded-2xl px-3 leading-6 bg-orange-100 text-orange-400">
                <span class="ico"><i class="fa-light fa-hourglass-clock"></i></span>
                Pendiente
              </span>
              @else
              <span class="inline-block rounded-2xl px-3 leading-6 bg-green-100 text-green-600">
                <span class="ico"><i class="fa-regular fa-check"></i></span>
                Pagado
              </span>
              @endif
            </p>
            <p class="">30 dic</p>
          </td>
          <td class="w-2/12">
            <div class="flex justify-center">
              <a href="{{ route('admin.pagoparaprofe', 1) }}" class="btn rounded-3xl bg-white px-3 py-1 shadow-md">detalle</a>
            </div>
          </td>
        </tr>
        @endfor
      </tbody>
    </table>
    <div class="flex justify-center my-5">
      <a href="#" class="btn rounded-3xl px-4 bg-white text-gray-500 leading-12 shadow-lg">ver más</a>
    </div>
  </section>
</x-admin.layout>