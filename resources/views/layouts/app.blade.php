<!DOCTYPE html>
<html>
    <head>
        <title>{{ $appName }}</title>

        <link href="/css/app.css" rel="stylesheet" type="text/css">

        <script>
        var Suggestive = {
            appName: "{{ $appName }}",
            userId: "{{ Auth::user()->id }}",
            isAdmin: {{ Auth::user()->isAdmin() ? 'true': 'false' }}
        };
        </script>
    </head>
    <body>
        <div class="container" id="app">
            <div class="content">
                <h1>{{ $appName }}</h1>
                <div>home | <a href="/logout">log out</a></div>
                <br>

                @yield('content')

            </div>
        </div>

        <script src="/js/app.js"></script>
    </body>
</html>
