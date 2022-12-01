<x-layout>
  <h1 class="text-xl lg:text-2xl font-accent font-bold mb-5">Perfil</h1>

  <div class="grid gap-5 grid-cols-1 lg:grid-cols-2">


    <form action="{{ route('admin.student.update', ['student'=>$user]) }}" method="POST" class="">
      @csrf

      <h2 class="text-xl font-accent font-medium">Actualizar información básica</h2>
      <small class="block mb-2">&nbsp;</small>

      <section class="grid grid-cols-1 md:grid-cols-2 gap-3">

        <x-forms.input name="fname" label="Nombre" :value="$user->fname" required></x-forms.input>

        <x-forms.input name="lname" label="Apellido" :value="$user->lname" required></x-forms.input>

        <x-forms.input type="email" name="email" label="Correo electrónico" :value="$user->email" required></x-forms.input>

        <x-forms.input type="tel" name="tel" label="Teléfono" :value="$user->tel" required></x-forms.input>

        <x-forms.input type="password" name="password" label="Contraseña" ></x-forms.input>

        <x-forms.input type="password" name="password_confirmation" label="Confirmar contraseña" ></x-forms.input>

      </section>
    </form>

    <form action="" method="POST">
      @csrf

      <h2 class="text-xl font-accent font-medium">Solicitar cambios</h2>
      <small class="block mb-2">Actualizar la siguiente información requiere validación por parte de un administrador</small>

      <section class="">

        <x-forms.option-group label="Edad" name="edad" :options="config('wiseabc.edad_labels')" :value="$user->edad" required class="mb-3"></x-option-group>

        <x-forms.option-group label="Horarios" type="checkbox" name="horarios[1]" :options="config('wiseabc.horarios_labels')" :value="$user->getHorarioArray(1)" required class="mb-3">
          <x-slot:optcont class="grid grid-cols-4 md:grid-cols-8"></x-slot>
        </x-option-group>

        <textarea name="" id="" cols="30" rows="10">{{ print_r($user->getHorarioArray(1), true) }}</textarea>
      </section>
    </form>
  </div>
</x-layout>