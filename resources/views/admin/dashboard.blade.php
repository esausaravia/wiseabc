<x-admin.layout>
  <h1 class="text-2xl mb-5">Dashboard</h1>
  <section class="grid grid-cols-1 gap-4 md:grid-cols-3 xl:gap-5 my-5">
    <div class="rounded-2xl bg-green-100 py-4 px-5 text-center text-green-900">
      <h3>Ingresos estimados</h3>
      <p class="text-[2rem] font-medium">${{$ingresosAcumulados}}</p>
      <!--<p class="text-sm">estimados a fin de mes</p>
      <p>$99,999.99</p>-->
    </div>

    <div class="rounded-2xl bg-black/10 dark:bg-white/10 py-4 px-5 text-center">
      <h3>Egresos acumulados</h3>
      <p class="text-[2rem] font-medium">${{ $egresoAcumuladoMes }}</p>
      <p class="text-sm">estimados a fin de mes</p>
      <p>${{ $egresoEstimadoMes }}</p>
    </div>

    <div class="rounded-2xl bg-black/10 dark:bg-white/10 py-4 px-5 text-center">
      <h3>Próximo corte</h3>
      <p class="text-[2rem] font-medium">{{ $finmes }}</p>
      <p class="text-sm">diferencia</p>
      <p>${{ $ingresosAcumulados - $egresoEstimadoMes }}</p>
    </div>
  </section>

  <div class="lg:grid grid-cols-2 gap-6">

    <section class="my-10">
      <h2 class="text-lg font-medium mb-5">Tenemos {{$sinClase}} estudiantes sin profesor</h2>

      @foreach( $arrCursos AS $curso )
      @php
        $arrStudents = $curso->alumnosSinClaseNums();
      @endphp
      <div class=" rounded-xl bg-white shadow p-3 mb-3">
        <h4 class="mb-3">{{$curso->name}}
          <span>[{{ $curso->alumnos_sin_clase->count() }} estudiantes]</span>
        </h4>

        <div class="md:grid grid-cols-2 gap-5 text-sm my-4">
          <div>
            <p class="text-xs font-bold uppercase">Grupo [{{ $arrStudents['grupal'] }}]</p>
            <ul class="flex -mx-2">
              @foreach( config('wiseabc.ritmo_labels') AS $rk=>$ritmo )
              <li class="px-2">
                <p>{{$ritmo}}</p>
                <span class="text-base">{{ $arrStudents['suscripciones'][$rk] }}</span>
              </li>
              @endforeach
            </ul>
          </div>

          <div>
            <p class="text-xs font-bold uppercase">Particular [{{ $arrStudents['particular'] }}]</p>
            <ul class="flex -mx-2">
              @foreach( config('wiseabc.ritmo_labels') AS $rk=>$ritmo )
              <li class="px-2">
                <p>{{$ritmo}}</p>
                <span class="text-base">{{ $arrStudents['suscripciones'][($rk+4)] }}</span>
              </li>
              @endforeach
            </ul>
          </div>
        </div>

        <div class="flex justify-center">
          <a href="{{ route('admin.classroom.createforcurso', $curso) }}" class="rounded-full border-gray-400 border-2 bg-white shadow-md px-4 font-medium text-sm leading-[44px]">ver horarios</a>
        </div>

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
          @foreach($RecibosPendientes AS $pendiente)
            @php
            $profe = \App\Models\User::find($pendiente->user_id)
            @endphp
            <tr class=" odd:bg-white even:bg-white/50 dark:odd:bg-white/10 dark:even:bg-white/5 text-center">
              <td class="px-2 py-2 text-left">{{ $profe->name }}</td>
              <td class="px-2 py-2">${{$pendiente->amount}}</td>
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
                  <a href="{{ route('admin.pagoparaprofe', 1) }}" class="btn rounded-3xl px-3 py-1 bg-white text-gray-600 text-sm font-accent font-medium shadow-md">detalle</a>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <div class="flex justify-center my-5">
        <a href="{{ route('admin.pagos') }}" class="btn rounded-3xl px-4 bg-white text-gray-500 leading-12 shadow-lg">ver más</a>
      </div>
    </section>

  </div>
</x-admin.layout>