<img loading="lazy" {{ $attributes->merge([
    'alt' => config('app.name'),
    'class' => 'lazyload '
  ]) }} width="{{$width}}" height="{{$height}}" src="{{$src}}" data-src="{{$src_url}}" data-sizes="auto" />