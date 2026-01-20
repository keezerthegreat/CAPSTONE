<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="login-body">
<form method="POST" action="/login" class="login-box">
    @csrf
    <h2>Login</h2>

    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>

    <button type="submit">Login</button>

    @if(session('error'))
        <p class="error">{{ session('error') }}</p>
    @endif
</form>
</body>
</html>
