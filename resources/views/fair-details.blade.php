<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $form->ExponName ?? 'Event Details' }}</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    <!-- Bootstrap + fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { background: #f6f8fb; font-family: 'Poppins', sans-serif; color: #24303f; }
        .detail-wrap { max-width: 1100px; margin: 40px auto; }
        .card-outer { border-radius: 14px; box-shadow: 0 8px 30px rgba(9,30,66,0.08); overflow: hidden; border: none; }

        .hero {
            background: linear-gradient(90deg, rgba(13,110,253,0.06), rgba(0,123,255,0.02));
            padding: 28px;
        }
        .title-main { 
            font-size: 30px; 
            font-weight: 700; 
            color: #0d6efd; 
            margin: 0; 
        }
        .subtitle { color: #5f6b78; margin-top: 6px; }

        /* event image */
        .event-image {
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(13,110,253,0.08);
            object-fit: cover;
            max-height: 420px;
        }

        /* Info cards */
        .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
        .info-item {
            background: #ffffff;
            padding: 16px 18px;
            border-radius: 10px;
            border: 1px solid rgba(13,110,253,0.08);
            box-shadow: 0 6px 18px rgba(13,110,253,0.03);
        }
        .info-label {
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            color: #0d6efd;
            margin-bottom: 4px;
        }
        .info-value {
            font-size: 15px;
            font-weight: 500;
            color: #2b3640;
        }

        @media (max-width: 767px) {
            .info-grid { grid-template-columns: 1fr; }
            .event-cols { flex-direction: column; gap: 18px; }
            .title-main { font-size: 24px; }
        }

        .btn-row { margin-top: 18px; display:flex; gap:10px; justify-content:flex-end; }
        .meta-tag { font-size: 13px; background:#eef6ff; padding:6px 10px; border-radius: 8px; color:#0d6efd; }
    </style>
</head>
<body>

<div class="detail-wrap">

    <div class="card card-outer">
        
        <!-- Header Section -->
        <div class="hero d-flex align-items-center justify-content-between flex-wrap">
            <div>
                <h1 class="title-main">{{ $form->ExponName }}</h1>
                <div class="subtitle">
                    Organizer: {{ $form->Orgname }} &bullet; Date: {{ $form->Date }}
                </div>
            </div>
            <div class="text-end">
                <div class="meta-tag">Hall: {{ $form->hallno ?? '—' }}</div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-4">
            <div class="d-flex event-cols" style="gap:28px; align-items:flex-start;">

                <!-- Left: Image -->
                <div style="flex:0 0 45%; max-width:45%;">
                    <img src="{{ asset($form->image ? 'uploads/'.$form->image : 'default.jpg') }}" 
                         class="event-image"loading="lazy">
                    <div class="mt-3 text-muted" style="font-size:13px;">For event registration, use the button below if provided.</div>
                </div>

                <!-- Right: Details -->
                <div style="flex:1;">
                    <div class="info-grid">

                        <div class="info-item">
                            <div class="info-label">Venue</div>
                            <div class="info-value">{{ $form->VenueName }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">City</div>
                            <div class="info-value">{{ $form->city }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Country</div>
                            <div class="info-value">{{ $form->country ?? '—' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Date</div>
                            <div class="info-value">{{ $form->Date }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Organizer</div>
                            <div class="info-value">{{ $form->Orgname }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Phone</div>
                            <div class="info-value">{{ $form->phone ?? '—' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $form->email ?? '—' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Hall No</div>
                            <div class="info-value">{{ $form->hallno ?? '—' }}</div>
                        </div>

                        @if($form->reglink)
                        <div class="info-item" style="grid-column: 1 / -1;">
                            <div class="info-label">Registration</div>
                            <div class="info-value">
                                <a href="{{ $form->reglink }}" target="_blank" class="btn btn-primary btn-sm">
                                    Open Registration Page
                                </a>
                            </div>
                        </div>
                        @endif

                    </div>

                    <!-- Buttons -->
                    <div class="btn-row">
                        <a href="{{ route('home') }}" class="btn btn-secondary">← Back</a>
                    </div>

                </div>

            </div>
        </div>

    </div>

</div>

</body>
</html>
