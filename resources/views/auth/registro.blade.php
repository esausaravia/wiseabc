<x-layoutreg container-class="py-12 lg:flex">
  <section class="lg:w-2/3 xl:w-3/4 mb-12 lg:mb-0">
    <h2 class="text-2xl md:text-3xl font-medium font-accent">Registro de estudiante</h2>
    <form id="frmRegStudent" name="frmRegStudent" class="form-stepped" action="{{ url('/register') }}" method="post" >
      @csrf
      <input type="hidden" name="jstimezone" value="" />
      <input type="hidden" name="jsTimezoneOffset" value="" />
      <input type="hidden" name="date_toTimeString" value="" />

      <div class="form-indicators flex my-12">
        <div class="form-indicator ">
          <span class="form-indicator-num">1</span>
          <span>Acceso</span>
        </div>
        <span class="mt-4 border-t border-gray-400 flex-grow"></span>
        <div class="form-indicator ">
          <span class="form-indicator-num">2</span>
          <span>Colocación</span>
        </div>
        <span class="mt-4 border-t border-gray-400 flex-grow"></span>
        <div class="form-indicator ">
          <span class="form-indicator-num">3</span>
          <span>Horarios</span>
        </div>
        <span class="mt-4 border-t border-gray-400 flex-grow"></span>
        <div class="form-indicator ">
          <span class="form-indicator-num">4</span>
          <span>Suscripción</span>
        </div>
      </div>

      @if ( $errors->any() )
        <div>
          <ul>
            @foreach ( $errors->all() AS $ek=>$error )
              <li>{{ $ek }} : {{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <section class="form-step grid gap-5 md:grid-cols-2">
        <div>
          <label for="ifname" class="block">Nombre <span class="text-rojo">*</span></label>
          <input type="text" id="ifname" name="fname" placeholder="Nombre *" required aria-required="true" class=" w-full border border-gray-600 dark:border-gray-400 px-2 py-[3px] rounded-[4px] leading-8 dark:text-gray-600" />
        </div>
        <div>
          <label for="ilname" class="block">Apellidos <span class="text-rojo">*</span></label>
          <input type="text" id="ilname" name="lname" placeholder="Apellido *" required aria-required="true" class="w-full border border-gray-600 dark:border-gray-400 px-2 py-[3px] rounded-[4px] leading-8 dark:text-gray-600" />
        </div>
        <div>
          <label for="iemail" class="block">Email <span class="text-rojo">*</span></label>
          <input type="email" id="iemail" name="email" placeholder="Correo electrónico *" required aria-required="true" class="w-full border border-gray-600 dark:border-gray-400 px-2 py-[3px] rounded-[4px] leading-8 dark:text-gray-600" />
        </div>
        <div>
          <label for="itel" class="block">Teléfono <span class="text-rojo">*</span></label>
          <input type="tel" id="itel" name="tel" placeholder="Teléfono *" required aria-required="true" pattern="[0-9()#&+*-=.]+" class="w-full border border-gray-600 dark:border-gray-400 px-2 py-[3px] rounded-[4px] leading-8 dark:text-gray-600" />
        </div>
        <div>
          <label for="ipwd" class="block">Contraseña <span class="text-rojo">*</span></label>
          <input type="password" id="ipwd" name="password" placeholder="Contraseña *" required aria-required="true" class="w-full border border-gray-600 dark:border-gray-400 px-2 py-[3px] rounded-[4px] leading-8 dark:text-gray-600" />
        </div>
        <div>
          <label for="ipwd2" class="block">Repetir contraseña <span class="text-rojo">*</span></label>
          <input type="password" id="ipwd2" name="password_confirmation" placeholder="Repetir contraseña *" required aria-required="true" class="w-full border border-gray-600 dark:border-gray-400 px-2 py-[3px] rounded-[4px] leading-8 dark:text-gray-600" />
        </div>
        <span></span>
        <div class="text-right">
          <button type="button" class="btn-next bg-rojo rounded-md px-5 py-1 font-medium font-accent leading-8 text-white">Continuar</button>
        </div>
      </section>

      {{-- Colocacion --}}
      <section class="form-step grid grid-cols-1 gap-5 hidden">

        <div class="lg:w-2/3 xl:w-1/2">
          <label for="" class="block">Edad <span class="text-rojo">*</span></label>
          <div class="grid grid-cols-4 gap-0 rounded-lg overflow-hidden bg-white text-gray-600 text-center leading-12 xl:leading-8">
            <label for="iedad-1" class="cursor-pointer">
              <input type="radio" name="edad" id="iedad-1" value="3" class="sr-only peer" required aria-required="true" />
              <div class="border-r border-gray-400 px-2 peer-checked:bg-rojo peer-checked:text-white">3-5 años</div>
            </label>
            <label for="iedad-2" class="cursor-pointer">
              <input type="radio" name="edad" id="iedad-2" value="5" class="sr-only peer" required aria-required="true" />
              <div class="border-r border-gray-400 px-2 peer-checked:bg-rojo peer-checked:text-white">5-12 años</div>
            </label>
            <label for="iedad-3" class="cursor-pointer">
              <input type="radio" name="edad" id="iedad-3" value="12" class="sr-only peer" required aria-required="true" />
              <div class="border-r border-gray-400 px-2 peer-checked:bg-rojo peer-checked:text-white">12-18 años</div>
            </label>
            <label for="iedad-4" class="cursor-pointer">
              <input type="radio" name="edad" id="iedad-4" value="19" class="sr-only peer" required aria-required="true" />
              <div class="px-2 peer-checked:bg-rojo peer-checked:text-white">+18 años</div>
            </label>
          </div>
          <small class="text-xs">Marque su edad dando clic.</small>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-center">
          <h4 class="mt-6 text-xl md:col-span-3 text-left">Nivel de Ingles</h4>
          <div class="">
            <label class="block">Principante</label>
            <div class="grid grid-cols-3 gap-0 rounded-lg overflow-hidden bg-white text-gray-600 leading-12 xl:leading-8">
              <label for="inivel-1" class="cursor-pointer">
                <input type="radio" name="nivel" id="inivel-1" value="1" class="sr-only peer" />
                <div class="border-r border-gray-400 px-2 peer-checked:bg-rojo peer-checked:text-white">1</div>
              </label>
              <label for="inivel-2" class="cursor-pointer">
                <input type="radio" name="nivel" id="inivel-2" value="2" class="sr-only peer" />
                <div class="border-r border-gray-400 px-2 peer-checked:bg-rojo peer-checked:text-white">2</div>
              </label>
              <label for="inivel-3" class="cursor-pointer">
                <input type="radio" name="nivel" id="inivel-3" value="3" class="sr-only peer" />
                <div class="px-2 peer-checked:bg-rojo peer-checked:text-white">3</div>
              </label>
            </div>
          </div>
          <div class="">
            <label class="block">Intermedio</label>
            <div class="grid grid-cols-3 gap-0 rounded-lg overflow-hidden bg-white text-gray-600 leading-12 xl:leading-8">
              <label for="inivel-4" class="cursor-pointer">
                <input type="radio" name="nivel" id="inivel-4" value="4" class="sr-only peer" />
                <div class="border-r border-gray-400 px-2 peer-checked:bg-rojo peer-checked:text-white">1</div>
              </label>
              <label for="inivel-5" class="cursor-pointer">
                <input type="radio" name="nivel" id="inivel-5" value="5" class="sr-only peer" />
                <div class="border-r border-gray-400 px-2 peer-checked:bg-rojo peer-checked:text-white">2</div>
              </label>
              <label for="inivel-6" class="cursor-pointer">
                <input type="radio" name="nivel" id="inivel-6" value="6" class="sr-only peer" />
                <div class="px-2 peer-checked:bg-rojo peer-checked:text-white">3</div>
              </label>
            </div>
          </div>
          <div class="">
            <label class="block">Avanzado</label>
            <div class="grid grid-cols-3 gap-0 rounded-lg overflow-hidden bg-white text-gray-600 leading-12 xl:leading-8">
              <label for="inivel-7" class="cursor-pointer">
                <input type="radio" name="nivel" id="inivel-7" value="7" class="sr-only peer" />
                <div class="border-r border-gray-400 px-2 peer-checked:bg-rojo peer-checked:text-white">1</div>
              </label>
              <label for="inivel-8" class="cursor-pointer">
                <input type="radio" name="nivel" id="inivel-8" value="8" class="sr-only peer" />
                <div class="border-r border-gray-400 px-2 peer-checked:bg-rojo peer-checked:text-white">2</div>
              </label>
              <label for="inivel-9" class="cursor-pointer">
                <input type="radio" name="nivel" id="inivel-9" value="9" class="sr-only peer" />
                <div class="px-2 peer-checked:bg-rojo peer-checked:text-white">3</div>
              </label>
            </div>

          </div>
          <small class="col-span-3 text-left">Le enviaremos una prueba de colocación, después de completar su suscripción.</small>
        </div>

        <div class="flex justify-between">
          <button type="button" class="btn-back border-2 border-gray-500 rounded-md px-5 py-1 font-medium font-accent leading-8 ">Regresar</button>
          <button type="button" class="btn-next bg-rojo rounded-md px-5 py-1 font-medium font-accent leading-8 text-white">Continuar</button>
        </div>
      </section>

      {{-- Horarios --}}
      <section class="form-step grid grid-cols-1 gap-5 hidden">
        <h4 class="text-xl">Disponibilidad de horario</h4>

        <div>
          <label for="" class="block">Zona horaria</label>
          <div class="inline-block rounded-lg border border-gray-500 bg-gray-100 text-gray-600 px-3 py-2 display-timezone"></div>
        </div>

        <p>Clic en los horarios que puede tomar clase, en cada día de la semana; no es necesario seleccionar todos los días.</p>
        <div class="grid grid-cols-4 md:grid-cols-7 gap-3 text-center leading-12 xl:leading-8">

          {{-- Lun --}}
          <div class="bg-white text-gray-600 rounded-lg overflow-hidden">
            <p>Lun.</p>
            <label for="ilun8" class="cursor-pointer">
              <input type="checkbox" name="horario[lun][]" id="ilun8" value="8" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">8:00</div>
            </label>
            <label for="ilun9" class="cursor-pointer">
              <input type="checkbox" name="horario[lun][]" id="ilun9" value="9" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">9:00</div>
            </label>
            <label for="ilun10" class="cursor-pointer">
              <input type="checkbox" name="horario[lun][]" id="ilun10" value="10" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">10:00</div>
            </label>
            <label for="ilun11" class="cursor-pointer">
              <input type="checkbox" name="horario[lun][]" id="ilun11" value="11" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">11:00</div>
            </label>
            <label for="ilun12" class="cursor-pointer">
              <input type="checkbox" name="horario[lun][]" id="ilun12" value="12" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">12:00</div>
            </label>
            <label for="ilun13" class="cursor-pointer">
              <input type="checkbox" name="horario[lun][]" id="ilun13" value="13" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">13:00</div>
            </label>
            <label for="ilun14" class="cursor-pointer">
              <input type="checkbox" name="horario[lun][]" id="ilun14" value="14" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">14:00</div>
            </label>
            <label for="ilun15" class="cursor-pointer">
              <input type="checkbox" name="horario[lun][]" id="ilun15" value="15" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">15:00</div>
            </label>
            <label for="ilun16" class="cursor-pointer">
              <input type="checkbox" name="horario[lun][]" id="ilun16" value="16" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">16:00</div>
            </label>
            <label for="ilun17" class="cursor-pointer">
              <input type="checkbox" name="horario[lun][]" id="ilun17" value="17" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">17:00</div>
            </label>
            <label for="ilun18" class="cursor-pointer">
              <input type="checkbox" name="horario[lun][]" id="ilun18" value="18" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">18:00</div>
            </label>
            <label for="ilun19" class="cursor-pointer">
              <input type="checkbox" name="horario[lun][]" id="ilun19" value="19" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">19:00</div>
            </label>
            <label for="ilun20" class="cursor-pointer">
              <input type="checkbox" name="horario[lun][]" id="ilun20" value="20" class="sr-only peer" />
              <div class="px-2 peer-checked:bg-azul peer-checked:text-white">20:00</div>
            </label>
          </div>

          {{-- Mar --}}
          <div class="bg-white text-gray-600 rounded-lg overflow-hidden">
            <p>Mar.</p>
            <label for="imar8" class="cursor-pointer">
              <input type="checkbox" name="horario[mar][]" id="imar8" value="8" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">8:00</div>
            </label>
            <label for="imar9" class="cursor-pointer">
              <input type="checkbox" name="horario[mar][]" id="imar9" value="9" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">9:00</div>
            </label>
            <label for="imar10" class="cursor-pointer">
              <input type="checkbox" name="horario[mar][]" id="imar10" value="10" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">10:00</div>
            </label>
            <label for="imar11" class="cursor-pointer">
              <input type="checkbox" name="horario[mar][]" id="imar11" value="11" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">11:00</div>
            </label>
            <label for="imar12" class="cursor-pointer">
              <input type="checkbox" name="horario[mar][]" id="imar12" value="12" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">12:00</div>
            </label>
            <label for="imar13" class="cursor-pointer">
              <input type="checkbox" name="horario[mar][]" id="imar13" value="13" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">13:00</div>
            </label>
            <label for="imar14" class="cursor-pointer">
              <input type="checkbox" name="horario[mar][]" id="imar14" value="14" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">14:00</div>
            </label>
            <label for="imar15" class="cursor-pointer">
              <input type="checkbox" name="horario[mar][]" id="imar15" value="15" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">15:00</div>
            </label>
            <label for="imar16" class="cursor-pointer">
              <input type="checkbox" name="horario[mar][]" id="imar16" value="16" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">16:00</div>
            </label>
            <label for="imar17" class="cursor-pointer">
              <input type="checkbox" name="horario[mar][]" id="imar17" value="17" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">17:00</div>
            </label>
            <label for="imar18" class="cursor-pointer">
              <input type="checkbox" name="horario[mar][]" id="imar18" value="18" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">18:00</div>
            </label>
            <label for="imar19" class="cursor-pointer">
              <input type="checkbox" name="horario[mar][]" id="imar19" value="19" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">19:00</div>
            </label>
            <label for="imar20" class="cursor-pointer">
              <input type="checkbox" name="horario[mar][]" id="imar20" value="20" class="sr-only peer" />
              <div class="px-2 peer-checked:bg-azul peer-checked:text-white">20:00</div>
            </label>
          </div>

          {{-- Mie --}}
          <div class="bg-white text-gray-600 rounded-lg overflow-hidden">
            <p>Mie.</p>
            <label for="imie8" class="cursor-pointer">
              <input type="checkbox" name="horario[mie][]" id="imie8" value="8" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">8:00</div>
            </label>
            <label for="imie9" class="cursor-pointer">
              <input type="checkbox" name="horario[mie][]" id="imie9" value="9" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">9:00</div>
            </label>
            <label for="imie10" class="cursor-pointer">
              <input type="checkbox" name="horario[mie][]" id="imie10" value="10" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">10:00</div>
            </label>
            <label for="imie11" class="cursor-pointer">
              <input type="checkbox" name="horario[mie][]" id="imie11" value="11" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">11:00</div>
            </label>
            <label for="imie12" class="cursor-pointer">
              <input type="checkbox" name="horario[mie][]" id="imie12" value="12" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">12:00</div>
            </label>
            <label for="imie13" class="cursor-pointer">
              <input type="checkbox" name="horario[mie][]" id="imie13" value="13" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">13:00</div>
            </label>
            <label for="imie14" class="cursor-pointer">
              <input type="checkbox" name="horario[mie][]" id="imie14" value="14" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">14:00</div>
            </label>
            <label for="imie15" class="cursor-pointer">
              <input type="checkbox" name="horario[mie][]" id="imie15" value="15" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">15:00</div>
            </label>
            <label for="imie16" class="cursor-pointer">
              <input type="checkbox" name="horario[mie][]" id="imie16" value="16" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">16:00</div>
            </label>
            <label for="imie17" class="cursor-pointer">
              <input type="checkbox" name="horario[mie][]" id="imie17" value="17" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">17:00</div>
            </label>
            <label for="imie18" class="cursor-pointer">
              <input type="checkbox" name="horario[mie][]" id="imie18" value="18" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">18:00</div>
            </label>
            <label for="imie19" class="cursor-pointer">
              <input type="checkbox" name="horario[mie][]" id="imie19" value="19" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">19:00</div>
            </label>
            <label for="imie20" class="cursor-pointer">
              <input type="checkbox" name="horario[mie][]" id="imie20" value="20" class="sr-only peer" />
              <div class="px-2 peer-checked:bg-azul peer-checked:text-white">20:00</div>
            </label>
          </div>

          {{-- Jue --}}
          <div class="bg-white text-gray-600 rounded-lg overflow-hidden">
            <p>Jue.</p>
            <label for="ijue8" class="cursor-pointer">
              <input type="checkbox" name="horario[jue][]" id="ijue8" value="8" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">8:00</div>
            </label>
            <label for="ijue9" class="cursor-pointer">
              <input type="checkbox" name="horario[jue][]" id="ijue9" value="9" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">9:00</div>
            </label>
            <label for="ijue10" class="cursor-pointer">
              <input type="checkbox" name="horario[jue][]" id="ijue10" value="10" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">10:00</div>
            </label>
            <label for="ijue11" class="cursor-pointer">
              <input type="checkbox" name="horario[jue][]" id="ijue11" value="11" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">11:00</div>
            </label>
            <label for="ijue12" class="cursor-pointer">
              <input type="checkbox" name="horario[jue][]" id="ijue12" value="12" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">12:00</div>
            </label>
            <label for="ijue13" class="cursor-pointer">
              <input type="checkbox" name="horario[jue][]" id="ijue13" value="13" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">13:00</div>
            </label>
            <label for="ijue14" class="cursor-pointer">
              <input type="checkbox" name="horario[jue][]" id="ijue14" value="14" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">14:00</div>
            </label>
            <label for="ijue15" class="cursor-pointer">
              <input type="checkbox" name="horario[jue][]" id="ijue15" value="15" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">15:00</div>
            </label>
            <label for="ijue16" class="cursor-pointer">
              <input type="checkbox" name="horario[jue][]" id="ijue16" value="16" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">16:00</div>
            </label>
            <label for="ijue17" class="cursor-pointer">
              <input type="checkbox" name="horario[jue][]" id="ijue17" value="17" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">17:00</div>
            </label>
            <label for="ijue18" class="cursor-pointer">
              <input type="checkbox" name="horario[jue][]" id="ijue18" value="18" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">18:00</div>
            </label>
            <label for="ijue19" class="cursor-pointer">
              <input type="checkbox" name="horario[jue][]" id="ijue19" value="19" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">19:00</div>
            </label>
            <label for="ijue20" class="cursor-pointer">
              <input type="checkbox" name="horario[jue][]" id="ijue20" value="20" class="sr-only peer" />
              <div class="px-2 peer-checked:bg-azul peer-checked:text-white">20:00</div>
            </label>
          </div>

          {{-- Vie --}}
          <div class="bg-white text-gray-600 rounded-lg overflow-hidden">
            <p>Vie.</p>
            <label for="ivie8" class="cursor-pointer">
              <input type="checkbox" name="horario[vie][]" id="ivie8" value="8" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">8:00</div>
            </label>
            <label for="ivie9" class="cursor-pointer">
              <input type="checkbox" name="horario[vie][]" id="ivie9" value="9" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">9:00</div>
            </label>
            <label for="ivie10" class="cursor-pointer">
              <input type="checkbox" name="horario[vie][]" id="ivie10" value="10" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">10:00</div>
            </label>
            <label for="ivie11" class="cursor-pointer">
              <input type="checkbox" name="horario[vie][]" id="ivie11" value="11" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">11:00</div>
            </label>
            <label for="ivie12" class="cursor-pointer">
              <input type="checkbox" name="horario[vie][]" id="ivie12" value="12" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">12:00</div>
            </label>
            <label for="ivie13" class="cursor-pointer">
              <input type="checkbox" name="horario[vie][]" id="ivie13" value="13" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">13:00</div>
            </label>
            <label for="ivie14" class="cursor-pointer">
              <input type="checkbox" name="horario[vie][]" id="ivie14" value="14" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">14:00</div>
            </label>
            <label for="ivie15" class="cursor-pointer">
              <input type="checkbox" name="horario[vie][]" id="ivie15" value="15" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">15:00</div>
            </label>
            <label for="ivie16" class="cursor-pointer">
              <input type="checkbox" name="horario[vie][]" id="ivie16" value="16" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">16:00</div>
            </label>
            <label for="ivie17" class="cursor-pointer">
              <input type="checkbox" name="horario[vie][]" id="ivie17" value="17" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">17:00</div>
            </label>
            <label for="ivie18" class="cursor-pointer">
              <input type="checkbox" name="horario[vie][]" id="ivie18" value="18" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">18:00</div>
            </label>
            <label for="ivie19" class="cursor-pointer">
              <input type="checkbox" name="horario[vie][]" id="ivie19" value="19" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">19:00</div>
            </label>
            <label for="ivie20" class="cursor-pointer">
              <input type="checkbox" name="horario[vie][]" id="ivie20" value="20" class="sr-only peer" />
              <div class="px-2 peer-checked:bg-azul peer-checked:text-white">20:00</div>
            </label>
          </div>

          {{-- Sab --}}
          <div class="bg-white text-gray-600 rounded-lg overflow-hidden">
            <p>Sab.</p>
            <label for="isab8" class="cursor-pointer">
              <input type="checkbox" name="horario[sab][]" id="isab8" value="8" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">8:00</div>
            </label>
            <label for="isab9" class="cursor-pointer">
              <input type="checkbox" name="horario[sab][]" id="isab9" value="9" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">9:00</div>
            </label>
            <label for="isab10" class="cursor-pointer">
              <input type="checkbox" name="horario[sab][]" id="isab10" value="10" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">10:00</div>
            </label>
            <label for="isab11" class="cursor-pointer">
              <input type="checkbox" name="horario[sab][]" id="isab11" value="11" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">11:00</div>
            </label>
            <label for="isab12" class="cursor-pointer">
              <input type="checkbox" name="horario[sab][]" id="isab12" value="12" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">12:00</div>
            </label>
            <label for="isab13" class="cursor-pointer">
              <input type="checkbox" name="horario[sab][]" id="isab13" value="13" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">13:00</div>
            </label>
            <label for="isab14" class="cursor-pointer">
              <input type="checkbox" name="horario[sab][]" id="isab14" value="14" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">14:00</div>
            </label>
            <label for="isab15" class="cursor-pointer">
              <input type="checkbox" name="horario[sab][]" id="isab15" value="15" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">15:00</div>
            </label>
            <label for="isab16" class="cursor-pointer">
              <input type="checkbox" name="horario[sab][]" id="isab16" value="16" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">16:00</div>
            </label>
            <label for="isab17" class="cursor-pointer">
              <input type="checkbox" name="horario[sab][]" id="isab17" value="17" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">17:00</div>
            </label>
            <label for="isab18" class="cursor-pointer">
              <input type="checkbox" name="horario[sab][]" id="isab18" value="18" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">18:00</div>
            </label>
            <label for="isab19" class="cursor-pointer">
              <input type="checkbox" name="horario[sab][]" id="isab19" value="19" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">19:00</div>
            </label>
            <label for="isab20" class="cursor-pointer">
              <input type="checkbox" name="horario[sab][]" id="isab20" value="20" class="sr-only peer" />
              <div class="px-2 peer-checked:bg-azul peer-checked:text-white">20:00</div>
            </label>
          </div>

          {{-- Dom --}}
          <div class="bg-white text-gray-600 rounded-lg overflow-hidden">
            <p>Dom.</p>
            <label for="idom8" class="cursor-pointer">
              <input type="checkbox" name="horario[dom][]" id="idom8" value="8" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">8:00</div>
            </label>
            <label for="idom9" class="cursor-pointer">
              <input type="checkbox" name="horario[dom][]" id="idom9" value="9" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">9:00</div>
            </label>
            <label for="idom10" class="cursor-pointer">
              <input type="checkbox" name="horario[dom][]" id="idom10" value="10" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">10:00</div>
            </label>
            <label for="idom11" class="cursor-pointer">
              <input type="checkbox" name="horario[dom][]" id="idom11" value="11" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">11:00</div>
            </label>
            <label for="idom12" class="cursor-pointer">
              <input type="checkbox" name="horario[dom][]" id="idom12" value="12" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">12:00</div>
            </label>
            <label for="idom13" class="cursor-pointer">
              <input type="checkbox" name="horario[dom][]" id="idom13" value="13" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">13:00</div>
            </label>
            <label for="idom14" class="cursor-pointer">
              <input type="checkbox" name="horario[dom][]" id="idom14" value="14" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">14:00</div>
            </label>
            <label for="idom15" class="cursor-pointer">
              <input type="checkbox" name="horario[dom][]" id="idom15" value="15" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">15:00</div>
            </label>
            <label for="idom16" class="cursor-pointer">
              <input type="checkbox" name="horario[dom][]" id="idom16" value="16" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">16:00</div>
            </label>
            <label for="idom17" class="cursor-pointer">
              <input type="checkbox" name="horario[dom][]" id="idom17" value="17" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">17:00</div>
            </label>
            <label for="idom18" class="cursor-pointer">
              <input type="checkbox" name="horario[dom][]" id="idom18" value="18" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">18:00</div>
            </label>
            <label for="idom19" class="cursor-pointer">
              <input type="checkbox" name="horario[dom][]" id="idom19" value="19" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azul peer-checked:text-white">19:00</div>
            </label>
            <label for="idom20" class="cursor-pointer">
              <input type="checkbox" name="horario[dom][]" id="idom20" value="20" class="sr-only peer" />
              <div class="px-2 peer-checked:bg-azul peer-checked:text-white">20:00</div>
            </label>
          </div>

        </div>

        <div class="md:flex justify-between">
          <button type="button" class="btn-back rounded-md px-5 py-1 font-medium font-accent leading-8 border-2 border-gray-500">Regresar</button>
          <button type="Submit" class="bg-rojo rounded-md px-5 py-1 font-medium font-accent leading-8 text-white">Continuar</button>
        </div>
      </section>
    </form>
  </section>

  <section class="md:w-1/2 lg:ml-5 lg:w-1/3 xl:w-1/4 grid grid-cols-1 gap-5">
    <h2 class="text-2xl font-medium font-accent mb-"><a href="{{ route('regprof') }}">Registro de profesor</a></h2>
    <a href="{{ route('regprof') }}">
      <img width="1536" height="1536"  class="lazyload w-full h-auto" alt="" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="  data-src="https://mezcalent.com/wiseabc/wp-content/uploads/2022/09/profesor-digital-1x1-1.jpg" decoding="async" data-srcset="https://mezcalent.com/wiseabc/wp-content/uploads/2022/09/profesor-digital-1x1-1.jpg 1536w, https://mezcalent.com/wiseabc/wp-content/uploads/2022/09/profesor-digital-1x1-1-420x420.jpg 420w, https://mezcalent.com/wiseabc/wp-content/uploads/2022/09/profesor-digital-1x1-1-1024x1024.jpg 1024w, https://mezcalent.com/wiseabc/wp-content/uploads/2022/09/profesor-digital-1x1-1-210x210.jpg 210w, https://mezcalent.com/wiseabc/wp-content/uploads/2022/09/profesor-digital-1x1-1-768x768.jpg 768w" data-sizes="auto" />
    </a>
    <p class="text-sm text-right">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus.</p>
    <p class="text-right">
      <a href="{{ route('regprof') }}" class="inline-block rounded-lg bg-azul p-3 font-medium font-accent text-sm text-gray-100">Profesores</a>
    </p>
  </section>
</x-layoutreg>