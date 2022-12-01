@props([
	'horarios'=>array(),
	'tachar'=>array(),
	'user_type'=>3
])
@php
	$weekdays = config('wiseabc.weekdays');
@endphp
<ul class="text-sm flex flex-wrap">
	@foreach ( $horarios as $_dia=>$hr )
		<x-user-card-horarios-li :dia="$weekdays[( $_dia )]" :horarios="$hr" :user_type="$user_type" ></x-user-card-horarios-li>
	@endforeach
</ul>