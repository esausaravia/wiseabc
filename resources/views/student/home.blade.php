<x-layout class="student-home">

  @push('scripts')
  <script defer src="{{ asset('./js/students.js') }}"></script>
  @endpush

  <div class="flex mb-8">
    <section class="shadow-md rounded-xl p-4 bg-gray-50">
      <h2 class="font-accent font-semibold text-2xl md:text-3xl mb-4">¡Bienvenido a WiseABC English!</h2>

      <p>Estamos entusiasmados con tu llegada a nuestra institución. En esta página podrás consultar tu perfil y los detalles de tus cursos y clases, así como los avances y monitorear el progreso del estudiante.</p>

    </section>
  </div>

  <div class="flex flex-wrap -mx-2 md:-mx-3">

    @if ( empty( $user->email_verified_at ) )
    <div class="w-full max-w-2xl lg:w-1/2 px-3 mb-8">
      <section class="shadow-md rounded-xl p-4 bg-amber-100 text-amber-600">
        <h2 class="text-xl font-semibold font-accent mb-4">Verificación de correo electrónico pendiente</h2>
        <p class="mb-2 text-gray-700">
          Le enviamos un correo a su dirección <span class="font-medium underline">{{$user->email}}</span> para verificar su cuenta.
        </p>
        <p class="text-base text-gray-500">
          Por favor, revisé la bandeja de su correo electrónico o su carpeta de correo no deseado (spam).
        </p>
      </section>
    </div>
    @endif {{--/email_verified_at --}}

    @if( is_object($classroom) )

    <div class="w-full px-3 mb-8 ">
      <section class="shadow-md rounded-xl overflow-hidden md:grid md:grid-cols-2 xl:grid-cols-4 ">
        <div class="bg-green-100 text-green-800 p-3 flex">
          <figure class="text-5xl mr-3 flex items-center justify-center text-green-700">
            <i class="fa-light fa-person-chalkboard"></i>
          </figure>
          <div>
            <strong class="block text-sm">Curso:</strong>
            <h4>{{$classroom->curso->name}}</h4>
            <strong class="block text-sm">Teacher:</strong>
            <h4>{{$classroom->teacher->name}}</h4>
          </div>
        </div>
        <div class="flex p-3 bg-yellow-100 text-amber-800">
          <figure class="text-5xl mr-3 flex items-center justify-center text-amber-600">
            <i class="fa-light fa-calendars"></i>
          </figure>
          <div>

            <ul class="">
              <li class="mr-2"><strong>Inicio</strong>: {{ $classroom->start->isoFormat('D MMM Y') }} </li>
              <li class="mr-2"><strong>Fin</strong>: {{ $classroom->ends_at->isoFormat('D MMM Y') }} </li>
              <!-- <li class="mr-2">( {{ $classroom->endsInWeeks() }} sem. )</li> -->
            </ul>

            <ul class="flex flex-wrap">
              @foreach ( $classroom->getHorarioArray() as $dia=>$arrHr )
              <li class="mr-2">
                <strong class="capitalize">{{ !empty($weekdays[( $dia )]) ? $weekdays[( $dia )] : $dia }}</strong>:
                @foreach ($arrHr as $hr )
                  {{$hr}}:00,
                @endforeach
              </li>
              @endforeach
            </ul>
          </div>
        </div>
        <div class="flex items-center p-3 bg-violet-200 text-violet-800">

          <figure class="text-5xl mr-3 flex items-center justify-center">
            @if ($classroom->tipo==1)
            <i class="fa-light fa-screen-users"></i>
            @else
            <i class="fa-light fa-screen-users"></i>
            @endif
          </figure>

          <div>
            <p class="font-bold text-sm">Tipo:</p>
            <p class="">Clase {{$classroom->tipoLabel}}</p>
          </div>
        </div>
        <div class="p-3 bg-blue-200 text-center">
          <p class="font-bold text-sm">Ritmo:</p>
          <p class="mb-3">{{$classroom->ritmoLabel}}</p>
          <ul class="flex justify-around">
            <li class="rounded-full border-4 h-5 w-5 border-azul-600"></li>
            @switch($classroom->ritmo)
              @case(1)
              <li class="rounded-full border-4 h-5 w-5 border-azul-600"></li>
              <li class="rounded-full border-4 h-5 w-5 border-azul-600 bg-azul-600"></li>
              <li class="rounded-full border-4 h-5 w-5 border-azul-600"></li>
                @break

              @case(2)
              <li class="rounded-full border-4 h-5 w-5 border-azul-600 bg-azul-600"></li>
              <li class="rounded-full border-4 h-5 w-5 border-azul-600"></li>
              <li class="rounded-full border-4 h-5 w-5 border-azul-600 bg-azul-600"></li>

                @break

              @case(3)
              <li class="rounded-full border-4 h-5 w-5 border-azul-600 bg-azul-600"></li>
              <li class="rounded-full border-4 h-5 w-5 border-azul-600 bg-azul-600"></li>
              <li class="rounded-full border-4 h-5 w-5 border-azul-600 bg-azul-600"></li>

                @break

              @case(5)
              <li class="rounded-full border-4 h-5 w-5 border-azul-600 bg-azul-600"></li>
              <li class="rounded-full border-4 h-5 w-5 border-azul-600 bg-azul-600"></li>
              <li class="rounded-full border-4 h-5 w-5 border-azul-600 bg-azul-600"></li>
              <li class="rounded-full border-4 h-5 w-5 border-azul-600 bg-azul-600"></li>
              <li class="rounded-full border-4 h-5 w-5 border-azul-600 bg-azul-600"></li>

                @break

              @default

            @endswitch
            <li class="rounded-full border-4 h-5 w-5 border-azul-600"></li>
          </ul>
        </div>
      </section>
    </div>

    @else {{-- SIN CLASE --}}

    <h2 class="w-full my-8 px-3 text-xl lg:text-2xl font-accent font-semibold ">Aún no tiene una clase asignada</h2>
    <div class="w-full max-w-2xl lg:w-1/2 px-3 mb-8">
      <section id="divSinClassroom" class="shadow-md rounded-xl overflow-clip bg-gray-50 dark:bg-white/10 p-4">
        <p class="mb-5">Nuestro personal evaluará su perfil de estudiante para asignar un profesor y una clase con base en las opciones que eligió durante su registro. Mismas que puede modificar antes de tener su clase asignada, con el formulario a continuación:</p>

        <form action="{{ route('student.update-metas') }}" method="POST" class=" text-center">
          @csrf
          @method('PATCH')

          <x-forms.option-group name="edad" label="Edad" :options="config('wiseabc.edad_labels')" :value="$user->edad" class="mb-5" center></x-forms.option-group>

          <h4 class="font-accent font-semibold">Nivel de inglés</h4>
          <div class="mb-5 grid grid-cols-1 gap-4 md:grid-cols-3">
            <x-forms.option-group name="nivel" label="Principiante" :options="['1'=>'1','2'=>'2','3'=>'3']" :fullwidth="true" :value="$user->nivel" class=""></x-forms.option-group>
            <x-forms.option-group name="nivel" label="Intermedio" :options="[4=>4,5=>5,6=>6]" :fullwidth="true" :value="$user->nivel" class=""></x-forms.option-group>
            <x-forms.option-group name="nivel" label="Avanzado" :options="[7=>7,8=>8,9=>9]" :fullwidth="true" :value="$user->nivel" class=""></x-forms.option-group>
          </div>

          <x-forms.option-group class="mb-5" label="Disponibilidad de horarios" type="checkbox" name="horarios[1]" :options="config('wiseabc.horarios_labels')" :value="$user->getHorariosArrayTimezoned(1)" center>
            <x-slot:optcont class="rounded-lg shadow-md grid grid-cols-4 md:grid-cols-8 bg-white text-gray-500 leading-12 xl:leading-8"></x-slot>
          </x-option-group>

          <div class="mb-5 text-left">
            <div>
              <label class="text-base font-accent">{{ __('Tipo de clase') }}:</label>
              <span class="font-semibold text-rojo" >{{ __( config('wiseabc.clase_tipo_labels.'.$user->clase_tipo) ) }}</span>
            </div>
            <div>
              <label class="text-base font-accent">{{ __('Ritmo') }}:</label>
              <span class="font-semibold text-rojo" >{{ __( config('wiseabc.ritmo_labels.'.$user->ritmo) ) }}</span>
              <span>({{$user->ritmo}} clases por semana)</span>
            </div>
            <div class="text-base">Para modificar el tipo de clase y/o ritmo deberá elegir otro tipo de subscripción en la siguiente sección.</div>
          </div>
          <div class="flex justify-around font-accent font-medium leading-12 xl:leading-8">
            <button type="submit" class="btn shadow-md rounded-full px-4 bg-rojo text-white ">Guardar</button>
          </div>
        </form>
      </section>
    </div>
    @endif {{-- endif clase --}}

    @if( !empty($nextSchedule) && is_object($nextSchedule) )
    <section class="mb-8 max-w-2xl lg:w-2/3 xl:w-1/2 ">

      <article class="shadow-md rounded-xl md:flex bg-gray-50 dark:bg-white/5 p-4">
        <div class="mb-5 md:mb-0 md:mr-5">
          <h2 class=" font-accent font-bold text-xl lg:text-2xl">Próxima clase</h2>
          <p class="">{{ $nextSchedule->fechahora->isoFormat('dddd D MMMM h:00 a') }} – {{ $nextSchedule->fechahora->isoFormat('h:40 a')}}</p>
        </div>
        <div>
          @if ( $activarSigClaseBtn )

          <a href="https://teams.microsoft.com/l/meetup-join/19%3ameeting_YTliMzhiMjMtZmExZC00NTc2LWEwNWYtOTI4MWNhZDhhYjU5%40thread.v2/0?context=%7b%22Tid%22%3a%22e196ec1f-12cb-43bf-a0aa-f7d14741ec2e%22%2c%22Oid%22%3a%22467eb4b6-313a-4960-8367-d383828c9a41%22%7d" target="_blank" class="btn flex px-4 py-3 rounded-full border-2 border-white bg-violet-700 text-white">
            <span class="mr-2">Abrir</span>
            <img alt="Abrir Teams" src="{{ asset('img/mteams-white.svg')}}" >
          </a>

          @else

          <a class="btn flex px-4 py-3 rounded-full border-2 border-white bg-gray-600 text-white">
            <span class="mr-2">Abrir</span>
            <img alt="Abrir Teams" src="{{ asset('img/mteams-white.svg')}}" >
          </a>

          @endif
        </div>
      </article>
    </section>
    @endif {{--/nextSchedule--}}

    @if ( is_object($subscripcion) || is_object($billPlan) )
    <div class="w-full max-w-2xl lg:w-2/3 xl:w-1/2 px-3 mb-8">
      <x-student.subscription-card :subscripcion="$subscripcion" :billplan="$billPlan" :classroom="$classroom" :subscription-qty="$subscriptionQty" :subscription-start-date="$subscriptionStartDate" ></x-student.subscription-card>

      <div class="">
        <p class="mb-3">Cualquier duda, escríbenos a <a href="mailto:admin@wiseabcenglish.com">admin@wiseabcenglish.com</a></p>
        <p class="mb-3">Teléfonos: <br />+1 239 3564996 <br /> +1 239 3563567</p>
      </div>
    </div>
    @endif


    <div class="w-full px-3"></div>
  </div>
</x-layout>