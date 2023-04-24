<x-layoutreg container-class="py-12">
  <div class="mx-auto max-w-[520px]">
    <form class="grid grid-cols-1 gap-5 ajx-form" id="frmLogin" name="frmLogin" action="{{ route('login') }}" method="post">
      @csrf
      <h2 class="text-2xl font-bold font-accent">Acceso</h2>
      <p class="">Al iniciar sesión o crear una cuenta, aceptas las <a href="#">Condiciones de servicio</a> y la <a href="#">Política de privacidad</a> de WiseABC.</p>

      <p class="alert-error hidden bg-rose-300 p-3"><span class="alert-msg"></span></p>

      <div class="">
        <label for="iemail" class="block">Email <span class="text-rojo">*</span></label>
        <input type="email" id="iemail" name="email" placeholder="Correo electrónico *" required aria-required="true" class="w-full border border-gray-600 dark:border-gray-400 px-2 py-[3px] rounded-[4px] leading-8 dark:text-gray-600 " />
      </div>
      <div class="">
        <label for="ipwd" class="block">Contraseña <span class="text-rojo">*</span></label>
        <input type="password" id="ipwd" name="password" placeholder="Contraseña *" required aria-required="true" class="w-full border border-gray-600 dark:border-gray-400 px-2 py-[3px] rounded-[4px] leading-8 dark:text-gray-600" />
      </div>
      <div class="grid grid-cols-2 md:leading-12 xl:leading-8 leading-6 font-medium font-accent">
        <label for="iremember" ><input type="checkbox" name="remember" id="iremember" value="1"> Mantener iniciada mi sesión</label>
        <div class="text-right">
          <a class="inline-block underline" href="{{ route('password.request')}}">Recuperar contraseña</a>
        </div>
      </div>
      <button type="submit" class="group rounded-lg bg-rojo p-3 font-accent font-medium text-gray-100 text-center disabled:opacity-75" data-loading-text="Enviando...">
        <span class="group-disabled:hidden">Continuar</span>
        <span class="group-disabled:block hidden">Enviando...</span>
      </button>
      <div class="text-center">
        <p>Si aun no tienes cuenta, </p>
        <a href="{{ route('register') }}" class="block rounded-lg bg-azulw p-3 font-accent font-medium text-gray-100 text-center">Registrate</a>
      </div>
    </form>
  </div>

</x-layoutreg>