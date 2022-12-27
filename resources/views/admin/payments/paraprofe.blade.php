<x-admin.layout>
  <section class="grid grid-cols-2 mb-5">
    <div>
      <h1 class="font-accent ">Resumen de pago para profesor</h1>
      <h2 class="font-accent font-medium text-2xl">Prof. Mario Jimenez</h2>
    </div>
    <div class="flex justify-end text-center items-start">
      <div class="rounded-full px-4 py-1 bg-orange-200 text-orange-600">
        <h3>Pendiente / 30 dic</h3>
      </div>
    </div>
  </section>
  <section class="my-5 rounded-full grid grid-cols-5 gap-5 px-5 py-3 bg-white text-center text-lg shadow-md ">
    <div class="">
      <h4>Clases</h4>
      <p class="text-3xl font-serif">99/999</p>
    </div>
    <div class="">
      <h4>Base</h4>
      <p class="text-3xl font-serif">$999.99</p>
    </div>
    <div class="">
      <h4>Puntualidad</h4>
      <p class="text-3xl font-serif">$999.99</p>
    </div>
    <div class="">
      <h4>Grupales</h4>
      <p class="text-3xl font-serif">$999.99</p>
    </div>
    <div class="">
      <h4>Total</h4>
      <p class="text-3xl font-serif">$9,999.99</p>
    </div>
  </section>

  <section class="my-5 ">
    <table class="rounded-xl bg-white">
      <thead class="text-sm">
        <tr>
          <th class="px-3 pt-3 pb-1">fecha</th>
          <th class="px-3 pt-3 pb-1 text-left">clase</th>
          <th class="px-3 pt-3 pb-1 text-right">monto</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="px-3 text-center align-top">1 nov</td>
          <td class="px-3">
            <p>Básico para niños, grupal, relax, #123</p>
            <ul class="pl-4">
              <li>– Base: $5</li>
              <li>– Asistencia: $6</li>
              <li>– Lealtad: $3</li>
            </ul>
          </td>
          <td class="px-3 text-right align-top">$14</td>
        </tr>
      </tbody>
    </table>
  </section>

  <section class="flex justify-around font-accent leading-8">
    <span>
      <button class="rounded-full px-4 bg-white shadow-md">Regresar</button>
    </span>
    <span>
      <button class="rounded-full px-4 bg-rojo text-white shadow-md">Pagar</button>
    </span>
  </section>

</x-admin.layout>