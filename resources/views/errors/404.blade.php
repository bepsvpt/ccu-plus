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
        <title>CCU Plus | 全新生活 由此領航</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <style>
            html {
                font-family: sans-serif;
                height: 100%;
                font-size: 15px;
                line-height: 1.5;
                color: rgba(0,0,0,0.87);
            }

            body {
                height: 100%;
                margin: 0;
                background-color: #fafafa;
                display: flex;
                align-items: center;
            }

            h4 {
                font-size: 2.28rem;
                line-height: 110%;
                margin: 1.14rem 0 0.912rem 0;
                font-weight: 400;
            }

            h5 {
                font-size: 1.64rem;
                line-height: 110%;
                margin: 0.82rem 0 0.656rem 0;
                font-weight: 400;
            }

            a {
                font-weight: inherit;
                text-decoration: none;
                color: #039be5;
                background-color: transparent;
            }
        </style>
    </head>
    <body>
        <main style="text-align: center; margin: 0 auto;">
            <div style="position: relative;">
                <img src="/assets/images/icon.png">
                <i
                    class="material-icons"
                    style="position: absolute; bottom: 0; margin-left: -31px; font-size: 4rem; color: #ff9800;"
                >warning</i>
            </div>

            <br>

            <h4>船長，這裡沒路了</h4>

            <br>

            <h5><a href="{{ route('home') }}">回首頁</a></h5>
        </main>
    </body>
</html>
