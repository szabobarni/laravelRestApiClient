<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Melodex — ♪ Music without limits ♪</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body>

    <div class="hero">
        <div class="overlay"></div>

        <div class="content">
            <h1 class="title">Melodex</h1>
            <p class="subtitle">♪ Music without limits ♪.</p>

            <div class="buttons">
                <a href="/login" class="btn login">Log In</a>
                <a href="/register" class="btn register">Register</a>
                <a href="{{ route('artists.index') }}" class="btn guest">Continue as guest</a>
            </div>
        </div>
    </div>

</body>
</html>
