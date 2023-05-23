<x-document class="forgot-password">
  <div class="min-h-screen flex items-center justify-center">
    <form method="POST" class="shadow-md rounded-xl bg-gray-50 p-4 md:p-5">
      @csrf
      <h1 class="mb-3 md:text-xl font-accent font-semibold">{{__('Send Password Reset Link')}}</h1>
      @if (session('status'))
      <p class="my-4 bg-green-100 text-green-600 p-3">{{ session('status') }}</p>
      @endif
      <x-forms.input type="email" name="email" label="{{__('E-Mail Address')}}" class="mb-3"></x-forms.input>
      <div class="text-center font-accent font-medium text-base leading-12">
        <button type="submit" class="rounded-full px-5 bg-rojo text-white">{{__('Reset Password')}}</button>
      </div>
    </form>
  </div>
</x-document>