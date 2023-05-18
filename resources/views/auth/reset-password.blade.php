<x-document class="reset-password">

  <div class="min-h-screen flex items-center justify-center">
    <form action="{{ route('password.update') }}" method="POST" class="shadow-md rounded-xl bg-gray-50 p-4 md:p-5">
      @csrf
      <input type="hidden" name="token" value="{{ request()->route('token') }}">

      <h1 class="mb-3 md:text-xl font-accent font-semibold">{{__('Reset Password')}}</h1>

      <p class="mb-3">{{__('Please confirm your password before continuing.')}}</p>

      <x-forms.input type="email" name="email" label="{{__('E-Mail Address')}}" value="{{ $request->input('email') }}" class="mb-3" readonly></x-forms.input>

      <x-forms.input label="{{__('Password')}}" name="password" type="password" required class="mb-3"></x-forms.input>
      <x-forms.input label="{{__('Confirm Password')}}" name="password_confirmation" type="password" required class="mb-3"></x-forms.input>

      <div class="text-center font-accent font-medium text-base leading-12">
        <button type="submit" class="rounded-full px-5 bg-rojo text-white">{{__('Save')}}</button>
      </div>

    </form>
  </div>

</x-document>