<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Melodex — Login</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body class="auth-body">

    <div class="auth-container">
        <h1 class="auth-title">Welcome Back</h1>
        <p class="auth-subtitle">Log in to continue your music journey</p>

        <form method="POST" action="/login">
            @csrf

            <div class="auth-input-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="auth-input-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <button class="auth-btn">Log In</button>

            <p class="auth-link">
                Don’t have an account?
                <a href="/register">Register</a>
            </p>

            <p class="auth-link">
                <a href="/">Back to Home</a>
            </p>
        </form>
    </div>

</body>
</html>
