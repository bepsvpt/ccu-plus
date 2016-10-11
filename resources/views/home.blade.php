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
    <link rel="stylesheet" href="{{ asset('assets/css/bulma.min.css') }}" integrity="sha384-hE6QNVYMjfoCI4CUPK++G5c4ZuS3Dk/nDsE+ytMA06IU4e3Qo7PQSPHDnmsecrqd">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1">
    <link rel="stylesheet" href="{{ _asset('main.css') }}" integrity="sha384-uyxfdspB8FOR/XkA8KulVXt/u9i1mjbh99GntJTwiRPjdEEI8OGGAEu+WfuHJgOr">
  </head>
  <body>
    <div id="ccu-plus">
      <router-view name="header"></router-view>
      <router-view></router-view>
      <router-view name="footer"></router-view>
    </div>

    <script src="https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit" defer></script>
    <script src="{{ _asset('vendor.js') }}" integrity="sha384-DK3H0bRdXLe1X4MwudeAd742Ma2ZIEaswcv5G+15LETjm3xYeHmb4MY40vYYLhPt" defer></script>
    <script src="{{ _asset('main.js') }}" integrity="sha384-jSuEjjLmTN/zbaME/U0DH3lt2gYnU9KuHhdsYVS7hyPW2OBwmA7iShOufutIOUoy" defer></script>
  </body>
</html>
