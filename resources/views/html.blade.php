<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://unpkg.com/purecss@2.0.6/build/pure-min.css"
            integrity="sha384-Uu6IeWbM+gzNVXJcM9XV3SohHtmWE+3VGi496jvgX1jyvDTXfdK+rfZc8C1Aehk5"
            crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="app.css">
        <title>Test Project</title>
    </head>
    <body>
        <div class="pure-g">
            <div class="pure-u-2-3">
                <img src="logo.svg" width="300" class="logo" />
            </div>
            <div class="pure-u-1-3">
                @if (isset($worker))
                Logged in as: {{ $worker->name }}<br>
                <a href="workers">Switch Account</a>
                @endif
            </div>
        </div>

        <div id="vue">
            @yield('body')
        </div>

        <script src="app.js"></script>
    </body>
</html>
