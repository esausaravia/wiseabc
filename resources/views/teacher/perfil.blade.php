<x-layout>

  <h1 class="text-xl lg:text-2xl font-accent font-bold mb-5">Perfil</h1>

  <form id="frmProfileCard" action="{{ route('teacher.update') }}" method="POST" enctype="multipart/form-data" class="my-5">
    @csrf
    <div class="grid gap-5 grid-cols-1 xl:grid-cols-2 my-5">

      <section class="rounded-xl py-3 px-4 bg-white text-gray-600">
        <h3 class="mb-2 font-accent font-medium text-sm text-azul dark:text-gray-600">Visible para todos</h3>

        <div class="md:flex">
          <div class="mb-4 md:mb-0 md:mr-4 flex-grow-0 flex-shrink-0">
            <figure class="rounded-full overflow-hidden border-[3px] border-dashed border-white hover:border-rojo bg-gray-100 bg-[length:67%] bg-center bg-no-repeat" style="background-image:url('{{ asset('img/user-tie-white.svg')}}')">

              <label for="iProfilePic" class="relative flex items-center justify-center text-2xl text-white">
                @if ( $profe->profilepic===null )
                <img alt="Profile pic" class="w-[150px] h-[150px] object-cover" src="{{asset('img/spacer.gif')}}" />
                @else
                <img alt="Profile pic" loading="lazy" class="lazyload w-[150px] h-[150px] object-cover" src="{{ asset('img/spacer.gif') }}" data-srcset="{{ $profe->getProfilePic() }} 80w, {{ $profe->getProfilePic(160) }} 160w, {{ $profe->getProfilePic(320) }} 320w" data-sizes="auto" />
                @endempty

                <span class="fa-stack absolute top-0 left-0 w-full h-full">
                  <i class="fa-light fa-pen-to-square fa-inverse fa-stack-1x translate-x-[2px] translate-y-[2px] text-gray-800"></i>
                  <i class="fa-light fa-pen-to-square fa-stack-1x"></i>
                </span>

                <input type="file" name="profilepic" id="iProfilePic" class="with-img-preview cursor-pointer block absolute top-0 left-0 w-full h-full opacity-0" accept="image/*" />
              </label>
            </figure>
            <p class="text-xs text-center">Imagen de perfil</p>
          </div>{{--/left--}}

          <div>
            <div class="mb-3 grid grid-cols-1 gap-3 md:grid-cols-2">
              <x-forms.input name="fname" label="Nombre" :value="$profe->fname" required></x-forms.input>

              <x-forms.input name="lname" label="Apellidos" :value="$profe->lname" required></x-forms.input>
            </div>

            <x-forms.textarea name="mini_bio" label="Mini Bio." id="mini-bio" rows="3" maxlenght="160" >{{$profe->mini_bio}}</x-forms.textarea>

          </div>{{--/right--}}

        </div>{{--/flex--}}
      </section>

      <section class="rounded-xl py-3 px-4 bg-white dark:bg-white/10">
        <h3 class="mb-2 font-accent font-medium text-sm text-azul dark:text-inherit">Datos de acceso</h3>

        <div class="mb-5 grid grid-cols-1 gap-3 md:grid-cols-2 ">

          <x-forms.input type="email" name="email" label="WiseABC Email" :value="$profe->email" disabled></x-forms.input>

          <span>&nbsp;</span>

          <x-forms.input type="password" name="password" label="Contraseña" ></x-forms.input>

          <x-forms.input type="password" name="password_confirmation" label="Confirmar contraseña" ></x-forms.input>

        </div>
      </section>

      <section class="">
        <div class="rounded-xl py-3 px-4 bg-white dark:bg-white/10">
          <h3 class="mb-2 font-accent font-medium text-sm text-azul dark:text-inherit">Información adicional</h3>

          <div class="mb-5 grid grid-cols-1 gap-3 md:grid-cols-2 ">

            <x-forms.input type="email" name="personal_email" label="Personal Email" :value="$profe->personal_email" required></x-forms.input>

            <x-forms.input type="tel" name="tel" label="Teléfono" :value="$profe->tel" required></x-forms.input>

            <x-forms.input name="pais" label="País" :value="$profe->pais" required></x-forms.input>

            <x-forms.input name="city" label="Ciudad" :value="$profe->city" required></x-forms.input>
          </div>
        </div>
      </section>

      <section class="rounded-xl py-3 px-4 bg-white dark:bg-white/10">
        <h3 class="mb-2 font-accent font-medium text-sm text-azul dark:text-inherit">Disponibilidad de horario</h3>

        <div class="grid grid-cols-4 md:grid-cols-7 gap-3 text-center leading-12 xl:leading-8">

          @php
            $weekdays = config('wiseabc.weekdays');
            $arrHorarios = $profe->getHorarioArray();
            $arrOcupados = $profe->horariosOcupados();
          @endphp
          @for ($dia=1; $dia<8; $dia++)
          <div class="rounded-lg overflow-hidden font-medium bg-white dark:bg-white/10">
            <p>{{ $weekdays[( $dia )] }}.</p>

            @for($hr=6; $hr<22; $hr++)
            <label for="idia{{$dia}}hr{{$hr}}" class="cursor-pointer block border-b border-black/10 last:border-b-0">
              <input type="checkbox" id="idia{{$dia}}hr{{$hr}}" name="horarios[{{$dia}}][]" value="{{$hr}}" class="sr-only peer" @checked( !empty($arrHorarios[$dia]) && in_array($hr, $arrHorarios[$dia]) ) @disabled( !empty($arrOcupados[$dia]) && in_array($hr, $arrOcupados[$dia]) ) />
              <span class="block px-2 peer-checked:bg-emerald-600 peer-checked:text-white peer-disabled:bg-amber-500 peer-disabled:text-white peer-checked:peer-disabled:bg-amber-500">{{$hr}}:00</span>
            </label>
            @endfor
          </div>
          @endfor
        </div>
      </section>

    </div>{{--/grid--}}

    <div class="my-5 flex justify-around leading-12 text-sm font-accent font-medium">
      <a href="{{ route('admin.teacher.index') }}" class="rounded-full px-5 bg-white dark:text-gray-600">Cancelar</a>
      <button type="submit" class=" rounded-full px-5 bg-rojo text-white">Guardar</button>
    </div>
  </form>
</x-layout>