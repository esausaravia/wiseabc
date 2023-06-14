<x-layoutreg container-class="py-12 text-center">
  <h1 class="text-2xl md:text-3xl font-bold text-azul-600 my-6">Elige tu subscripción para finalizar tu registro</h1>

  <p class="max-w-[600px] mx-auto mb-8 text-center">
    Puedes cambiarla más adelante
  </p>

  @if( is_object($region) && $region->id==2 )

  <div class="tabs-widget overflow-hidden" data-active-class="" >
    <div class="tabs-wrapper font-accent font-medium text-xl text-azul-600 flex justify-center" role="tablist">
      <div id="tab-grp-title" class="tab active cursor-pointer py-10px px-3 border-l border-r border-t border-black/20 dark:border-white/20 text-rojo relative after:content-[''] after:absolute after:left-full after:bottom-0 after:w-[100vw] after:h-0 after:border-t after:border-black/20 after:dark:border-white/20 before:content-[''] before:absolute before:right-full before:bottom-0 before:w-[100vw] before:h-0 before:border-t before:border-black/20 before:dark:border-white/20" data-tab="1" role="tab" aria-controls="tab-grp-content" aria-expanded="true" aria-selected="true"><span class="hidden md:inline">Clases</span> Grupales</div>

      <div id="tab-title2" class="tab cursor-pointer py-10px px-3 dark:text-gray-200" data-tab="2" role="tab" aria-controls="tab-content2" aria-expanded="false" aria-selected="false"><span class="hidden md:inline">Clases</span> Individuales</div>
    </div>
    <div class="tabs-content">
      <div id="tab-grp-content" class="tabpanel active border-l border-r border-b border-black/20 dark:border-white/20 p-5" data-tab="1" role="tabpanel" aria-labelledby="tab-grp-title" aria-expanded="true" aria-selected="true" >
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
            <form action="{{ route('student.elegir-ritmo') }}" method="post" class="mt-7">
              @csrf
              <input type="hidden" name="clase_tipo" value="1" />
              <input type="hidden" name="ritmo" value="1" />
              <button type="submit" class="rounded-lg px-4 py-3 bg-rojo text-white font-accent font-medium ">Continuar</button>
            </form>
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
            <form action="{{ route('student.elegir-ritmo') }}" method="post" class="mt-7">
              @csrf
              <input type="hidden" name="clase_tipo" value="1" />
              <input type="hidden" name="ritmo" value="2" />
              <button type="submit" class="rounded-lg px-4 py-3 bg-rojo text-white font-accent font-medium ">Continuar</button>
            </form>
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
            <form action="{{ route('student.elegir-ritmo') }}" method="post" class="mt-7">
              @csrf
              <input type="hidden" name="clase_tipo" value="1" />
              <input type="hidden" name="ritmo" value="3" />
              <button type="submit" class="rounded-lg px-4 py-3 bg-rojo text-white font-accent font-medium ">Continuar</button>
            </form>
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
            <form action="{{ route('student.elegir-ritmo') }}" method="post" class="mt-7">
              @csrf
              <input type="hidden" name="clase_tipo" value="1" />
              <input type="hidden" name="ritmo" value="5" />
              <button type="submit" class="rounded-lg px-4 py-3 bg-azulw text-white font-accent font-medium ">Continuar</button>
            </form>
          </article>
        </div>
      </div>{{-- tab1 --}}

      <div id="tab-content2" class="tabpanel hidden" data-tab="2" role="tabpanel" aria-labelledby="tab-title2" aria-expanded="false" aria-selected="false" >

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
            <form action="{{ route('student.elegir-ritmo') }}" method="post" class="mt-7">
              @csrf
              <input type="hidden" name="clase_tipo" value="2" />
              <input type="hidden" name="ritmo" value="1" />
              <button type="submit" class="rounded-lg px-4 py-3 bg-rojo text-white font-accent font-medium ">Continuar</button>
            </form>
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
            <form action="{{ route('student.elegir-ritmo') }}" method="post" class="mt-7">
              @csrf
              <input type="hidden" name="clase_tipo" value="2" />
              <input type="hidden" name="ritmo" value="2" />
              <button type="submit" class="rounded-lg px-4 py-3 bg-rojo text-white font-accent font-medium ">Continuar</button>
            </form>
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
            <form action="{{ route('student.elegir-ritmo') }}" method="post" class="mt-7">
              @csrf
              <input type="hidden" name="clase_tipo" value="2" />
              <input type="hidden" name="ritmo" value="3" />
              <button type="submit" class="rounded-lg px-4 py-3 bg-rojo text-white font-accent font-medium ">Continuar</button>
            </form>
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
            <form action="{{ route('student.elegir-ritmo') }}" method="post" class="mt-7">
              @csrf
              <input type="hidden" name="clase_tipo" value="2" />
              <input type="hidden" name="ritmo" value="5" />
              <button type="submit" class="rounded-lg px-4 py-3 bg-azulw text-white font-accent font-medium ">Continuar</button>
            </form>
          </article>
        </div>

      </div>
    </div>
  </div>

  @else

  <div class="tabs-widget overflow-hidden" data-active-class="" >
    <div class="tabs-wrapper font-accent font-medium text-xl text-azul-600 flex justify-center" role="tablist">
      <div id="tab-grp-title" class="tab active cursor-pointer py-10px px-3 border-l border-r border-t border-black/20 dark:border-white/20 text-rojo relative after:content-[''] after:absolute after:left-full after:bottom-0 after:w-[100vw] after:h-0 after:border-t after:border-black/20 after:dark:border-white/20 before:content-[''] before:absolute before:right-full before:bottom-0 before:w-[100vw] before:h-0 before:border-t before:border-black/20 before:dark:border-white/20" data-tab="1" role="tab" aria-controls="tab-grp-content" aria-expanded="true" aria-selected="true"> {{__('Grupal')}} </div>

      <div id="tab-title2" class="tab cursor-pointer py-10px px-3 dark:text-gray-200" data-tab="2" role="tab" aria-controls="tab-content2" aria-expanded="false" aria-selected="false"> {{__('Individuales')}} </div>
    </div>
    <div class="tabs-content">
      <div id="tab-grp-content" class="tabpanel active border-l border-r border-b border-black/20 dark:border-white/20 p-5" data-tab="1" role="tabpanel" aria-labelledby="tab-grp-title" aria-expanded="true" aria-selected="true" >
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
            <form action="{{ route('student.elegir-ritmo') }}" method="post" class="mt-7">
              @csrf
              <input type="hidden" name="clase_tipo" value="1" />
              <input type="hidden" name="ritmo" value="1" />
              <button type="submit" class="rounded-lg px-4 py-3 bg-rojo text-white font-accent font-medium ">Continuar</button>
            </form>
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
            <form action="{{ route('student.elegir-ritmo') }}" method="post" class="mt-7">
              @csrf
              <input type="hidden" name="clase_tipo" value="1" />
              <input type="hidden" name="ritmo" value="2" />
              <button type="submit" class="rounded-lg px-4 py-3 bg-rojo text-white font-accent font-medium ">Continuar</button>
            </form>
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
            <form action="{{ route('student.elegir-ritmo') }}" method="post" class="mt-7">
              @csrf
              <input type="hidden" name="clase_tipo" value="1" />
              <input type="hidden" name="ritmo" value="3" />
              <button type="submit" class="rounded-lg px-4 py-3 bg-rojo text-white font-accent font-medium ">Continuar</button>
            </form>
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
            <form action="{{ route('student.elegir-ritmo') }}" method="post" class="mt-7">
              @csrf
              <input type="hidden" name="clase_tipo" value="1" />
              <input type="hidden" name="ritmo" value="5" />
              <button type="submit" class="rounded-lg px-4 py-3 bg-azulw text-white font-accent font-medium ">Continuar</button>
            </form>
          </article>
        </div>
      </div>{{-- tab1 --}}

      <div id="tab-content2" class="tabpanel hidden" data-tab="2" role="tabpanel" aria-labelledby="tab-title2" aria-expanded="false" aria-selected="false" >

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
            <form action="{{ route('student.elegir-ritmo') }}" method="post" class="mt-7">
              @csrf
              <input type="hidden" name="clase_tipo" value="2" />
              <input type="hidden" name="ritmo" value="1" />
              <button type="submit" class="rounded-lg px-4 py-3 bg-rojo text-white font-accent font-medium ">Continuar</button>
            </form>
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
            <form action="{{ route('student.elegir-ritmo') }}" method="post" class="mt-7">
              @csrf
              <input type="hidden" name="clase_tipo" value="2" />
              <input type="hidden" name="ritmo" value="2" />
              <button type="submit" class="rounded-lg px-4 py-3 bg-rojo text-white font-accent font-medium ">Continuar</button>
            </form>
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
            <form action="{{ route('student.elegir-ritmo') }}" method="post" class="mt-7">
              @csrf
              <input type="hidden" name="clase_tipo" value="2" />
              <input type="hidden" name="ritmo" value="3" />
              <button type="submit" class="rounded-lg px-4 py-3 bg-rojo text-white font-accent font-medium ">Continuar</button>
            </form>
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
            <form action="{{ route('student.elegir-ritmo') }}" method="post" class="mt-7">
              @csrf
              <input type="hidden" name="clase_tipo" value="2" />
              <input type="hidden" name="ritmo" value="5" />
              <button type="submit" class="rounded-lg px-4 py-3 bg-azulw text-white font-accent font-medium ">Continuar</button>
            </form>
          </article>
        </div>

      </div>
    </div>
  </div>
  @endif

  @push('scripts')
  <script>
    window.addEventListener('beforeunload', function(ev) {
      let confirmationMessage = "Si sales de esta página perderás tu progreso.";
      ev.returnValue = confirmationMessage;
      return confirmationMessage;
    });
  </script>
  @endpush
</x-layoutreg>