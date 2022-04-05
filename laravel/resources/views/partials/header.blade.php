@section('header')
    <!DOCTYPE html>
        <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="{{ asset('/css/main.css') }}">
            <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200;400&display=swap" rel="stylesheet">
            <script src='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.js'></script>
            <link href='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.css' rel='stylesheet' />
            <script src="{{asset('/js/polyline.js')}}"></script>
        </head>

        <body class="antialiased">
