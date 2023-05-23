@props([
  'body'=>new \Illuminate\View\ComponentSlot()
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Wise ABC English</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
  <script>
    window.app = window.app || {};
    window.app.home = '{{ route('home') }}';
  </script>
  <script src="{{ mix('js/app.js') }}" defer></script>
  <script src="https://kit.fontawesome.com/161bce774c.js" crossorigin="anonymous" defer></script>
</head>
<body {{ $body->attributes->class(['bg-gray-200 text-gray-600 dark:bg-azulw dark:text-gray-400 text-lg', $attributes->get('class')]) }}>

  {{ $slot }}

  <x-layout-toast></x-layout-toast>
  @stack('scripts')
</body>
</html>