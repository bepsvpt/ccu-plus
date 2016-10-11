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
  </head>
  <body>
    <div id="ccu-plus">
      <router-view name="header"></router-view>
      <router-view></router-view>
      <router-view name="footer"></router-view>
    </div>

    <script src="https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit" defer></script>
    <script src="{{ _asset('vendor.js') }}" integrity="sha384-BIyeTAPUiNVJKk3ByNla8KTOfxeI9pvIQIYj/AQX4bNWj0BQdqInvSE4f0v3K7Sr" defer></script>
    <script src="{{ _asset('main.js') }}" integrity="sha384-CIcXA6yTvEunyVmLUVKFPaUGVowQFQwZwV7w3UFrwCw1I7Eb1OcVOqnAAUxo1YcV" defer></script>
  </body>
</html>
