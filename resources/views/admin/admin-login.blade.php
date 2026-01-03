<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .admin-card {
            border-radius: 12px;
            border: none;
            width: 100%;
            max-width: 420px;
        }
        .admin-badge {
            background: #1a73e8;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        @media (max-width: 576px) {
            .admin-card {
                padding: 1.5rem !important;
            }
            body {
                padding: 10px;
            }
        }
    </style>
</head>
<body>

<div class="card admin-card p-4 shadow-lg">
    <div class="text-center mb-3">
        <span class="admin-badge">ADMIN ONLY</span>
        <h3 class="mt-3">Admin Login</h3>
        <p class="text-muted small">Restricted access portal</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.login.submit') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login as Admin</button>
    </form>

    <div class="text-center mt-3">
        <a href="{{ route('login') }}" class="text-muted small">← Back to regular login</a>
    </div>
</div>

</body>
</html>
