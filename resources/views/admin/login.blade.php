<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

<div class="card p-4 shadow-lg" style="width: 350px;">
    <h3 class="text-center mb-3">Login</h3>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" enctype="multipart/form-data">

        @csrf

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Profile Picture (optional)</label>
            <input type="file" name="profilepic" class="form-control" accept="image/*">
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>
        
        <div class="mt-3 text-center">
            <a href="{{ route('register') }}" class="text-decoration-none">Don't have an account? Register</a>
        </div>
    </div>

</body>
</html>
