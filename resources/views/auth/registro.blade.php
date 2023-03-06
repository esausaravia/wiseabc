<x-layoutreg container-class="py-12 lg:flex">
  <section class="lg:w-2/3 xl:w-3/4 mb-12 lg:mb-0">
    <h2 class="text-xl md:text-2xl font-medium font-accent">Registro de estudiante</h2>
    <form id="frmRegStudent" name="frmRegStudent" class="form-stepped" action="{{ route('register') }}" method="post" >
      @csrf
      <input type="hidden" name="jstimezone" value="" />
      <input type="hidden" name="jsTimezoneOffset" value="" />
      <input type="hidden" name="date_toTimeString" value="" />

      @if ( $errors->any() )
        <div class="rounded-lg border-1 border-rose-600 bg-rose-200 text-rose-600 p-3">
          <ul>
            @foreach ( $errors->all() AS $ek=>$error )
              <li>{{ $ek }} : {{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

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
      <section class="form-step grid gap-5 md:grid-cols-2">
        <x-forms.input label="Nombre" name="fname" required></x-forms.input>
        <x-forms.input label="Apellidos" name="lname" required></x-forms.input>
        <x-forms.input label="Correo electrónico" name="email" required></x-forms.input>
        <x-forms.input label="Teléfono" name="tel" required></x-forms.input>

        <x-forms.input label="Contraseña" name="password" type="password" required></x-forms.input>
        <x-forms.input label="Confirmar contraseña" name="password_confirmation" type="password" required></x-forms.input>

        <span></span>
        <div class="text-right">
          <button type="button" class="btn-next shadow-md rounded-full px-5 py-3 leading-6 bg-rojo text-white text-sm font-medium font-accent">Continuar</button>
        </div>
      </section>

      {{-- Colocacion --}}
      <section class="form-step grid grid-cols-1 gap-5 hidden">

        <div class="lg:w-2/3 xl:w-1/2">
          <label for="" class="block">Edad <span class="text-rojo">*</span></label>
          <div class="grid grid-cols-4 gap-0 rounded-lg overflow-hidden bg-white text-gray-600 text-center leading-12 xl:leading-8">
            @php
              $arrEdades = config('wiseabc.edad_labels');
            @endphp
            @foreach ($arrEdades as $_key=>$_label )
              <label for="iedad-{{$_key}}" class="cursor-pointer">
                <input type="radio" name="edad" id="iedad-{{$_key}}" value="{{$_key}}" class="sr-only peer" required aria-required="true" />
                <div class="border-r border-gray-400 px-2 peer-checked:bg-rojo peer-checked:text-white">{{$_label}} años</div>
              </label>
            @endforeach
          </div>
          <small class="text-xs">Marque su edad dando clic.</small>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-center">
          <h4 class="mt-6 md:col-span-3 text-left text-lg">Nivel de Ingles</h4>
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

        <div class="flex justify-between font-medium font-accent text-sm">
          <button type="button" class="btn-back shadow-md rounded-full px-5 py-3 bg-white text-gray-600">Regresar</button>
          <button type="button" class="btn-next shadow-md rounded-full px-5 py-3 bg-rojo text-white">Continuar</button>
        </div>
      </section>

      {{-- Horarios --}}
      <section class="form-step grid grid-cols-1 gap-5 hidden" data-toggle-display="grid">
        <h4 class="text-lg font-accent font-medium">Disponibilidad de horario</h4>

        <div>
          <label for="" class="block">Zona horaria</label>
          <div class="inline-block rounded-lg border border-gray-500 bg-gray-100 text-gray-600 px-3 py-2 display-timezone"></div>
        </div>

        <p>Clic en los horarios que puede tomar clase.</p>

        <x-forms.option-group type="checkbox" name="horarios" :options="config('wiseabc.horarios_labels')">
          <x-slot:optcont class="grid grid-cols-4 md:grid-cols-8"></x-slot>
        </x-option-group>

        <div id="clases-disponibles" class="hidden" data-ajx="{{ route('clases.disponibles') }}">
          <h4 class="my-2 text-lg font-accent font-medium">Clases disponibles</h4>
          <p class="mb-3">En el siguiente paso podrá elegir el tipo de suscripción.</p>
          <div class="result"></div>
        </div>

        <div id="no-clases-disponibles" class="hidden">
          <h4 class="my-5 text-rose-700 text-lg font-accent font-medium">Cupo agotado</h4>
          <p class="text-lg">Todas las clases para su edad, nivel y horario se encuentran agotadas. Le sugerimos elegir otro horario.</p>
        </div>


        <input type="hidden" name="clases" required value="" >
        <div class="md:flex justify-between font-medium font-accent text-sm">
          <button type="button" class="btn-back shadow-md rounded-full px-5 py-3 bg-white text-gray-600">Regresar</button>
          <button id="btn-disponibilidad" type="button" class=" shadow-md rounded-full px-5 py-3 bg-azul text-white">Validar disponibilidad</button>
          <button type="submit" class="shadow-md rounded-full px-5 py-3 bg-rojo text-white">Continuar</button>
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