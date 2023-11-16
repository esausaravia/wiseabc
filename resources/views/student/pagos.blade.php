<x-layout class="student-pagos">
  @pushOnce('scripts')
    @vite('resources/js/students.js')
  @endPushOnce
  <h1 class="mb-5 text-2xl lg:text-3xl font-accent font-semibold">Pagos</h1>

  <div class="md:flex md:flex-wrap md:-mx-3">
    <div class="w-full max-w-2xl lg:w-2/3 xl:w-1/2 px-3 mb-8">
      <x-student.subscription-card :subscripcion="$subscripcion" :billplan="$billPlan" :subscription-qty="$subscriptionQty" :subscription-start-date="$subscriptionStartDate"></x-student.subscription-card>
    </div>
  </div>
</x-layout>