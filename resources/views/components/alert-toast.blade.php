@props([
    'type'=>'info',
    'message'
])
<div {{ $attributes->class([
    'alert toast fixed bottom-0 left-1/2 -translate-x-1/2 shadow-lg rounded-t-xl p-5',
    'bg-white text-gray-600' => ( $type=='info' ),
    'bg-green-100 text-green-700' => ( $type=='success' ),
    'bg-amber-200 text-amber-600' => ( $type=='warning' ),
    'bg-rose-200 text-rose-600' => ( $type=='error' ),
])->merge(['role'=>'alert']) }}>
  <span class="block">{{ $message }}</span>
  <button type="button" class="btn-close absolute top-0 right-0 w-6" aria-label="Close"><i class="fa-light fa-times"></i></button>
</div>