<x-admin.layout>
  <div class="max-w-[1200px]">
    <h1 class="text-3xl font-accent font-bold mb-5">
      {{ empty($curso->id) ? 'Nuevo' : 'Editar' }} Curso
    </h1>
    <form action="{{ $form_action }}" method="POST" class="md:flex md:flex-wrap md:items-start">
      @csrf
      @method( !empty($curso->id) ? 'PUT' : 'POST' )
      <input type="hidden" name="id" value="{{ $curso->id }}">

      <section class="md:w-1/2 grid grid-cols-1 gap-3">

        @if ($errors->any())
          <div class="alert bg-rose-200">
            @foreach ( $errors->all() as $error )
              <li>{{$error}}</li>
            @endforeach
          </div>
        @endif

        <div class="fieldset">
          <label for="iname" class="block after:content-['*'] after:text-rose-700 after:pl-1">Nombre </label>
          <input type="text" id="iname" name="name" value="{{ old('name', $curso->name) }}" required placeholder="Nombre del curso" class="block shadow-sm px-3 py-1 rounded-md border border-black/5 bg-white text-gray-600 leading-9 xl:leading-6" />
        </div>

        <div class="fieldset">
          <label for="istatus" class="block after:content-['*'] after:text-rose-700 after:pl-1">Estatus</label>
          <select name="status" id="istatus" required class="block shadow-sm px-3 py-1 rounded-md border border-black/5 bg-white text-gray-600 leading-9 xl:leading-6" value="{{ old('status', $curso->status) }}">
            <option value="active">Activo</option>
            <option value="disabled">Desactivado</option>
          </select>
        </div>

        <div class="fieldset">
          <label for="" class="block after:content-['*'] after:text-rose-700 after:pl-1">Edad</label>

          <div class="flex">
            <div class="flex rounded-lg shadow-md bg-white text-gray-500">

              @foreach ( config('wiseabc.edad_labels') as $_key=>$_label)
              <div class="border-r border-black/5">
                <input type="radio" name="edad" id="iedad-{{$_key}}" value="{{$_key}}" required class="peer sr-only" @checked( old('edad', $curso->edad)==$_key ) />
                <label for="iedad-{{$_key}}" class="block cursor-pointer px-3 leading-12 xl:leading-8 font-normal peer-checked:font-bold peer-checked:text-rojo">
                  {{$_label}}
                </label>
              </div>
              @endforeach

            </div>
          </div>
        </div>{{-- /edad --}}

        <div class="fieldset">
          <label for="inivel" class="block after:content-['*'] after:text-rose-700 after:pl-1">Nivel</label>
          <select name="nivel" id="inivel" required class="block shadow-sm px-3 py-1 rounded-md border border-black/5 bg-white text-gray-600 leading-9 xl:leading-6" value="{{ old('nivel', $curso->nivel) }}" >
            @foreach ( config('wiseabc.nivel_labels') as $nk=>$nivel)
              <option value="{{ $nk }}">{{ $nivel }}</option>
            @endforeach
          </select>
        </div>

        <div class="fieldset">
          <label for="iduracion" class="block after:content-['*'] after:text-rose-700 after:pl-1">Duración </label>
          <input type="number" id="iduracion" name="duracion" value="{{ old('duracion', !empty($curso->duracion) ? $curso->duracion : 48 ) }}" required placeholder="48" class="block shadow-sm px-3 py-1 rounded-md border border-black/5 bg-white text-gray-600 leading-9 xl:leading-6" />
        </div>

      </section>

      <div class="w-full mt-5">
        <button type="submit" class="rounded-lg px-5 leading-12 bg-rojo text-white">Guardar</button>
      </div>
    </form>
  </div>
</x-admin.layout>