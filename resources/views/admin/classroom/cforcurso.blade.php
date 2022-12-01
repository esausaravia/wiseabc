<x-admin.layout>
  <div class="max-w-[1200px]">
    <h1 class="text-2xl font-accent font-bold mb-5">Crear classroom para curso</h1>
    <section class="flex mb-5">
      <div class="shadow-md rounded-lg bg-white dark:bg-white/10 p-5">
        <h4 class="mb-3"><span>[#{{$curso->id}}]</span> {{ $curso->name }}</h4>

        <div class="text-sm">
          <span class="inline-block mr-3"><strong>Edad</strong>: {{$curso->edad_label}}</span>
          <span class="inline-block mr-3"><strong>Nivel</strong>: {{$curso->nivel_label}} [{{$curso->nivel}}]</span>
          <span class="inline-block">({{$curso->duracion}} hrs)</span>
        </div>
      </div>
    </section>{{--/curso--}}

    <form action="{{ route('admin.classroom.create') }}" method="POST">
      @csrf
      <input type="hidden" name="curso_id" value="{{$curso->id}}" />
      <div class="fieldset mb-5">
        <label for="" class="block after:content-['*'] after:text-rose-700 after:pl-1">Tipo</label>

          <div class="flex">
            <div class="flex rounded-lg shadow-md bg-white text-gray-500">

              <div class="border-r border-black/5">
                <input type="radio" name="tipo" id="itipo1" value="5" required class="peer sr-only" @checked( old('tipo')==1 ) />
                <label for="itipo1" class="block cursor-pointer px-3 leading-12 xl:leading-8 font-normal peer-checked:font-bold peer-checked:text-rojo">
                  <i class="fa-light fa-users"></i>
                  Grupal
                </label>
              </div>

              <div class="border-r border-black/5">
                <input type="radio" name="tipo" id="itipo2" value="11" required class="peer sr-only" @checked( old('tipo')==2 ) />
                <label for="itipo2" class="block cursor-pointer px-3 leading-12 xl:leading-8 font-normal peer-checked:font-bold peer-checked:text-rojo">
                  <i class="fa-light fa-user"></i>
                  Individual
                </label>
              </div>

            </div>
          </div>
      </div>{{--/tipo--}}

      <section class="fieldset mb-5">
        <label for="" class="block after:content-['*'] after:text-rose-700 after:pl-1">Elegir profesor</label>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-5 xl:gap-6">
          @foreach ( $profes as $prof )
          <div>
            <input type="radio" name="teacher_id" id="iprof{{$prof->id}}" value="{{$prof->id}}" class="sr-only peer" @checked( old('teacher_id')==$prof->id ) />
            <label for="iprof{{$prof->id}}" class="block shadow-md rounded-lg bg-white dark:bg-white/10 p-3 lg:p-4 peer-checked:shadow-rose-400 peer-checked:border-2 peer-checked:border-rose-600">
              <div>{{$prof->name}} [{{$prof->id}}]</div>
              <div class="text-sm">
                <strong class="block">Horarios:</strong>
                <x-user-card-horarios :user="$prof" :disponibles="true"></x-user-card-horarios>
              </div>
            </label>
          </div>
          @endforeach
        </div>
      </section>{{--/teacher_id--}}
    </form>
  </div>
</x-admin.layout>