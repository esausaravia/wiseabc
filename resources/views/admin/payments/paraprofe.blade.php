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
  <section class="my-5 rounded-full grid grid-cols-5 gap-5 px-5 py-3 text-center text-lg shadow-md bg-white dark:bg-white/10">
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
    <table class="rounded-xl bg-white dark:bg-white/10">
      <thead class="text-sm">
        <tr>
          <th class="p-2">fecha</th>
          <th class="p-2 text-left">clase</th>
          <th class="p-2 text-right">monto</th>
        </tr>
      </thead>
      <tbody>
        @for ($_loop=1; $_loop<5; $_loop++)
        <tr class="border-b last:border-b-0 border-gray-200 dark:border-gray-600">
          <td class="p-2 text-center align-top">{{$_loop}} nov</td>
          <td class="p-2">
            <p>Básico para niños, grupal, relax, #123</p>
            <ul class="pl-4 mb-3">
              <li>– Base: $5</li>
              <li>– Asistencia: $6</li>
              <li>– Lealtad: $3</li>
            </ul>
          </td>
          <td class="p-2 text-right align-top">$14</td>
        </tr>
        @endfor
      </tbody>
    </table>
  </section>

  <section class="flex justify-around font-accent font-medium text-sm leading-8">
    <span>
      <button class="rounded-full px-4 bg-white text-gray-600 shadow-md">Regresar</button>
    </span>
    <span>
      <button class="rounded-full px-4 bg-rojo text-white shadow-md" data-toggle="modal" data-target="modal-pago-form">Pagar</button>
    </span>
  </section>

  <section class="hidden">

  </section>

  <div id="modal-pago-form" class="modal fixed top-0 left-0 w-full h-full z-50 flex items-center justify-center bg-black/50 hidden">
    <form id="" action="" class="modal-content rounded-2xl bg-gray-100 text-gray-600">
      <input type="hidden" name="prof_id" value="" />
      <input type="hidden" name="receipt_id[]" value="" />
      <input type="hidden" name="receipt_id[]" value="" />
      <input type="hidden" name="receipt_id[]" value="" />
      <input type="hidden" name="receipt_id[]" value="" />
      <input type="hidden" name="receipt_id[]" value="" />
      <div class="modal-header border-b border-b-gray-300 px-3 py-2 flex justify-between">
        <h2 class="font-accent font-medium">Registrar pago</h2>
        <span class="-mt-2 -mr-2">
          <button class="modal-close w-12 xl:w-8 leading-12 xl:leading-8 text-xl" type="button">
            <i class="fa-regular fa-times"></i>
          </button>
        </span>
      </div>
      <div class="modal-body py-2 px-3">

        <div class="grid grid-cols-2 gap-3 lg:gap-4 xl:gap-5 mb-3">
          <x-forms.input label="Fecha" name="fecha" type="date" />

          <x-forms.input label="Hora" name="hora" type="time" />
        </div>

        <x-forms.input label="Método de pago" name="metodopago" />

        <x-forms.input label="Monto" name="monto" />

        <x-forms.input label="Referencia" name="referencia" />
      </div>
      <div class="modal-footer border-t border-t-gray-300 py-2 px-3 flex justify-between font-accent font-medium text-sm leading-8">
        <span>
          <button class="rounded-full px-4 bg-white shadow-md">Regresar</button>
        </span>
        <span>
          <button class="rounded-full px-4 bg-rojo text-white shadow-md">Pagar</button>
        </span>
      </div>
    </form>
  </div>

</x-admin.layout>