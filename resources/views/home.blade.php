<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <meta property="og:title" content="CCU Plus | 全新生活 由此領航">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ route('home') }}">
        <meta property="og:image" content="{{ asset('assets/images/cover.png') }}">
        <meta property="og:image" content="{{ asset('assets/images/ccu-plus.png') }}">
        <meta property="og:locale" content="zh_TW">
        <meta property="fb:app_id" content="1706154966284270">
        <meta name="theme-color" content="#9d9171" >
        <meta name="apple-mobile-web-app-status-bar-style" content="#9d9171">
        <title>CCU Plus | 全新生活 由此領航</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="{{ $css }}">
    </head>
    <body class="grey lighten-5">
        <main></main>

        <script src="https://www.google-analytics.com/analytics.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment-with-locales.min.js" defer></script>
        <script src="https://www.google.com/recaptcha/api.js?onload=grecaptchaLoaded&render=explicit" defer></script>
        <script src="{{ asset('assets/js/arrive.min.js') }}" defer></script>
        <script src="{{ $js }}" defer></script>
    </body>
</html>
