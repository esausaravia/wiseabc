<x-layout class="student-home text-lg">
  @pushOnce('scripts')
    @vite('resources/js/students.js')
  @endPushOnce

  <h1>Exito!</h1>

  <form action="{{ route('student.stripe.create-portal-session') }}" method="POST">
    @csrf
    <input type="hidden" id="session-id" name="session_id" value="" />
    <button id="checkout-and-portal-button" type="submit">Manage your billing information</button>
  </form>
</x-layout>