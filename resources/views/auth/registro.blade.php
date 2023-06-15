<x-layoutreg container-class="py-12">

  <div class="flex justify-between">
    <h2 class="text-xl md:text-2xl font-medium font-accent">Registro de estudiante</h2>
    <h2 class="hidden lg:block text-xl font-medium font-accent mb-3"><a href="{{ route('regprof') }}">Teacher sign-up</a></h2>
  </div>

  <section class="max-w- mx-auto">

    <form id="frmRegStudent" name="frmRegStudent" class="form-stepped" action="{{ route('register') }}" method="post" >
      @csrf

      @if ( $errors->any() )
        <div class="rounded-lg border-1 border-rose-600 bg-rose-200 text-rose-600 p-3">
          <ul>
            @foreach ( $errors->all() AS $ek=>$error )
              <li data-error-key="{{ $ek }}">{{ $error }}</li>
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
        <x-forms.input label="Teléfono" name="user_phone" type="tel" required></x-forms.input>
        <input type="hidden" name="country" value="" />

        <x-forms.input label="{{__('Password')}}" name="password" type="password" required></x-forms.input>
        <x-forms.input label="{{__('Confirm Password')}}" name="password_confirmation" type="password" required></x-forms.input>

        <span></span>
        <div class="text-right">
          <button type="button" class="btn-next shadow-md rounded-full px-5 py-3 leading-6 bg-rojo text-white text-sm font-medium font-accent" onclick="checkCountrySubscriptions()">Continuar</button>
        </div>
      </section>

      {{-- Colocacion --}}
      <section class="form-step hidden">

        <div class="lg:w-2/3 xl:w-1/2 mb-5">
          <x-forms.option-group label="Edad" name="edad" :options="config('wiseabc.edad_labels')" required helper="Marque su edad dando clic."></x-option-group>
        </div>

        <h4 class="md:col-span-3 text-left text-lg after:content-['*'] after:text-rose-700 after:pl-1 ">Nivel de Ingles</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-center">

          <div class="">
            <label class="block">Principante</label>
            <div class="grid grid-cols-3 rounded-lg shadow-md bg-white text-gray-500 leading-12 xl:leading-8">
              <div class="border-r border-black/5">
                <input class="peer sr-only" type="radio" name="nivel" id="inivel1" value="1" required="">
                <label for="inivel1" class="cursor-pointer block px-3 peer-checked:font-bold peer-checked:text-rojo">
                  1
                </label>
              </div>
              <div class="border-r border-black/5">
                <input class="peer sr-only" type="radio" name="nivel" id="inivel2" value="2" required="">
                <label for="inivel2" class="cursor-pointer block px-3 peer-checked:font-bold peer-checked:text-rojo">
                  2
                </label>
              </div>
              <div class="border-r border-black/5">
                <input class="peer sr-only" type="radio" name="nivel" id="inivel3" value="3" required="">
                <label for="inivel3" class="cursor-pointer block px-3 peer-checked:font-bold peer-checked:text-rojo">
                  3
                </label>
              </div>
            </div>
          </div>

          <div class="">
            <label class="block">Intermedio</label>
            <div class="grid grid-cols-3 rounded-lg shadow-md bg-white text-gray-500 leading-12 xl:leading-8">
              <div class="border-r border-black/5">
                <input class="peer sr-only" type="radio" name="nivel" id="inivel4" value="4" required="">
                <label for="inivel4" class="cursor-pointer block px-3 peer-checked:font-bold peer-checked:text-rojo">
                  4
                </label>
              </div>
              <div class="border-r border-black/5">
                <input class="peer sr-only" type="radio" name="nivel" id="inivel5" value="5" required="">
                <label for="inivel5" class="cursor-pointer block px-3 peer-checked:font-bold peer-checked:text-rojo">
                  5
                </label>
              </div>
              <div class="border-r border-black/5">
                <input class="peer sr-only" type="radio" name="nivel" id="inivel6" value="6" required="">
                <label for="inivel6" class="cursor-pointer block px-3 peer-checked:font-bold peer-checked:text-rojo">
                  6
                </label>
              </div>
            </div>
          </div>

          <div class="">
            <label class="block">Avanzado</label>
            <div class="grid grid-cols-3 rounded-lg shadow-md bg-white text-gray-500 leading-12 xl:leading-8">
              <div class="border-r border-black/5">
                <input class="peer sr-only" type="radio" name="nivel" id="inivel7" value="7" required="">
                <label for="inivel7" class="cursor-pointer block px-3 peer-checked:font-bold peer-checked:text-rojo">
                  7
                </label>
              </div>
              <div class="border-r border-black/5">
                <input class="peer sr-only" type="radio" name="nivel" id="inivel8" value="8" required="">
                <label for="inivel8" class="cursor-pointer block px-3 peer-checked:font-bold peer-checked:text-rojo">
                  8
                </label>
              </div>
              <div class="border-r border-black/5">
                <input class="peer sr-only" type="radio" name="nivel" id="inivel9" value="9" required="">
                <label for="inivel9" class="cursor-pointer block px-3 peer-checked:font-bold peer-checked:text-rojo">
                  9
                </label>
              </div>
            </div>
          </div>
        </div>
        <small class="block mb-8">Le enviaremos una prueba de colocación, después de completar su suscripción.</small>

        <div class="flex justify-between font-medium font-accent text-sm">
          <button type="button" class="btn-back shadow-md rounded-full px-5 py-3 bg-white text-gray-600">Regresar</button>
          <button type="button" class="btn-next shadow-md rounded-full px-5 py-3 bg-rojo text-white">Continuar</button>
        </div>
      </section>

      {{-- Horarios --}}
      <section class="form-step ">
        <div class="grid grid-cols-1 gap-5">
          <h4 class="text-lg font-accent font-medium">Disponibilidad de horario</h4>

          <div>
            <label for="" class="block">Zona horaria</label>
            <div class="inline-block rounded-lg border border-gray-500 bg-gray-100 text-gray-600 px-3 py-2 display-timezone"></div>
          </div>

          <div>
            <p>Clic en todos los horarios que puede tomar clase.</p>

            <x-forms.option-group type="checkbox" name="horarios" :options="$horarios">
              <x-slot:optcont class="rounded-lg shadow-md grid grid-cols-4 md:grid-cols-8 bg-white text-gray-500 leading-12 xl:leading-8 text-center"></x-slot>
            </x-option-group>
            <small class="block">Nosotros revisaremos disponibilidad de profesor para los días (de lunes a domingo).</small>
          </div>

          <div id="clases-disponibles" class="hidden" data-ajx="{{ route('clases.disponibles') }}">
            <h4 class="my-2 text-lg font-accent font-medium">Clases disponibles</h4>
            <div class="result"></div>
          </div>

          <div id="no-clases-disponibles" class="hidden">
            <h4 class="my-5 text-rose-700 text-lg font-accent font-medium">Cupo agotado</h4>
            <p class="text-lg">Todas las clases para su edad, nivel y horario se encuentran agotadas. Le sugerimos elegir otro horario.</p>
          </div>


          <input type="hidden" name="clases" required value="" >
          <div class="md:flex justify-between font-medium font-accent text-sm">
            <button type="button" class="btn-back shadow-md rounded-full px-5 py-3 bg-white text-gray-600">Regresar</button>
            <button id="btn-disponibilidad" type="button" class=" shadow-md rounded-full px-5 py-3 bg-azulw text-white">Validar disponibilidad</button>
            <button type="button" class="btn-next shadow-md rounded-full px-5 py-3 bg-rojo text-white">Continuar</button>
            {{--}}<button type="submit" class="shadow-md rounded-full px-5 py-3 bg-rojo text-white">Submit</button>{{--}}
          </div>
        </div>
      </section>

      <section class="form-step hidden text-center">
        <h1 class="text-2xl md:text-3xl font-bold text-azul-600 my-6">Elige tu subscripción para finalizar tu registro</h1>

        <p class="max-w-[600px] mx-auto mb-8 text-center">
          Puedes cambiarla más adelante
        </p>
        <input type="hidden" name="clase_tipo" value="" />
        <input type="hidden" name="ritmo" value="" />

        <div id="SubscriptionPricesMX" class="tabs-widget overflow-hidden" data-active-class="" >
          <div class="tabs-wrapper font-accent font-medium text-xl text-azul-600 flex justify-center" role="tablist">
            <div id="tab-title-mx1" data-tab="1" role="tab" aria-controls="tab-content-mx1" aria-expanded="true" aria-selected="true" class="tab active cursor-pointer py-10px px-3 border-l border-r border-t border-black/20 dark:border-white/20 text-rojo relative after:content-[''] after:absolute after:left-full after:bottom-0 after:w-[50vw] after:h-0 after:border-t after:border-black/20 after:dark:border-white/20 before:content-[''] before:absolute before:right-full before:bottom-0 before:w-[50vw] before:h-0 before:border-t before:border-black/20 before:dark:border-white/20"> {{__('Grupal')}} </div>

            <div id="tab-title-mx2" class="tab cursor-pointer py-10px px-3 dark:text-gray-200" data-tab="2" role="tab" aria-controls="tab-content-mx2" aria-expanded="false" aria-selected="false"> {{__('Individuales')}} </div>
          </div>
          <div class="tabs-content">
            <div id="tab-content-mx1" class="tabpanel active border-l border-r border-b border-black/20 dark:border-white/20 p-5" data-tab="1" role="tabpanel" aria-labelledby="tab-title-mx1" aria-expanded="true" aria-selected="true" >
              <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-5 lg:gap-6 xl:gap-7">

                <article class="rounded-2xl shadow-lg bg-white text-gray-600 py-5">

                  <h3 class="text-2xl font-accent font-medium">Relax</h3>
                  <p class="font-bold text-base">4 clases</p>
                  <div class="my-3">
                    <div class="flex items-top justify-center font-accent">
                      <span class="self-end line-through ">$36</span>
                      <span class="font-bold">$</span>
                      <span class="font-bold text-5xl">28</span>
                      <span class="font-bold">.80</span>
                    </div>
                    <p>al mes</p>
                  </div>
                  <ul class="text-left mx-5 pl-5 list-disc text-sm">
                    <li class="text-base">$7.2 usd por clase</li>
                    <li>1 clase por semana</li>
                    <li>Clase grupal de 40 min.</li>
                    <li>Grupo de hasta 3 estudiantes</li>
                  </ul>
                  <div class="mt-7">
                    <button type="button" data-clase-tipo="1" data-ritmo="1" class="rounded-lg px-4 py-3 bg-rojo text-white font-accent font-medium ">Continuar</
                  </div>
                </article>

                <article class="rounded-2xl shadow-lg bg-white text-gray-600 py-5">
                  <h3 class="text-2xl font-accent font-medium">Medio</h3>
                  <p class="font-bold text-base">8 clases</p>
                  <div class="my-3">
                    <div class="flex items-top justify-center">
                      <span class="self-end line-through ">$72</span>
                      <span class="font-bold">$</span>
                      <span class="font-accent font-bold text-6xl">57</span>
                      <span class="font-bold">.60</span>
                    </div>
                    <p>al mes</p>
                  </div>
                  <ul class="text-left mx-5 pl-5 list-disc text-sm">
                    <li class="text-base">$7.2 usd por clase</li>
                    <li>2 clases por semana</li>
                    <li>Clase grupal de 40 min.</li>
                    <li>Grupo de hasta 3 estudiantes</li>
                  </ul>
                  <div class="mt-7">
                    <button type="button" data-clase-tipo="1" data-ritmo="2" class="rounded-lg px-4 py-3 bg-rojo text-white font-accent font-medium ">Continuar</button>
                  </div>
                </article>

                <article class="rounded-2xl shadow-lg py-5 bg-azulw text-white dark:bg-white dark:text-gray-700">
                  <h3 class="text-2xl font-accent font-medium">Intensivo</h3>
                  <p class="font-bold text-base">12 clases</p>
                  <div class="my-3">
                    <div class="flex items-top justify-center">
                      <span class="self-end line-through ">$96</span>
                      <span class="font-bold">$</span>
                      <span class="font-accent font-bold text-6xl">76</span>
                      <span class="font-bold">.80</span>
                    </div>
                    <p>al mes</p>
                  </div>
                  <ul class="text-left mx-5 pl-5 list-disc text-sm">
                    <li class="text-base">$6.4 usd por clase</li>
                    <li>3 clases por semana</li>
                    <li>Clase grupal de 40 min.</li>
                    <li>Grupo de hasta 3 estudiantes</li>
                  </ul>
                  <div class="mt-7">
                    <button type="button" data-clase-tipo="1" data-ritmo="3" class="rounded-lg px-4 py-3 bg-rojo text-white font-accent font-medium ">Continuar</button>
                  </div>
                </article>

                <article class="rounded-2xl shadow-lg py-5 bg-rojo text-white">
                  <h3 class="text-2xl font-accent font-medium">Intensivo+</h3>
                  <p class="font-bold text-base">20 clases</p>
                  <div class="my-3">
                    <div class="flex items-top justify-center">
                      <span class="self-end line-through ">$140</span>
                      <span class="font-bold">$</span>
                      <span class="font-accent font-bold text-6xl">112</span>
                    </div>
                    <p>al mes</p>
                  </div>
                  <ul class="text-left mx-5 pl-5 list-disc text-sm">
                    <li class="text-base">$5.6 usd por clase</li>
                    <li>5 clases por semana</li>
                    <li>Clase grupal de 40 min.</li>
                    <li>Grupo de hasta 3 estudiantes</li>
                  </ul>
                  <div class="mt-7">
                    <button type="button" data-clase-tipo="1" data-ritmo="5" class="rounded-lg px-4 py-3 bg-azulw text-white font-accent font-medium ">Continuar</button>
                  </div>
                </article>
              </div>
            </div>{{-- tab1 --}}

            <div id="tab-content-mx2" class="tabpanel hidden" data-tab="2" role="tabpanel" aria-labelledby="tab-title-mx2" aria-expanded="false" aria-selected="false" >

              <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-5 lg:gap-6 xl:gap-7">

                <article class="rounded-2xl shadow-lg bg-white text-gray-600 py-5">
                  <h3 class="text-2xl font-accent font-medium">Relax</h3>
                  <p class="font-bold text-base">4 clases</p>
                  <div class="my-3">
                    <div class="flex items-top justify-center">
                      <span class="self-end line-through ">$80</span>
                      <span class="font-bold">$</span>
                      <span class="font-accent font-bold text-6xl">64</span>
                    </div>
                    <p>usd al mes</p>
                  </div>
                  <ul class="text-left mx-5 pl-5 list-disc text-sm">
                    <li class="text-base">$16 usd por clase</li>
                    <li>Clase particular de 40 mins</li>
                    <li>Un solo estudiante</li>
                    <li>1 clase por semana</li>
                  </ul>
                  <div class="mt-7">
                    <button type="button" data-clase-tipo="2" data-ritmo="1" class="rounded-lg px-4 py-3 bg-rojo text-white font-accent font-medium ">Continuar</button>
                  </div>
                </article>

                <article class="rounded-2xl shadow-lg bg-white text-gray-600 py-5">
                  <h3 class="text-2xl font-accent font-medium">Medio</h3>
                  <p class="font-bold text-base">8 clases</p>
                  <div class="my-3">
                    <div class="flex items-top justify-center">
                      <span class="self-end line-through ">$160</span>
                      <span class="font-bold">$</span>
                      <span class="font-accent font-bold text-6xl">128</span>
                    </div>
                    <p>usd al mes</p>
                  </div>
                  <ul class="text-left mx-5 pl-5 list-disc text-sm">
                    <li class="text-base">$16 usd por clase</li>
                    <li>Clase particular de 40 mins</li>
                    <li>Un solo estudiante</li>
                    <li>2 clases por semana</li>
                  </ul>
                  <div class="mt-7">
                    <button type="button" data-clase-tipo="2" data-ritmo="2" class="rounded-lg px-4 py-3 bg-rojo text-white font-accent font-medium ">Continuar</button>
                  </div>
                </article>

                <article class="rounded-2xl shadow-lg py-5 bg-azulw text-white dark:bg-white dark:text-gray-700">
                  <h3 class="text-2xl font-accent font-medium">Intensivo</h3>
                  <p class="font-bold text-base">12 clases</p>
                  <div class="my-3">
                    <div class="flex items-top justify-center">
                      <span class="self-end line-through ">$216</span>
                      <span class="font-bold">$</span>
                      <span class="font-accent font-bold text-6xl">172</span>
                      <span class="font-bold">.80</span>
                    </div>
                    <p>al mes</p>
                  </div>
                  <ul class="text-left mx-5 pl-5 list-disc text-sm">
                    <li class="text-base">$14.4 usd por clase</li>
                    <li>Clase particular de 40 mins</li>
                    <li>Un solo estudiante</li>
                    <li>3 clases por semana</li>
                  </ul>
                  <div class="mt-7">
                    <button type="button" data-clase-tipo="2" data-ritmo="3" class="rounded-lg px-4 py-3 bg-rojo text-white font-accent font-medium ">Continuar</button>
                  </div>
                </article>

                <article class="rounded-2xl shadow-lg py-5 bg-rojo text-white">
                  <h3 class="text-2xl font-accent font-medium">Intensivo+</h3>
                  <p class="font-bold text-base">20 clases</p>
                  <div class="my-3">
                    <div class="flex items-top justify-center">
                      <span class="self-end line-through ">$320</span>
                      <span class="font-bold">$</span>
                      <span class="font-accent font-bold text-6xl">256</span>
                    </div>
                    <p>al mes</p>
                  </div>
                  <ul class="text-left mx-5 pl-5 list-disc text-sm">
                    <li class="text-base">$12.8 usd por clase</li>
                    <li>Clase particular de 40 mins</li>
                    <li>Un solo estudiante</li>
                    <li>5 clases por semana</li>
                  </ul>
                  <div class="mt-7">
                    <button type="button" data-clase-tipo="2" data-ritmo="5" class="rounded-lg px-4 py-3 bg-azulw text-white font-accent font-medium ">Continuar</button>
                  </div>
                </article>
              </div>

            </div>
          </div>
        </div>

        <div id="SubscriptionPricesUS" class="tabs-widget overflow-hidden" data-active-class="" >
          <div class="tabs-wrapper font-accent font-medium text-xl text-azul-600 flex justify-center" role="tablist">
            <div id="tab-title-us1" data-tab="1" role="tab" aria-controls="tab-content-us1" aria-expanded="true" aria-selected="true" class="tab active cursor-pointer py-10px px-3 border-l border-r border-t border-black/20 dark:border-white/20 text-rojo relative after:content-[''] after:absolute after:left-full after:bottom-0 after:w-[100vw] after:h-0 after:border-t after:border-black/20 after:dark:border-white/20 before:content-[''] before:absolute before:right-full before:bottom-0 before:w-[100vw] before:h-0 before:border-t before:border-black/20 before:dark:border-white/20" > {{__('Grupal')}} </div>

            <div id="tab-title-us2" data-tab="2" role="tab" aria-controls="tab-content-us2" aria-expanded="false" aria-selected="false" class="tab cursor-pointer py-10px px-3 dark:text-gray-200" > {{__('Individuales')}} </div>
          </div>
          <div class="tabs-content border-l border-r border-b border-black/20 dark:border-white/20">
            <div id="tab-content-us1" data-tab="1" role="tabpanel" aria-labelledby="tab-title-us1" aria-expanded="true" aria-selected="true" class="tabpanel active p-5"  >
              <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-5 lg:gap-6 xl:gap-7">

                <article class="rounded-2xl shadow-lg bg-white text-gray-600 py-5">
                  <h3 class="text-2xl font-accent font-medium">Relax</h3>
                  <p class="font-bold text-base">4 clases</p>
                  <div class="my-3">
                    <div class="flex items-top justify-center">
                      <span class="self-end line-through ">$44</span>
                      <span class="font-bold">$</span>
                      <span class="font-accent font-bold text-6xl">35</span>
                      <span class="font-bold">.17</span>
                    </div>
                    <p>al mes</p>
                  </div>
                  <ul class="text-left mx-5 pl-5 list-disc text-sm">
                    <li class="text-base">$8.8 usd por clase</li>
                    <li>1 clase por semana</li>
                    <li>Clase grupal de 40 min.</li>
                    <li>Grupo de hasta 3 estudiantes</li>
                  </ul>
                  <div class="mt-7">
                    <button type="button" data-clase-tipo="1" data-ritmo="1" class="rounded-lg px-4 py-3 bg-rojo text-white font-accent font-medium ">Continuar</button>
                  </div>
                </article>

                <article class="rounded-2xl shadow-lg bg-white text-gray-600 py-5">
                  <h3 class="text-2xl font-accent font-medium">Medio</h3>
                  <p class="font-bold text-base">8 clases</p>
                  <div class="my-3">
                    <div class="flex items-top justify-center">
                      <span class="self-end line-through ">$88</span>
                      <span class="font-bold">$</span>
                      <span class="font-accent font-bold text-6xl">70</span>
                      <span class="font-bold">.34</span>
                    </div>
                    <p>al mes</p>
                  </div>
                  <ul class="text-left mx-5 pl-5 list-disc text-sm">
                    <li class="text-base">$8.8 usd por clase</li>
                    <li>2 clases por semana</li>
                    <li>Clase grupal de 40 min.</li>
                    <li>Grupo de hasta 3 estudiantes</li>
                  </ul>
                  <div class="mt-7">
                    <button type="button" data-clase-tipo="1" data-ritmo="2" class="rounded-lg px-4 py-3 bg-rojo text-white font-accent font-medium ">Continuar</button>
                  </div>
                </article>

                <article class="rounded-2xl shadow-lg py-5 bg-azulw text-white dark:bg-white dark:text-gray-700">
                  <h3 class="text-2xl font-accent font-medium">Intensivo</h3>
                  <p class="font-bold text-base">12 clases</p>
                  <div class="my-3">
                    <div class="flex items-top justify-center">
                      <span class="self-end line-through ">$120</span>
                      <span class="font-bold">$</span>
                      <span class="font-accent font-bold text-6xl">95</span>
                      <span class="font-bold">.91</span>
                    </div>
                    <p>al mes</p>
                  </div>
                  <ul class="text-left mx-5 pl-5 list-disc text-sm">
                    <li class="text-base">$8 usd por clase</li>
                    <li>3 clases por semana</li>
                    <li>Clase grupal de 40 min.</li>
                    <li>Grupo de hasta 3 estudiantes</li>
                  </ul>
                  <div class="mt-7">
                    <button type="button" data-clase-tipo="1" data-ritmo="3" class="rounded-lg px-4 py-3 bg-rojo text-white font-accent font-medium ">Continuar</button>
                  </div>
                </article>

                <article class="rounded-2xl shadow-lg py-5 bg-rojo text-white">
                  <h3 class="text-2xl font-accent font-medium">Intensivo+</h3>
                  <p class="font-bold text-base">20 clases</p>
                  <div class="my-3">
                    <div class="flex items-top justify-center">
                      <span class="self-end line-through ">$180</span>
                      <span class="font-bold">$</span>
                      <span class="font-accent font-bold text-6xl">144</span>
                    </div>
                    <p>al mes</p>
                  </div>
                  <ul class="text-left mx-5 pl-5 list-disc text-sm">
                    <li class="text-base">$7.2 usd por clase</li>
                    <li>5 clases por semana</li>
                    <li>Clase grupal de 40 min.</li>
                    <li>Grupo de hasta 3 estudiantes</li>
                  </ul>
                  <div class="mt-7">
                    <button type="button" data-clase-tipo="1" data-ritmo="5" class="rounded-lg px-4 py-3 bg-azulw text-white font-accent font-medium ">Continuar</button>
                  </div>
                </article>
              </div>
            </div>{{-- tab1 --}}

            <div id="tab-content-us2" data-tab="2" role="tabpanel" aria-labelledby="tab-title-us2" aria-expanded="false" aria-selected="false" class="tabpanel hidden p-5" >

              <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-5 lg:gap-6 xl:gap-7">

                <article class="rounded-2xl shadow-lg bg-white text-gray-600 py-5">
                  <h3 class="text-2xl font-accent font-medium">Relax</h3>
                  <p class="font-bold text-base">4 clases</p>
                  <div class="my-3">
                    <div class="flex items-top justify-center">
                      <span class="self-end line-through ">$100</span>
                      <span class="font-bold">$</span>
                      <span class="font-accent font-bold text-6xl">80</span>
                    </div>
                    <p>usd al mes</p>
                  </div>
                  <ul class="text-left mx-5 pl-5 list-disc text-sm">
                    <li class="text-base">$20 usd por clase</li>
                    <li>Clase particular de 40 mins</li>
                    <li>Un solo estudiante</li>
                    <li>1 clase por semana</li>
                  </ul>
                  <div class="mt-7">
                    <button type="button" data-clase-tipo="2" data-ritmo="1" class="rounded-lg px-4 py-3 bg-rojo text-white font-accent font-medium ">Continuar</button>
                  </div>
                </article>

                <article class="rounded-2xl shadow-lg bg-white text-gray-600 py-5">
                  <h3 class="text-2xl font-accent font-medium">Medio</h3>
                  <p class="font-bold text-base">8 clases</p>
                  <div class="my-3">
                    <div class="flex items-top justify-center">
                      <span class="self-end line-through ">$200</span>
                      <span class="font-bold">$</span>
                      <span class="font-accent font-bold text-6xl">160</span>
                    </div>
                    <p>usd al mes</p>
                  </div>
                  <ul class="text-left mx-5 pl-5 list-disc text-sm">
                    <li class="text-base">$20 usd por clase</li>
                    <li>Clase particular de 40 mins</li>
                    <li>Un solo estudiante</li>
                    <li>2 clases por semana</li>
                  </ul>
                  <div class="mt-7">
                    <button type="button" data-clase-tipo="2" data-ritmo="2" class="rounded-lg px-4 py-3 bg-rojo text-white font-accent font-medium ">Continuar</button>
                  </div>
                </article>

                <article class="rounded-2xl shadow-lg py-5 bg-azulw text-white dark:bg-white dark:text-gray-700">
                  <h3 class="text-2xl font-accent font-medium">Intensivo</h3>
                  <p class="font-bold text-base">12 clases</p>
                  <div class="my-3">
                    <div class="flex items-top justify-center">
                      <span class="self-end line-through ">$264</span>
                      <span class="font-bold">$</span>
                      <span class="font-accent font-bold text-6xl">211</span>
                      <span class="font-bold">.20</span>
                    </div>
                    <p>al mes</p>
                  </div>
                  <ul class="text-left mx-5 pl-5 list-disc text-sm">
                    <li class="text-base">$17.6 usd por clase</li>
                    <li>Clase particular de 40 mins</li>
                    <li>Un solo estudiante</li>
                    <li>3 clases por semana</li>
                  </ul>
                  <div class="mt-7">
                    <button type="button" data-clase-tipo="2" data-ritmo="3" class="rounded-lg px-4 py-3 bg-rojo text-white font-accent font-medium ">Continuar</button>
                  </div>
                </article>

                <article class="rounded-2xl shadow-lg py-5 bg-rojo text-white">
                  <h3 class="text-2xl font-accent font-medium">Intensivo+</h3>
                  <p class="font-bold text-base">20 clases</p>
                  <div class="my-3">
                    <div class="flex items-top justify-center">
                      <span class="self-end line-through ">$400</span>
                      <span class="font-bold">$</span>
                      <span class="font-accent font-bold text-6xl">320</span>
                    </div>
                    <p>al mes</p>
                  </div>
                  <ul class="text-left mx-5 pl-5 list-disc text-sm">
                    <li class="text-base">$16 usd por clase</li>
                    <li>Clase particular de 40 mins</li>
                    <li>Un solo estudiante</li>
                    <li>5 clases por semana</li>
                  </ul>
                  <div class="mt-7">
                    <button type="button" data-clase-tipo="2" data-ritmo="5" class="rounded-lg px-4 py-3 bg-azulw text-white font-accent font-medium ">Continuar</button>
                  </div>
                </article>
              </div>

            </div>
          </div>
        </div>

        <div class="my-6 md:flex justify-between font-medium font-accent text-sm">
          <button type="button" class="btn-back shadow-md rounded-full px-5 py-3 bg-white text-gray-600">Regresar</button>
        </div>
      </section>
    </form>
  </section>

  <aside class="lg:hidden">
    <div class="rounded-lg bg-white px-4 py-5 shadow-md">
      <h2 class="text-lg md:text-xl font-medium font-accent mb-3"><a href="{{ route('regprof') }}">Teacher sign-up</a></h2>

      <a class="block mb-3" href="{{ route('regprof') }}">
        <img width="1536" height="1536"  class="lazyload w-full h-auto" alt="" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="  data-src="https://wiseabcenglish.com/wp-content/uploads/2022/09/profesor-digital-1x1-1.jpg" decoding="async" data-srcset="https://wiseabcenglish.com/wp-content/uploads/2022/09/profesor-digital-1x1-1.jpg 1536w, https://wiseabcenglish.com/wp-content/uploads/2022/09/profesor-digital-1x1-1-420x420.jpg 420w, https://wiseabcenglish.com/wp-content/uploads/2022/09/profesor-digital-1x1-1-1024x1024.jpg 1024w, https://wiseabcenglish.com/wp-content/uploads/2022/09/profesor-digital-1x1-1-210x210.jpg 210w, https://wiseabcenglish.com/wp-content/uploads/2022/09/profesor-digital-1x1-1-768x768.jpg 768w" data-sizes="auto" />
      </a>
      <p class="text-sm text-right mb-3">Sé parte de nuestro equipo de profesores y Únete a la familia de WiseABC English, regístrate como profesor aquí y ahora.</p>
      <p class="text-right">
        <a href="{{ route('regprof') }}" class="inline-block rounded-lg bg-azulw p-3 font-medium font-accent text-sm text-gray-100">Profesores</a>
      </p>
    </div>
  </aside>

  @push('css')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/css/intlTelInput.css">
  @endpush

  @push('scripts')
  <script src="{{ asset('js/app-profile-form.js') }}" defer></script>
  <script>
    window.addEventListener('beforeunload', function(ev) {
      if ( document.getElementById('frmRegStudent').querySelector('input[name="ritmo"]').value!="" )
      {
        return;
      }
      let confirmationMessage = "Si sales de esta página perderás tu progreso.";
      ev.returnValue = confirmationMessage;
      return confirmationMessage;
    });
    function checkCountrySubscriptions() {
      const btn = document.querySelector('input[name="user_phone"]');
      const itiData = btn.__iti.getSelectedCountryData();

      if ( window.app.ipapi.countryCode=='US' || itiData.iso2=='us' )
      {
        document.getElementById('SubscriptionPricesMX').classList.add('hidden');
        document.getElementById('SubscriptionPricesUS').classList.remove('hidden');
      }
      else
      {
        document.getElementById('SubscriptionPricesMX').classList.remove('hidden');
        document.getElementById('SubscriptionPricesUS').classList.add('hidden');
      }
    }
  </script>
  @endpush
</x-layoutreg>