<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
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

<div class="card user-card p-4 shadow-lg" style="width: 400px;">
    <div class="text-center mb-3">
        <h3 class="mt-3">Create Account</h3>
        <p class="text-muted small">Join us today</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required autofocus value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Profile Picture (optional)</label>
            <input type="file" name="profilepic" class="form-control" accept="image/*">
            <div class="form-text">Upload your profile photo</div>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>

    <div class="text-center mt-3">
        <a href="{{ route('login') }}" class="text-decoration-none small">Already have an account? Log in</a>
    </div>
</div>

</body>
</html>
