<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Melodex — Register</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body class="auth-body">

    <div class="auth-container">
        <h1 class="auth-title">Create Account</h1>
        <p class="auth-subtitle">Join Melode and feel the music</p>

        <form method="POST" action="/register">
            @csrf

            <div class="auth-input-group">
                <label>Name</label>
                <input type="text" name="name" required>
            </div>

            <div class="auth-input-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="auth-input-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <div class="auth-input-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" required>
            </div>

            <button class="auth-btn">Register</button>

            <p class="auth-link">
                Already have an account?
                <a href="/login">Log In</a>
            </p>

            <p class="auth-link">
                <a href="/">Back to Home</a>
            </p>
        </form>
    </div>

<script>
document.getElementById('registerForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const form = this;

    const payload = {
        name: form.name.value,
        email: form.email.value,
        password: form.password.value,
        password_confirmation: form.password_confirmation.value,
    };

    try {
        const response = await fetch('http://localhost:8000/api/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload),
        });

        const data = await response.json();

        if (response.ok) {
            // success
            alert('Registration successful!');
            window.location.href = '/login';
        } else {
            // validation / error
            console.error(data);

            let msg = 'Registration failed.\n';
            if (data.errors) {
                for (const [field, messages] of Object.entries(data.errors)) {
                    msg += `${field}: ${messages.join(', ')}\n`;
                }
            } else if (data.message) {
                msg += data.message;
            }
            alert(msg);
        }
    } catch (error) {
        console.error(error);
        alert('Something went wrong. Please try again later.');
    }
});
</script>

</body>
</html>
