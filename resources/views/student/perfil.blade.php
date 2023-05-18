<x-layout class="student-perfil">
  @push('scripts')
  <script defer src="{{ asset('./js/students.js') }}"></script>
  @endpush
  <h1 class="text-2xl lg:text-3xl font-accent font-semibold mb-5">Perfil</h1>

  <form action="{{ route('student.update') }}" method="POST" class="grid gap-5 grid-cols-1 lg:grid-cols-2">

    <div>
      <section class="flex flex-wrap -mx-2">
        @csrf
        @method('PATCH')

        <h2 class="w-full md:text-xl font-accent font-semibold mb-3 px-2">Actualizar información básica</h2>

        <x-forms.input name="fname" label="Nombre" :value="$user->fname" required class="w-1/2 mb-3 px-2"></x-forms.input>

        <x-forms.input name="lname" label="Apellido" :value="$user->lname" required class="w-1/2 mb-3 px-2"></x-forms.input>

        <x-forms.input type="email" name="email" label="Correo electrónico" :value="$user->email" required class="w-1/2 mb-3 px-2"></x-forms.input>

        <x-forms.input type="tel" name="tel" label="Teléfono" :value="$user->tel" required class="w-1/2 mb-3 px-2"></x-forms.input>

        <x-forms.input type="password" name="password" label="Contraseña" class="w-1/2 mb-3 px-2" ></x-forms.input>

        <x-forms.input type="password" name="password_confirmation" label="Confirmar contraseña" class="w-1/2 mb-3 px-2" ></x-forms.input>
      </section>
    </div>

    <div>
      <section>
        @if( is_object($user->currentClassroom) )

        <h2 class="text-xl font-accent font-semibold">Solicitar cambios</h2>
        <p class="block mb-2">Ahora que ya tiene una clase y profesor asignados, debe ponerse en contacto con nosotros para actualizar su información de colocación.</p>

        <p>Edad: <strong class="">{{ $user->edadLabel }}</strong></p>
        <p>Nivel de inglés: <strong class="">{{ $user->nivelLabel }}</strong></p>
        <p>Tipo de clase:
          <strong class="">{{ __(config('wiseabc.clase_tipo_labels.'.$user->clase_tipo)) }} {{ __(config('wiseabc.ritmo_labels.'.$user->ritmo)) }}</strong>
        </p>
        <p>Ritmo: <strong class="">{{ $user->ritmo }} {{ $user->ritmo>1 ? 'clases' : 'clase' }} por semana</strong></p>

        @else
          <h2 class=" md:text-xl font-accent font-semibold mb-3">{{__('Información de colocación')}}</h2>
          <x-forms.option-group label="Edad" name="edad" :options="config('wiseabc.edad_labels')" :value="$user->edad" required class="mb-3"></x-option-group>

          <x-forms.option-group class="mb-5" label="Disponibilidad de horarios" type="checkbox" name="horarios[1]" :options="config('wiseabc.horarios_labels')" :value="$user->getHorariosArrayTimezoned(1)" >
            <x-slot:optcont class="rounded-lg shadow-md grid grid-cols-4 md:grid-cols-8 bg-white text-gray-500 leading-12 xl:leading-8"></x-slot>
          </x-option-group>

          <h4 class="font-accent font-medium">Nivel de inglés</h4>
          <div class="mb-5 grid grid-cols-1 gap-4 md:grid-cols-3">
            <x-forms.option-group name="nivel" label="Principiante" :options="['1'=>'1','2'=>'2','3'=>'3']" :fullwidth="true" :value="$user->nivel" class=""></x-forms.option-group>
            <x-forms.option-group name="nivel" label="Intermedio" :options="[4=>4,5=>5,6=>6]" :fullwidth="true" :value="$user->nivel" class=""></x-forms.option-group>
            <x-forms.option-group name="nivel" label="Avanzado" :options="[7=>7,8=>8,9=>9]" :fullwidth="true" :value="$user->nivel" class=""></x-forms.option-group>
          </div>
        @endif

      </section>
    </div>
    <div class="@unless( is_object($user->currentClassroom) ) lg:col-span-2 @endunless flex justify-around font-accent font-medium text-base leading-12">
      <a href="#" class="btn inline-block shadow-md rounded-full px-5 bg-white text-gray-600">Regresar</a>
      <button type="submit" class="btn shadow-md rounded-full px-5 border-rojo bg-rojo text-white">Guardar</button>
    </div>
  </form>
</x-layout>