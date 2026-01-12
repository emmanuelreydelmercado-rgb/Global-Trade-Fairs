<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .user-card {
            border-radius: 12px;
            border: none;
            width: 100%;
            max-width: 440px;
        }
        @media (max-width: 576px) {
            .user-card {
                padding: 1.5rem !important;
            }
            body {
                padding: 10px;
            }
        }
    </style>
</head>
<body>

<div class="card user-card p-4 shadow-lg">
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

    <!-- OR Divider -->
    <div class="d-flex align-items-center my-3">
        <hr class="flex-grow-1">
        <span class="px-3 text-muted small">OR</span>
        <hr class="flex-grow-1">
    </div>

    <!-- Google Sign-Up Button -->
    <a href="{{ route('auth.google.register') }}" class="btn btn-outline-dark w-100 d-flex align-items-center justify-content-center gap-2" style="border-radius: 8px; padding: 10px;">
        <svg width="20" height="20" viewBox="0 0 48 48">
            <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
            <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
            <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
            <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
            <path fill="none" d="M0 0h48v48H0z"></path>
        </svg>
        <span class="fw-semibold">Sign up with Google</span>
    </a>

    <div class="text-center mt-3">
        <a href="{{ route('login') }}" class="text-decoration-none small">Already have an account? Log in</a>
    </div>
</div>

@if(session('registration_success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Registration Successful!',
        text: '{{ session('registration_success') }}',
        confirmButtonText: 'OK',
        confirmButtonColor: '#1a73e8'
    });
</script>
@endif

</body>
</html>
