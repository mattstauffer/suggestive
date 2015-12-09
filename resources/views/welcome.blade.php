<!DOCTYPE html>
<html>
    <head>
        <title>{{ $appName }}</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 96px;
            }

            .login {
                font-size: 1.5rem;
            }

            .login-button {
                background: #fafafa;
                border: 1px solid #444;
                border-radius: 0.5rem;
                color: #444;
                font-weight: bold;
                padding: 0.5rem 1.75rem;
                text-decoration: none;
            }

            .login-button:hover {
                background: #fff;
                color: #000;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">{{ $appName }}</div>
                <div class="login">
                    <br>
                    <a href="/auth/github" class="login-button">Log in with GitHub</a><br><br><br>
                    <a href="/auth/twitter" class="login-button">Log in with Twitter</a><br>
                </div>
            </div>
        </div>
    </body>
</html>
