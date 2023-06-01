<x-layoutreg container-class="py-12 lg:flex">
  <section class="lg:w-2/3 xl:w-3/4 mb-12 lg:mb-0">
    <h2 class="text-xl md:text-2xl font-medium font-accent">Teacher sign-up</h2>

    <div class="my-5">
      <h3 class="text-lg md:text-xl mb-2">Requirements to get hired at Wise ABC </h3>

      <ol class="list-decimal pl-5">
        <li>Must be a Native Speaker.</li>
        <li>Must have a bachelor's degree and TEFL certificate.</li>
        <li>Must have 2 years of experience teaching E.S.L.</li>
        <li>Must be energetic and enthusiastic while teaching.</li>
        <li>Must have a reliable server and good equipment to teach online. Must have a reliable server and good equipment to teach online.</li>
        <li>Must use teachers' props during the lesson.</li>
      </ol>
    </div>

    <form id="frmRegProf" name="frmRegProf" class="form-stepped" action="{{ route('regprof') }}" method="post" >
      @csrf

      <div class="form-indicators flex my-10">
        <div class="form-indicator ">
          <span class="form-indicator-num">1</span>
          <span>Acceso</span>
        </div>
        <span class="mt-4 border-t border-gray-400 flex-grow"></span>
        <div class="form-indicator ">
          <span class="form-indicator-num">3</span>
          <span>Horarios</span>
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
          <input type="text" id="ifname" name="fname" placeholder="Nombre *" required aria-required="true" class=" block w-full shadow-sm rounded-md px-3 py-2 sm:text-sm bg-white border border-slate-300 placeholder-slate-400 focus:border-sky-500 focus:ring-sky-500 focus:ring-1 invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500" value="{{ old('fname') }}" />
        </div>
        <div>
          <label for="ilname" class="block">Apellidos <span class="text-rojo">*</span></label>
          <input type="text" id="ilname" name="lname" placeholder="Apellido *" required aria-required="true" class="block w-full shadow-sm rounded-md px-3 py-2 sm:text-sm bg-white border border-slate-300 placeholder-slate-400 focus:border-sky-500 focus:ring-sky-500 focus:ring-1 invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500" value="{{ old('lname') }}" />
        </div>
        <div>
          <label for="iemail" class="block">Email <span class="text-rojo">*</span></label>
          <input type="email" id="iemail" name="email" placeholder="Correo electrónico *" required aria-required="true" class="block w-full shadow-sm rounded-md px-3 py-2 sm:text-sm bg-white border border-slate-300 placeholder-slate-400 focus:border-sky-500 focus:ring-sky-500 focus:ring-1 invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500" value="{{ old('email') }}" />
          @error('email')
            <script>document.getElementById('iemail').focus();</script>
            <p class="alert text-sm text-rose-700">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label for="itel" class="block">Teléfono <span class="text-rojo">*</span></label>
          <input type="tel" id="itel" name="tel" placeholder="Teléfono *" required aria-required="true" pattern="[0-9()#&+*-=.]+" class="block w-full shadow-sm rounded-md px-3 py-2 sm:text-sm bg-white border border-slate-300 placeholder-slate-400 focus:border-sky-500 focus:ring-sky-500 focus:ring-1 invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500" value="{{ old('tel') }}" />
        </div>
        <div>
          <label for="ipais" class="block">País <span class="text-rojo">*</span></label>
          <input type="text" id="ipais" name="pais" placeholder="País *" required aria-required="true" class=" block w-full shadow-sm rounded-md px-3 py-2 sm:text-sm bg-white border border-slate-300 placeholder-slate-400 focus:border-sky-500 focus:ring-sky-500 focus:ring-1 invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500" value="{{ old('pais') }}" />
        </div>
        <div>
          <label for="icity" class="block">Ciudad <span class="text-rojo">*</span></label>
          <input type="text" id="icity" name="city" placeholder="Ciudad *" required aria-required="true" class="block w-full shadow-sm rounded-md px-3 py-2 sm:text-sm bg-white border border-slate-300 placeholder-slate-400 focus:border-sky-500 focus:ring-sky-500 focus:ring-1 invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500" value="{{ old('city') }}" />
        </div>

        <div class="col-span-2">
          <label for="iexpe" class="block">Experiencia <span class="text-rojo">*</span></label>
          <textarea name="experiencia" id="iexpe" rows="6" required aria-required="true" class="block w-full shadow-sm rounded-md px-3 py-2 sm:text-sm bg-white border border-slate-300 placeholder-slate-400 focus:border-sky-500 focus:ring-sky-500 focus:ring-1 invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500" placeholder="Describanos su experiencia impartiendo clases de Ingles">{{ old('experiencia') }}</textarea>
        </div>

        <div class="col-span-2">
          <label for="icerts" class="block">Certificaciones <span class="text-rojo">*</span></label>
          <textarea name="certificaciones" id="icerts" rows="6" required aria-required="true" class="block w-full shadow-sm rounded-md px-3 py-2 sm:text-sm bg-white border border-slate-300 placeholder-slate-400 focus:border-sky-500 focus:ring-sky-500 focus:ring-1 invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500" placeholder="Enliste las certificaciones con las que cuenta, en materia de enseñanza y dominio del idioma.">{{ old('certificaciones') }}</textarea>
        </div>

        <span></span>
        <div class="text-right">
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
              <input type="checkbox" name="horarios[1][]" id="ilun8" value="8" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">8:00</div>
            </label>
            <label for="ilun9" class="cursor-pointer">
              <input type="checkbox" name="horarios[1][]" id="ilun9" value="9" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">9:00</div>
            </label>
            <label for="ilun10" class="cursor-pointer">
              <input type="checkbox" name="horarios[1][]" id="ilun10" value="10" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">10:00</div>
            </label>
            <label for="ilun11" class="cursor-pointer">
              <input type="checkbox" name="horarios[1][]" id="ilun11" value="11" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">11:00</div>
            </label>
            <label for="ilun12" class="cursor-pointer">
              <input type="checkbox" name="horarios[1][]" id="ilun12" value="12" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">12:00</div>
            </label>
            <label for="ilun13" class="cursor-pointer">
              <input type="checkbox" name="horarios[1][]" id="ilun13" value="13" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">13:00</div>
            </label>
            <label for="ilun14" class="cursor-pointer">
              <input type="checkbox" name="horarios[1][]" id="ilun14" value="14" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">14:00</div>
            </label>
            <label for="ilun15" class="cursor-pointer">
              <input type="checkbox" name="horarios[1][]" id="ilun15" value="15" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">15:00</div>
            </label>
            <label for="ilun16" class="cursor-pointer">
              <input type="checkbox" name="horarios[1][]" id="ilun16" value="16" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">16:00</div>
            </label>
            <label for="ilun17" class="cursor-pointer">
              <input type="checkbox" name="horarios[1][]" id="ilun17" value="17" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">17:00</div>
            </label>
            <label for="ilun18" class="cursor-pointer">
              <input type="checkbox" name="horarios[1][]" id="ilun18" value="18" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">18:00</div>
            </label>
            <label for="ilun19" class="cursor-pointer">
              <input type="checkbox" name="horarios[1][]" id="ilun19" value="19" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">19:00</div>
            </label>
            <label for="ilun20" class="cursor-pointer">
              <input type="checkbox" name="horarios[1][]" id="ilun20" value="20" class="sr-only peer" />
              <div class="px-2 peer-checked:bg-azulw peer-checked:text-white">20:00</div>
            </label>
          </div>

          {{-- Mar --}}
          <div class="bg-white text-gray-600 rounded-lg overflow-hidden">
            <p>Mar.</p>
            <label for="imar8" class="cursor-pointer">
              <input type="checkbox" name="horarios[2][]" id="imar8" value="8" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">8:00</div>
            </label>
            <label for="imar9" class="cursor-pointer">
              <input type="checkbox" name="horarios[2][]" id="imar9" value="9" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">9:00</div>
            </label>
            <label for="imar10" class="cursor-pointer">
              <input type="checkbox" name="horarios[2][]" id="imar10" value="10" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">10:00</div>
            </label>
            <label for="imar11" class="cursor-pointer">
              <input type="checkbox" name="horarios[2][]" id="imar11" value="11" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">11:00</div>
            </label>
            <label for="imar12" class="cursor-pointer">
              <input type="checkbox" name="horarios[2][]" id="imar12" value="12" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">12:00</div>
            </label>
            <label for="imar13" class="cursor-pointer">
              <input type="checkbox" name="horarios[2][]" id="imar13" value="13" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">13:00</div>
            </label>
            <label for="imar14" class="cursor-pointer">
              <input type="checkbox" name="horarios[2][]" id="imar14" value="14" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">14:00</div>
            </label>
            <label for="imar15" class="cursor-pointer">
              <input type="checkbox" name="horarios[2][]" id="imar15" value="15" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">15:00</div>
            </label>
            <label for="imar16" class="cursor-pointer">
              <input type="checkbox" name="horarios[2][]" id="imar16" value="16" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">16:00</div>
            </label>
            <label for="imar17" class="cursor-pointer">
              <input type="checkbox" name="horarios[2][]" id="imar17" value="17" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">17:00</div>
            </label>
            <label for="imar18" class="cursor-pointer">
              <input type="checkbox" name="horarios[2][]" id="imar18" value="18" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">18:00</div>
            </label>
            <label for="imar19" class="cursor-pointer">
              <input type="checkbox" name="horarios[2][]" id="imar19" value="19" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">19:00</div>
            </label>
            <label for="imar20" class="cursor-pointer">
              <input type="checkbox" name="horarios[2][]" id="imar20" value="20" class="sr-only peer" />
              <div class="px-2 peer-checked:bg-azulw peer-checked:text-white">20:00</div>
            </label>
          </div>

          {{-- Mie --}}
          <div class="bg-white text-gray-600 rounded-lg overflow-hidden">
            <p>Mie.</p>
            <label for="imie8" class="cursor-pointer">
              <input type="checkbox" name="horarios[3][]" id="imie8" value="8" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">8:00</div>
            </label>
            <label for="imie9" class="cursor-pointer">
              <input type="checkbox" name="horarios[3][]" id="imie9" value="9" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">9:00</div>
            </label>
            <label for="imie10" class="cursor-pointer">
              <input type="checkbox" name="horarios[3][]" id="imie10" value="10" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">10:00</div>
            </label>
            <label for="imie11" class="cursor-pointer">
              <input type="checkbox" name="horarios[3][]" id="imie11" value="11" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">11:00</div>
            </label>
            <label for="imie12" class="cursor-pointer">
              <input type="checkbox" name="horarios[3][]" id="imie12" value="12" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">12:00</div>
            </label>
            <label for="imie13" class="cursor-pointer">
              <input type="checkbox" name="horarios[3][]" id="imie13" value="13" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">13:00</div>
            </label>
            <label for="imie14" class="cursor-pointer">
              <input type="checkbox" name="horarios[3][]" id="imie14" value="14" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">14:00</div>
            </label>
            <label for="imie15" class="cursor-pointer">
              <input type="checkbox" name="horarios[3][]" id="imie15" value="15" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">15:00</div>
            </label>
            <label for="imie16" class="cursor-pointer">
              <input type="checkbox" name="horarios[3][]" id="imie16" value="16" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">16:00</div>
            </label>
            <label for="imie17" class="cursor-pointer">
              <input type="checkbox" name="horarios[3][]" id="imie17" value="17" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">17:00</div>
            </label>
            <label for="imie18" class="cursor-pointer">
              <input type="checkbox" name="horarios[3][]" id="imie18" value="18" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">18:00</div>
            </label>
            <label for="imie19" class="cursor-pointer">
              <input type="checkbox" name="horarios[3][]" id="imie19" value="19" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">19:00</div>
            </label>
            <label for="imie20" class="cursor-pointer">
              <input type="checkbox" name="horarios[3][]" id="imie20" value="20" class="sr-only peer" />
              <div class="px-2 peer-checked:bg-azulw peer-checked:text-white">20:00</div>
            </label>
          </div>

          {{-- Jue --}}
          <div class="bg-white text-gray-600 rounded-lg overflow-hidden">
            <p>Jue.</p>
            <label for="ijue8" class="cursor-pointer">
              <input type="checkbox" name="horarios[4][]" id="ijue8" value="8" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">8:00</div>
            </label>
            <label for="ijue9" class="cursor-pointer">
              <input type="checkbox" name="horarios[4][]" id="ijue9" value="9" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">9:00</div>
            </label>
            <label for="ijue10" class="cursor-pointer">
              <input type="checkbox" name="horarios[4][]" id="ijue10" value="10" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">10:00</div>
            </label>
            <label for="ijue11" class="cursor-pointer">
              <input type="checkbox" name="horarios[4][]" id="ijue11" value="11" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">11:00</div>
            </label>
            <label for="ijue12" class="cursor-pointer">
              <input type="checkbox" name="horarios[4][]" id="ijue12" value="12" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">12:00</div>
            </label>
            <label for="ijue13" class="cursor-pointer">
              <input type="checkbox" name="horarios[4][]" id="ijue13" value="13" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">13:00</div>
            </label>
            <label for="ijue14" class="cursor-pointer">
              <input type="checkbox" name="horarios[4][]" id="ijue14" value="14" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">14:00</div>
            </label>
            <label for="ijue15" class="cursor-pointer">
              <input type="checkbox" name="horarios[4][]" id="ijue15" value="15" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">15:00</div>
            </label>
            <label for="ijue16" class="cursor-pointer">
              <input type="checkbox" name="horarios[4][]" id="ijue16" value="16" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">16:00</div>
            </label>
            <label for="ijue17" class="cursor-pointer">
              <input type="checkbox" name="horarios[4][]" id="ijue17" value="17" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">17:00</div>
            </label>
            <label for="ijue18" class="cursor-pointer">
              <input type="checkbox" name="horarios[4][]" id="ijue18" value="18" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">18:00</div>
            </label>
            <label for="ijue19" class="cursor-pointer">
              <input type="checkbox" name="horarios[4][]" id="ijue19" value="19" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">19:00</div>
            </label>
            <label for="ijue20" class="cursor-pointer">
              <input type="checkbox" name="horarios[4][]" id="ijue20" value="20" class="sr-only peer" />
              <div class="px-2 peer-checked:bg-azulw peer-checked:text-white">20:00</div>
            </label>
          </div>

          {{-- Vie --}}
          <div class="bg-white text-gray-600 rounded-lg overflow-hidden">
            <p>Vie.</p>
            <label for="ivie8" class="cursor-pointer">
              <input type="checkbox" name="horarios[5][]" id="ivie8" value="8" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">8:00</div>
            </label>
            <label for="ivie9" class="cursor-pointer">
              <input type="checkbox" name="horarios[5][]" id="ivie9" value="9" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">9:00</div>
            </label>
            <label for="ivie10" class="cursor-pointer">
              <input type="checkbox" name="horarios[5][]" id="ivie10" value="10" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">10:00</div>
            </label>
            <label for="ivie11" class="cursor-pointer">
              <input type="checkbox" name="horarios[5][]" id="ivie11" value="11" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">11:00</div>
            </label>
            <label for="ivie12" class="cursor-pointer">
              <input type="checkbox" name="horarios[5][]" id="ivie12" value="12" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">12:00</div>
            </label>
            <label for="ivie13" class="cursor-pointer">
              <input type="checkbox" name="horarios[5][]" id="ivie13" value="13" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">13:00</div>
            </label>
            <label for="ivie14" class="cursor-pointer">
              <input type="checkbox" name="horarios[5][]" id="ivie14" value="14" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">14:00</div>
            </label>
            <label for="ivie15" class="cursor-pointer">
              <input type="checkbox" name="horarios[5][]" id="ivie15" value="15" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">15:00</div>
            </label>
            <label for="ivie16" class="cursor-pointer">
              <input type="checkbox" name="horarios[5][]" id="ivie16" value="16" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">16:00</div>
            </label>
            <label for="ivie17" class="cursor-pointer">
              <input type="checkbox" name="horarios[5][]" id="ivie17" value="17" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">17:00</div>
            </label>
            <label for="ivie18" class="cursor-pointer">
              <input type="checkbox" name="horarios[5][]" id="ivie18" value="18" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">18:00</div>
            </label>
            <label for="ivie19" class="cursor-pointer">
              <input type="checkbox" name="horarios[5][]" id="ivie19" value="19" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">19:00</div>
            </label>
            <label for="ivie20" class="cursor-pointer">
              <input type="checkbox" name="horarios[5][]" id="ivie20" value="20" class="sr-only peer" />
              <div class="px-2 peer-checked:bg-azulw peer-checked:text-white">20:00</div>
            </label>
          </div>

          {{-- Sab --}}
          <div class="bg-white text-gray-600 rounded-lg overflow-hidden">
            <p>Sab.</p>
            <label for="isab8" class="cursor-pointer">
              <input type="checkbox" name="horarios[6][]" id="isab8" value="8" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">8:00</div>
            </label>
            <label for="isab9" class="cursor-pointer">
              <input type="checkbox" name="horarios[6][]" id="isab9" value="9" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">9:00</div>
            </label>
            <label for="isab10" class="cursor-pointer">
              <input type="checkbox" name="horarios[6][]" id="isab10" value="10" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">10:00</div>
            </label>
            <label for="isab11" class="cursor-pointer">
              <input type="checkbox" name="horarios[6][]" id="isab11" value="11" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">11:00</div>
            </label>
            <label for="isab12" class="cursor-pointer">
              <input type="checkbox" name="horarios[6][]" id="isab12" value="12" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">12:00</div>
            </label>
            <label for="isab13" class="cursor-pointer">
              <input type="checkbox" name="horarios[6][]" id="isab13" value="13" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">13:00</div>
            </label>
            <label for="isab14" class="cursor-pointer">
              <input type="checkbox" name="horarios[6][]" id="isab14" value="14" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">14:00</div>
            </label>
            <label for="isab15" class="cursor-pointer">
              <input type="checkbox" name="horarios[6][]" id="isab15" value="15" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">15:00</div>
            </label>
            <label for="isab16" class="cursor-pointer">
              <input type="checkbox" name="horarios[6][]" id="isab16" value="16" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">16:00</div>
            </label>
            <label for="isab17" class="cursor-pointer">
              <input type="checkbox" name="horarios[6][]" id="isab17" value="17" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">17:00</div>
            </label>
            <label for="isab18" class="cursor-pointer">
              <input type="checkbox" name="horarios[6][]" id="isab18" value="18" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">18:00</div>
            </label>
            <label for="isab19" class="cursor-pointer">
              <input type="checkbox" name="horarios[6][]" id="isab19" value="19" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">19:00</div>
            </label>
            <label for="isab20" class="cursor-pointer">
              <input type="checkbox" name="horarios[6][]" id="isab20" value="20" class="sr-only peer" />
              <div class="px-2 peer-checked:bg-azulw peer-checked:text-white">20:00</div>
            </label>
          </div>

          {{-- Dom --}}
          <div class="bg-white text-gray-600 rounded-lg overflow-hidden">
            <p>Dom.</p>
            <label for="idom8" class="cursor-pointer">
              <input type="checkbox" name="horarios[7][]" id="idom8" value="8" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">8:00</div>
            </label>
            <label for="idom9" class="cursor-pointer">
              <input type="checkbox" name="horarios[7][]" id="idom9" value="9" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">9:00</div>
            </label>
            <label for="idom10" class="cursor-pointer">
              <input type="checkbox" name="horarios[7][]" id="idom10" value="10" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">10:00</div>
            </label>
            <label for="idom11" class="cursor-pointer">
              <input type="checkbox" name="horarios[7][]" id="idom11" value="11" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">11:00</div>
            </label>
            <label for="idom12" class="cursor-pointer">
              <input type="checkbox" name="horarios[7][]" id="idom12" value="12" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">12:00</div>
            </label>
            <label for="idom13" class="cursor-pointer">
              <input type="checkbox" name="horarios[7][]" id="idom13" value="13" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">13:00</div>
            </label>
            <label for="idom14" class="cursor-pointer">
              <input type="checkbox" name="horarios[7][]" id="idom14" value="14" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">14:00</div>
            </label>
            <label for="idom15" class="cursor-pointer">
              <input type="checkbox" name="horarios[7][]" id="idom15" value="15" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">15:00</div>
            </label>
            <label for="idom16" class="cursor-pointer">
              <input type="checkbox" name="horarios[7][]" id="idom16" value="16" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">16:00</div>
            </label>
            <label for="idom17" class="cursor-pointer">
              <input type="checkbox" name="horarios[7][]" id="idom17" value="17" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">17:00</div>
            </label>
            <label for="idom18" class="cursor-pointer">
              <input type="checkbox" name="horarios[7][]" id="idom18" value="18" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">18:00</div>
            </label>
            <label for="idom19" class="cursor-pointer">
              <input type="checkbox" name="horarios[7][]" id="idom19" value="19" class="sr-only peer" />
              <div class="border-b border-gray-400 px-2 peer-checked:bg-azulw peer-checked:text-white">19:00</div>
            </label>
            <label for="idom20" class="cursor-pointer">
              <input type="checkbox" name="horarios[7][]" id="idom20" value="20" class="sr-only peer" />
              <div class="px-2 peer-checked:bg-azulw peer-checked:text-white">20:00</div>
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

  <section class="md:w-1/2 lg:ml-5 lg:w-1/3 xl:w-1/4">
    <div class="grid grid-cols-1 gap-5">
      <h2 class="text-lg md:text-xl font-medium font-accent"><a href="{{ route('register') }}">Student sign-up</a></h2>
      <a href="{{ route('register') }}">

        <img width="2048" height="2048"  class="lazyload w-full h-auto" alt="" srcset="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="  data-src="https://wiseabcenglish.com/wp-content/uploads/2022/09/estudiante-digital-1x1-1.jpg" decoding="async" data-srcset="https://wiseabcenglish.com/wp-content/uploads/2022/09/estudiante-digital-1x1-1.jpg 2048w, https://wiseabcenglish.com/wp-content/uploads/2022/09/estudiante-digital-1x1-1-420x420.jpg 420w, https://wiseabcenglish.com/wp-content/uploads/2022/09/estudiante-digital-1x1-1-1024x1024.jpg 1024w, https://wiseabcenglish.com/wp-content/uploads/2022/09/estudiante-digital-1x1-1-210x210.jpg 210w, https://wiseabcenglish.com/wp-content/uploads/2022/09/estudiante-digital-1x1-1-768x768.jpg 768w, https://wiseabcenglish.com/wp-content/uploads/2022/09/estudiante-digital-1x1-1-1536x1536.jpg 1536w" data-sizes="auto" />
      </a>

      </a>
      <p class="text-sm text-right">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus.</p>
      <p class="text-right">
        <a href="{{ route('register') }}" class="inline-block rounded-lg bg-azulw p-3 font-accent text-sm text-gray-100">Estudiante</a>
      </p>
    </div>

  </section>
</x-layoutreg>