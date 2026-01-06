<!DOCTYPE html>
<html>
<head>
    <title>Profile Picture Debug</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .info { background: #f0f0f0; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .error { background: #ffe5e5; }
        .success { background: #e5ffe5; }
        img { max-width: 200px; border: 2px solid #ccc; }
    </style>
</head>
<body>
    <h1>Profile Picture Diagnostic</h1>
    
    @auth
        <div class="info">
            <h3>Database Info</h3>
            <p><strong>User ID:</strong> {{ auth()->user()->id }}</p>
            <p><strong>Username:</strong> {{ auth()->user()->name }}</p>
            <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
            <p><strong>Profile Pic (raw from DB):</strong> <code>{{ auth()->user()->profilepic ?? 'NULL' }}</code></p>
        </div>

        <div class="info">
            <h3>Path Tests</h3>
            <?php
                $profilepic = auth()->user()->profilepic;
                $paths = [
                    'asset(profilepics/)' => asset('profilepics/' . $profilepic),
                    'asset(storage/)' => asset('storage/' . $profilepic),
                    'Storage::url()' => \Storage::url($profilepic ?? ''),
                ];
            ?>
            @foreach($paths as $method => $url)
                <p><strong>{{ $method }}:</strong><br><code>{{ $url }}</code></p>
            @endforeach
        </div>

        <div class="info">
            <h3>File Existence Checks</h3>
            <?php
                $checks = [
                    'public/profilepics/' . $profilepic => public_path('profilepics/' . $profilepic),
                    'storage/app/public/' . $profilepic => storage_path('app/public/' . $profilepic),
                    'storage/app/' . $profilepic => storage_path('app/' . $profilepic),
                ];
            ?>
            @foreach($checks as $label => $path)
                <p class="{{ file_exists($path) ? 'success' : 'error' }}">
                    <strong>{{ $label }}:</strong><br>
                    {{ file_exists($path) ? '✅ EXISTS' : '❌ NOT FOUND' }}<br>
                    <small>{{ $path }}</small>
                </p>
            @endforeach
        </div>

        <div class="info">
            <h3>Image Render Tests</h3>
            @if ($profilepic)
                <p><strong>asset('profilepics/'):</strong></p>
                <img src="{{ asset('profilepics/' . $profilepic) }}" onerror="this.alt='FAILED TO LOAD'">
                
                <p><strong>asset('storage/'):</strong></p>
                <img src="{{ asset('storage/' . $profilepic) }}" onerror="this.alt='FAILED TO LOAD'">
            @endif
        </div>
    @else
        <div class="info error">
            <p>❌ You must be logged in to see debug info</p>
        </div>
    @endauth
</body>
</html>
