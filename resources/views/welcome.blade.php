<!DOCTYPE html>
<html>
    <head>
        <title>{{ $appName }}</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:300,700" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                font-family: 'Lato';
                margin: 6em 2em;
                padding: 2em;
            }

            .container {
                margin: 0 auto;
                max-width: 1000px;
                text-align: center;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 6em;
                font-weight: 500;
            }

            .login {
                font-size: 1.5rem;
                margin-top: 2em;
            }

            .login-button {
                background: #fafafa;
                border: 2px solid #444;
                border-radius: 0.25rem;
                color: #444;
                display: inline-block;
                font-weight: 700;
                margin: 0 0.5em;
                padding: 0.5rem 1.75rem;
                text-decoration: none;
            }

            .login-button:hover {
                background: #444;
                color: #fff;
            }

            .login-button:active {
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
                    <a href="/auth/github" class="login-button">Log in with GitHub</a>
                    <a href="/auth/twitter" class="login-button">Log in with Twitter</a>
                </div>
            </div>
        </div>
    </body>
</html>
