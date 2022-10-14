<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>WiseABC</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;400;700&family=Open+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
  <script src="{{ mix('js/app.js') }}" defer></script>
</head>
<body class="bg-gray-200 text-gray-800 dark:bg-azul dark:text-gray-200">
  <header id="HeaderMain">
  </header>
  <div class="container" style="min-height: calc(100vh - 280px)">
    {{$slot}}
  </div>
  <footer id="MainFooter" class="bg-azul text-gray-200 dark:bg-gray-900 dark:border-t dark:border-gray-900">
    <p class="text-center text-xs pb-2">
      &copy; 2022 Todos los derechos reservados
    </p>
  </footer>
</body>
</html>