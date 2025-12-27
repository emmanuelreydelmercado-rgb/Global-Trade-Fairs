<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Event</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Edit Event</h4>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-light">← Back</a>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('form.update', $form->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Organizer Name</label>
                            <input type="text" name="Orgname" class="form-control" value="{{ $form->Orgname }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Venue</label>
                            <input type="text" name="VenueName" class="form-control" value="{{ $form->VenueName }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Event Date</label>
                            <input type="date" name="Date" class="form-control" value="{{ $form->Date }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Event Name</label>
                            <input type="text" name="ExponName" class="form-control" value="{{ $form->ExponName }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="{{ $form->phone }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $form->email }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hall No</label>
                            <input type="text" name="hallno" class="form-control" value="{{ $form->hallno }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Registration Link</label>
                            <input type="url" name="reglink" class="form-control" value="{{ $form->reglink }}">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Update Event
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
