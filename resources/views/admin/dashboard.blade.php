<x-admin.layout>
  <h1 class="text-2xl mb-5">Dashboard</h1>
  <section class="grid grid-cols-1 gap-4 md:grid-cols-3 xl:gap-5 my-5">
    <div class="rounded-2xl bg-green-100 py-4 px-5 text-center text-green-900">
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

  <div class="lg:grid grid-cols-2 gap-6">

    <section class="my-10">
      <h2 class="text-lg font-medium mb-5">Tenemos alumnos sin profesor</h2>

      @foreach( $arrCursos AS $curso )
      <div class=" rounded-xl bg-white shadow p-3 mb-3">
        <h4 class="font-medium">{{$curso->name}}</h4>

        <p>Grupales</p>
        @foreach( config('wiseabc.ritmo_labels') AS $ritmo )
        <ul>
          <li>{{$ritmo}}</li>
        </ul>
        @endforeach

        Individuales

      </div>
      @endforeach
    </section>

    <section class="my-10">
      <h2 class="text-lg font-medium mb-5">Pagos pendientes</h2>

      <table class="w-full">
        <thead class="text-sm">
          <tr>
            <th class="px-2 py-1 ">Profesor</th>
            <th class="px-2 py-1 w-2/12">Cantidad</th>
            <th class="px-2 py-1 ">Estatus / Fecha</th>
            <th class="px-2 py-1 w-2/12">Acciones</th>
          </tr>
        </thead>
        <tbody class="">
          @for ($loop=0; $loop<6; $loop++)
          <tr class=" odd:bg-white even:bg-white/50 dark:odd:bg-white/10 dark:even:bg-white/5 text-center">
            <td class="px-2 py-2 text-left">Prof. Mario Jimenez</td>
            <td class="px-2 py-2">$999.99</td>
            <td class="px-2 py-2 text-sm">
              <p class="">
                @if ( $loop<3 )
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
            <td class="">
              <div class="flex justify-center">
                <a href="{{ route('admin.pagoparaprofe', 1) }}" class="btn rounded-3xl px-3 py-1 bg-white text-gray-600 text-sm font-accent font-medium shadow-md">detalle</a>
              </div>
            </td>
          </tr>
          @endfor
        </tbody>
      </table>
      <div class="flex justify-center my-5">
        <a href="{{ route('admin.pagos') }}" class="btn rounded-3xl px-4 bg-white text-gray-500 leading-12 shadow-lg">ver más</a>
      </div>
    </section>

  </div>
</x-admin.layout>