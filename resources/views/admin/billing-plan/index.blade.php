<x-admin.layout>
  <h1 class="mb-8 font-accent font-medium text-3xl text-gray-700 dark:text-gray-300">Planes de subscripción</h1>

  <h2 class="my-5 font-accent font-medium text-gray-700 dark:text-gray-300">Planes para US</h2>
  <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
    @foreach ( $billingPlans->where('bill_region_id',1) as $bplan )
      <article class="shadow-md rounded-lg bg-gray-50 dark:bg-gray-800">
        <div class="p-3">
          <h4 class="font-accent"><a href="{{ route('admin.billingplans.show', $bplan->id) }}">{{ $bplan->name }}</a></h4>
          <div class="flex flex-wrap -mx-2 text-base">
            <p class="px-2"><b>Precio: </b><span>$@money($bplan->price/100)</span></p>
            <p class="px-2"><b>Subs: </b><span>{{ $bplan->subscriptions_count }}</span></p>
          </div>
          <p class="mt-5 flex justify-around font-accent font-medium text-base leading-10 xl:leading-8 text-center">
            <a href="{{ route('admin.billingplans.show', $bplan->id) }}" class="btn shadow-md rounded-full border py-1 xl:py-0 px-4 bg-white dark:bg-gray-700 border-gray-200 dark:border-gray-600">Ver</a>

            <a href="{{ route('admin.billingplans.edit', $bplan->id) }}" class="btn shadow-md rounded-full border py-1 xl:py-0 px-4 bg-white dark:bg-gray-700 border-gray-200 dark:border-gray-600">Editar</a>
          </p>
        </div>
      </article>
    @endforeach
  </div>

  <h2 class="my-5 font-accent font-semibold">Planes para MX</h2>
  <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
    @foreach ( $billingPlans->where('bill_region_id', 2) as $bplan )
      <article class="shadow-md rounded-lg bg-gray-50 dark:bg-gray-800">
        <div class="p-3">
          <h4 class="font-accent"><a href="{{ route('admin.billingplans.show', $bplan->id) }}">{{ $bplan->name }}</a></h4>
          <div class="flex flex-wrap -mx-2 text-base">
            <p class="px-2"><b>Precio: </b><span>$@money($bplan->price/100)</span></p>
            <p class="px-2"><b>Subs: </b><span>{{ $bplan->subscriptions_count }}</span></p>
          </div>
          <p class="mt-5 flex justify-around font-accent font-medium text-base leading-10 xl:leading-8 text-center">

            <a href="{{ route('admin.billingplans.show', $bplan->id) }}" class="btn shadow-md rounded-full border py-1 xl:py-0 px-4 bg-white dark:bg-gray-700 border-gray-200 dark:border-gray-600">Ver</a>

            <a href="{{ route('admin.billingplans.edit', $bplan->id) }}" class="btn shadow-md rounded-full border py-1 xl:py-0 px-4 bg-white dark:bg-gray-700 border-gray-200 dark:border-gray-600">Editar</a>
          </p>
        </div>
      </article>
    @endforeach
  </div>
</x-admin.layout>