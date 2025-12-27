<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%);
        }
        .user-card {
            border-radius: 12px;
            border: none;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

<div class="card user-card p-4 shadow-lg" style="width: 380px;">
    <div class="text-center mb-3">
        <h3 class="mt-3">Login</h3>
        <p class="text-muted small">Welcome back</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required autofocus value="{{ old('email') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Profile Picture (optional)</label>
            <input type="file" name="profilepic" class="form-control" accept="image/*">
            <div class="form-text">Updates your account photo</div>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
            <label class="form-check-label" for="remember_me">Remember me</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">Log in</button>
    </form>

    <div class="text-center mt-3">
        <div class="d-flex justify-content-between">
            <a href="{{ route('register') }}" class="text-decoration-none small">Create account</a>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-decoration-none small text-muted">Forgot password?</a>
            @endif
        </div>
    </div>
</div>

</body>
</html>
