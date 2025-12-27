<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ isset($form) ? 'Edit Event' : 'Event Form' }}</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ isset($form) ? 'Edit Event' : 'Event Details' }}</h4>
                    @if(isset($form))
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-light">← Back</a>
                    @endif
                </div>
                <div class="card-body">
                    @if(session('message'))
                        <div id="sessionMessage" data-message="{{ session('message') }}" style="display: none;"></div>
                    @endif



                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" 
                          action="{{ isset($form) ? route('form.update', $form->id) : '/event-form' }}" 
                          enctype="multipart/form-data">
                        @csrf
                        @if(isset($form))
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Organizer Name</label>
                            <input type="text" name="Orgname" class="form-control" 
                                   value="{{ isset($form) ? $form->Orgname : old('Orgname') }}"
                                   placeholder="Enter organizer name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Venue</label>
                            <input type="text" name="VenueName" class="form-control" 
                                   value="{{ isset($form) ? $form->VenueName : old('VenueName') }}"
                                   placeholder="Enter venue" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Country</label>
                            <select name="country" id="countrySelect" class="form-control" required>
                                <option value="">Select Country</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">City</label>
                            <select name="city" id="citySelect" class="form-control" required disabled>
                                <option value="">Select Country First</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Event Date</label>
                            <input type="date" name="Date" class="form-control" 
                                   value="{{ isset($form) ? $form->Date : old('Date') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Event Name</label>
                            <input type="text" name="ExponName" class="form-control" 
                                   value="{{ isset($form) ? $form->ExponName : old('ExponName') }}"
                                   placeholder="Enter event name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Event Image</label>
                            @if(isset($form) && $form->image)
                                <div class="mb-2">
                                    <img src="{{ asset('uploads/' . $form->image) }}" alt="Current Image" style="max-height: 100px;">
                                    <small class="d-block text-muted">Current image (leave empty to keep)</small>
                                </div>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            @else
                                <input type="file" name="image" class="form-control" accept="image/*" required>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" 
                                   value="{{ isset($form) ? $form->phone : old('phone') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email ID</label>
                            <input type="email" name="email" class="form-control" 
                                   value="{{ isset($form) ? $form->email : old('email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hall No</label>
                            <input type="text" name="hallno" class="form-control" 
                                   value="{{ isset($form) ? $form->hallno : old('hallno') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Registration Link (optional)</label>
                            <input type="url" name="reglink" class="form-control" 
                                   value="{{ isset($form) ? $form->reglink : old('reglink') }}">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            {{ isset($form) ? 'Update Event' : 'Submit' }}
                        </button>

                        @if(!isset($form))
                            <button type="button" class="btn btn-secondary w-100" 
                                    onclick="window.location.href='{{ route('admin.dashboard') }}'">
                                Go to Dashboard
                            </button>
                        @endif
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

 <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const formId = "{{ isset($form) ? 'edit_event_' . $form->id : 'create_event' }}";
        const storageKey = 'event_form_data_' + formId;
        const countrySelect = document.getElementById('countrySelect');
        const citySelect = document.getElementById('citySelect');
        
        let countriesData = [];

        // Load countries data from JSON
        fetch('/data/countries-cities.json')
            .then(response => response.json())
            .then(data => {
                countriesData = data.countries;
                
                // Populate countries dropdown
                countriesData.forEach(country => {
                    const option = document.createElement('option');
                    option.value = country.name;
                    option.textContent = country.name;
                    countrySelect.appendChild(option);
                });

                // Load saved data after countries are loaded
                loadFormData();

                // If editing, set the values
                @if(isset($form))
                    countrySelect.value = "{{ $form->country ?? '' }}";
                    if (countrySelect.value) {
                        loadCities(countrySelect.value).then(() => {
                            citySelect.value = "{{ $form->city ?? '' }}";
                        });
                    }
                @endif
            })
            .catch(error => console.error('Error loading countries:', error));

        // Handle country selection change
        countrySelect.addEventListener('change', function() {
            const selectedCountry = this.value;
            
            if (selectedCountry) {
                loadCities(selectedCountry);
            } else {
                citySelect.disabled = true;
                citySelect.innerHTML = '<option value="">Select Country First</option>';
            }
            
            saveFormData();
        });

        // Function to load cities for a country
        function loadCities(countryName) {
            return fetch(`/get-cities/${encodeURIComponent(countryName)}`)
                .then(response => response.json())
                .then(data => {
                    citySelect.disabled = false;
                    citySelect.innerHTML = '<option value="">Select City</option>';
                    
                    if (data.cities && data.cities.length > 0) {
                        data.cities.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city;
                            option.textContent = city;
                            citySelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading cities:', error);
                    citySelect.disabled = true;
                });
        }

        // Listen to city selection change
        citySelect.addEventListener('change', saveFormData);

        // Function to save form data to localStorage
        function saveFormData() {
            const formData = new FormData(form);
            const data = {};
            formData.forEach((value, key) => {
                // Don't save files
                if (!(value instanceof File)) {
                    data[key] = value;
                }
            });
            localStorage.setItem(storageKey, JSON.stringify(data));
        }

        // Function to load form data from localStorage
        function loadFormData() {
            const savedData = localStorage.getItem(storageKey);
            if (savedData) {
                const data = JSON.parse(savedData);
                
                // Restore country first
                if (data.country && countrySelect) {
                    countrySelect.value = data.country;
                    
                    // Then load and restore city
                    if (data.country) {
                        loadCities(data.country).then(() => {
                            if (data.city && citySelect) {
                                citySelect.value = data.city;
                            }
                        });
                    }
                }
                
                // Restore other fields
                Object.keys(data).forEach(key => {
                    if (key !== 'country' && key !== 'city') {
                        const field = form.querySelector(`[name="${key}"]`);
                        if (field && field.type !== 'file') {
                            if (!field.value || field.value === "") {
                                field.value = data[key];
                            }
                        }
                    }
                });
            }
        }

        // Listen for input changes to save data
        form.addEventListener('input', saveFormData);

        // Clear localStorage on form submission
        form.addEventListener('submit', function() {
            localStorage.removeItem(storageKey);
        });

        // Check for session message and show modal
        const sessionMessageDiv = document.getElementById('sessionMessage');
        if (sessionMessageDiv) {
            const message = sessionMessageDiv.getAttribute('data-message');
            if (message) {
                // Set the message in the modal
                document.getElementById('successMessageText').textContent = message;
                // Show the modal
                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            }
        }
    });

</script>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="successModalLabel">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16" style="margin-right: 8px;">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg>
                    Event Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="fs-5" id="successMessageText"></p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>
