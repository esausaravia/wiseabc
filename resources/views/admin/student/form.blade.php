<x-admin.layout>
  <div class="max-w-[1200px]">
    <h1 class="text-2xl font-accent font-bold mb-5">
      @empty( $student->id )
        Nuevo estudiante
      @else
        Editar estudiante #{{$student->id}}
      @endempty
    </h1>

    <form action="{{ route('admin.student.update', ['student'=>$student]) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">
      @csrf
      @method('PUT')

      <section>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

          <x-forms.input name="fname" label="Nombre" :value="$student->fname" required></x-forms.input>

          <x-forms.input name="lname" label="Apellido" :value="$student->lname" required></x-forms.input>

          <x-forms.input type="email" name="email" label="Correo electrónico" :value="$student->email" required></x-forms.input>

          <x-forms.input type="tel" name="tel" label="Teléfono" :value="$student->tel" required></x-forms.input>

          <x-forms.input type="password" name="password" label="Contraseña" ></x-forms.input>

          <x-forms.select label="Estatus" name="status" :options="['active'=>'Activo', 'disabled'=>'Desactivado']" :value="$student->status" required></x-forms.select>

          <x-forms.option-group label="Edad" name="edad" :options="config('wiseabc.edad_labels')" :value="$student->edad" required></x-option-group>

          <x-forms.select label="Nivel" name="nivel" :options="config('wiseabc.nivel_labels')" :value="$student->nivel" required></x-forms.select>
        </div>
      </section>

      <section class="">

        <x-forms.option-group label="Horarios" type="checkbox" name="horarios[1]" :options="config('wiseabc.horarios_labels')" :value="$student->getHorarioArray(1)" class="mb-3">
          <x-slot:optcont class="grid grid-cols-4 md:grid-cols-8"></x-slot>
        </x-option-group>

      </section>
      <section class="col-span-2 flex justify-around leading-12 font-accent font-medium text-sm">
        <a href="{{ route('admin.student.index') }}" class="btn rounded-full  px-4 bg-white text-gray-600 ">Regresar</a>
        <button type="submit" class="btn rounded-full px-5 bg-rojo text-white">Guardar</button>
      </section>
    </form>
  </div>
</x-admin.layout>