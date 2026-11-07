<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dog Breed Management - DogTranslation.com Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <style>
        :root {
            --primary-green: #58A509;
            --dark-bg: #1f2937;
            --sidebar-bg: #111827;
            --card-border: #e5e7eb;
            --text-muted: #6b7280;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f9fafb;
        }

        .main-content {
            margin-left: 280px;
            padding: 2rem;
            min-height: 100vh;
        }

        .page-header {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--dark-bg);
            margin: 0;
        }

        .content-card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .search-container {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .search-input-wrapper {
            flex: 1;
            min-width: 300px;
            position: relative;
        }

        .search-input-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .search-input-wrapper input {
            padding-left: 2.5rem;
        }

        .filter-dropdown {
            background-color: var(--primary-green);
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-dropdown:hover {
            background-color: #4a8a08;
        }

        .breed-table {
            width: 100%;
            border-collapse: collapse;
        }

        .breed-table thead {
            background-color: #f9fafb;
            border-bottom: 2px solid var(--card-border);
        }

        .breed-table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--dark-bg);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .breed-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--card-border);
            color: var(--dark-bg);
            vertical-align: middle;
        }

        .breed-table tbody tr:hover {
            background-color: #f9fafb;
        }

        .breed-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 0.5rem;
            border: 2px solid var(--card-border);
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .btn-add {
            background-color: var(--primary-green);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
        }

        .btn-add:hover {
            background-color: #4a8a08;
        }

        .btn-edit {
            background-color: white;
            color: var(--primary-green);
            border: 2px solid var(--primary-green);
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
        }

        .btn-edit:hover {
            background-color: #f0f9e8;
        }

        .top-analyzed-section {
            margin-top: 2rem;
        }

        .top-analyzed-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .top-analyzed-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark-bg);
        }

        .top-analyzed-stats {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-bg);
        }

        .stat-change {
            font-size: 0.875rem;
            color: var(--primary-green);
            font-weight: 500;
        }

        .chart-container {
            margin-top: 1.5rem;
            height: 300px;
            position: relative;
        }

        .modal-content {
            border-radius: 0.5rem;
        }

        .modal-header {
            background-color: var(--primary-green);
            color: white;
            border-bottom: none;
        }

        .modal-header .btn-close {
            filter: invert(1);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-bg);
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control, .form-select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--card-border);
            border-radius: 0.5rem;
            font-size: 1rem;
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(88, 165, 9, 0.1);
        }

        .image-preview-container {
            margin-top: 1rem;
        }

        .image-preview {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 0.5rem;
            border: 2px solid var(--card-border);
            display: none;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
            border: none;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .search-container {
                flex-direction: column;
            }

            .search-input-wrapper {
                min-width: 100%;
            }
        }
    </style>
</head>

<body>
    <!-- Include the sidebar -->
    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <main class="main-content">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin: 1rem 0;">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin: 1rem 0;">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="page-header">
            <h1 class="page-title">Dog Breed Management</h1>
        </div>

                <div class="content-card">
            <div class="search-container">
                <div class="search-input-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" class="form-control" id="breedSearch" placeholder="Search for a breed">
                        </div>
                <div class="dropdown">
                    <button class="filter-dropdown dropdown-toggle" type="button" id="barkCharacteristicsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Bark Characteristics
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="barkCharacteristicsDropdown">
                        <li><a class="dropdown-item" href="#" data-filter="bark" data-value="">All</a></li>
                        <li><a class="dropdown-item" href="#" data-filter="bark" data-value="High pitch">High pitch</a></li>
                        <li><a class="dropdown-item" href="#" data-filter="bark" data-value="Low pitch">Low pitch</a></li>
                        <li><a class="dropdown-item" href="#" data-filter="bark" data-value="Medium pitch">Medium pitch</a></li>
                    </ul>
                    </div>
                <div class="dropdown">
                    <button class="filter-dropdown dropdown-toggle" type="button" id="responseVoiceDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Response Voice Style
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="responseVoiceDropdown">
                        <li><a class="dropdown-item" href="#" data-filter="voice" data-value="">All</a></li>
                        <li><a class="dropdown-item" href="#" data-filter="voice" data-value="Calm">Calm</a></li>
                        <li><a class="dropdown-item" href="#" data-filter="voice" data-value="Authoritative">Authoritative</a></li>
                        <li><a class="dropdown-item" href="#" data-filter="voice" data-value="Friendly">Friendly</a></li>
                        <li><a class="dropdown-item" href="#" data-filter="voice" data-value="Firm">Firm</a></li>
                        <li><a class="dropdown-item" href="#" data-filter="voice" data-value="Alert">Alert</a></li>
                    </ul>
                </div>
            </div>

            <table class="breed-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Breed Name</th>
                        <th>Bark Characteristics</th>
                        <th>Response Voice Style</th>
                        <th>Frequency</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="breedsTableBody">
                    @forelse($breeds as $breed)
                    <tr data-breed-name="{{ strtolower($breed->breed_name) }}" 
                        data-bark="{{ $breed->bark_characteristics }}" 
                        data-voice="{{ $breed->response_voice_style }}">
                        <td>
                            <img src="{{ $breed->image_url }}" 
                                 alt="{{ $breed->breed_name }}" 
                                 class="breed-image"
                                 onerror="this.src='{{ asset('images/dog-illustration.png') }}'">
                        </td>
                        <td><strong>{{ $breed->breed_name }}</strong></td>
                        <td>{{ $breed->bark_characteristics }}</td>
                        <td>{{ $breed->response_voice_style }}</td>
                        <td>{{ $breed->frequency }}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="editBreed({{ $breed->id }})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteBreed({{ $breed->id }})" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No breeds found. Please seed the database.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="action-buttons">
                <button class="btn-add" onclick="openAddBreedModal()">
                    <i class="fas fa-plus me-2"></i>Add Breed
                </button>
                    </div>
                </div>

        <div class="content-card top-analyzed-section">
            <div class="top-analyzed-header">
                <h2 class="top-analyzed-title">Top Analyzed Breeds</h2>
                <div class="top-analyzed-stats">
                    <span class="stat-value">{{ $totalAnalysis ?? 0 }}%</span>
                    <span class="stat-change">Last Month +10%</span>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="breedsChart"></canvas>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Chart data
        const breedsData = @json($breeds->map(function($breed) {
            return [
                'breed' => $breed->breed_name,
                'count' => $breed->analysis_count
            ];
        }));

        // Initialize chart
        const ctx = document.getElementById('breedsChart').getContext('2d');
        const maxCount = Math.max(...breedsData.map(b => b.count), 1);
        
        const breedsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: breedsData.map(b => b.breed),
                datasets: [{
                    label: 'Analysis Count',
                    data: breedsData.map(b => b.count),
                    backgroundColor: 'rgba(88, 165, 9, 0.2)',
                    borderColor: 'rgba(88, 165, 9, 0.8)',
                    borderWidth: 2,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: maxCount + 5,
                        ticks: {
                            stepSize: 5,
                            color: '#6b7280'
                        },
                        grid: {
                            color: '#e5e7eb'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#6b7280'
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Search functionality
        document.getElementById('breedSearch').addEventListener('input', function() {
            filterTable();
        });

        // Filter dropdown functionality
        let currentBarkFilter = '';
        let currentVoiceFilter = '';

        document.querySelectorAll('[data-filter]').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const filterType = this.getAttribute('data-filter');
                const filterValue = this.getAttribute('data-value');
                
                if (filterType === 'bark') {
                    currentBarkFilter = filterValue;
                    document.getElementById('barkCharacteristicsDropdown').textContent = 
                        filterValue || 'Bark Characteristics';
                } else if (filterType === 'voice') {
                    currentVoiceFilter = filterValue;
                    document.getElementById('responseVoiceDropdown').textContent = 
                        filterValue || 'Response Voice Style';
                }
                
                filterTable();
            });
        });

        function filterTable() {
            const searchTerm = document.getElementById('breedSearch').value.toLowerCase();
            const rows = document.querySelectorAll('#breedsTableBody tr');
            
            rows.forEach(row => {
                const breedName = row.getAttribute('data-breed-name') || '';
                const bark = row.getAttribute('data-bark') || '';
                const voice = row.getAttribute('data-voice') || '';
                
                const matchesSearch = breedName.includes(searchTerm);
                const matchesBark = !currentBarkFilter || bark.includes(currentBarkFilter);
                const matchesVoice = !currentVoiceFilter || voice.includes(currentVoiceFilter);
                
                if (matchesSearch && matchesBark && matchesVoice) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Add/Edit Breed Modal Functions
        function openAddBreedModal() {
            document.getElementById('breedForm').reset();
            document.getElementById('breedForm').action = '{{ route("admin.breeds.store") }}';
            document.getElementById('breedForm').method = 'POST';
            // Remove _method hidden input if exists
            const methodInput = document.getElementById('breedForm').querySelector('input[name="_method"]');
            if (methodInput) {
                methodInput.remove();
            }
            document.getElementById('breedModalTitle').textContent = 'Add New Breed';
            document.getElementById('breedId').value = '';
            document.getElementById('currentImagePreview').style.display = 'none';
            document.getElementById('imagePreview').style.display = 'none';
            document.getElementById('imageInput').required = false;
            
            const modal = new bootstrap.Modal(document.getElementById('breedModal'));
            modal.show();
        }

        function editBreed(id) {
            fetch(`/admin/breeds/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        const breed = data.data;
                        document.getElementById('breedId').value = breed.id;
                        document.getElementById('breed_name').value = breed.breed_name;
                        document.getElementById('bark_characteristics').value = breed.bark_characteristics;
                        document.getElementById('response_voice_style').value = breed.response_voice_style;
                        document.getElementById('frequency').value = breed.frequency;
                        
                        // Update form action and method
                        document.getElementById('breedForm').action = `/admin/breeds/${breed.id}`;
                        document.getElementById('breedForm').method = 'POST';
                        
                        // Add _method hidden input for PUT request
                        let methodInput = document.getElementById('breedForm').querySelector('input[name="_method"]');
                        if (!methodInput) {
                            methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'PUT';
                            document.getElementById('breedForm').appendChild(methodInput);
                        }
                        
                        document.getElementById('breedModalTitle').textContent = 'Edit Breed';
                        
                        if (breed.image_url) {
                            document.getElementById('currentImagePreview').src = breed.image_url;
                            document.getElementById('currentImagePreview').style.display = 'block';
                        } else {
                            document.getElementById('currentImagePreview').style.display = 'none';
                        }
                        document.getElementById('imagePreview').style.display = 'none';
                        document.getElementById('imageInput').required = false;
                        
                        const modal = new bootstrap.Modal(document.getElementById('breedModal'));
                        modal.show();
                    } else {
                        alert('Failed to load breed data');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load breed data');
                });
        }

        function deleteBreed(id) {
            if (confirm('Are you sure you want to delete this breed? This action cannot be undone.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/breeds/${id}`;
                form.innerHTML = `
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Image preview functionality
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('imageInput');
            if (imageInput) {
                imageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('imagePreview').src = e.target.result;
                            document.getElementById('imagePreview').style.display = 'block';
                            document.getElementById('currentImagePreview').style.display = 'none';
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>

    <!-- Add/Edit Breed Modal -->
    <div class="modal fade" id="breedModal" tabindex="-1" aria-labelledby="breedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="breedModalTitle">Add New Breed</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="breedForm" method="POST" action="{{ route('admin.breeds.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="breedId" name="breed_id">
                    <div class="modal-body">
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
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

                        <div class="form-group">
                            <label class="form-label" for="breed_name">Breed Name *</label>
                            <input type="text" class="form-control @error('breed_name') is-invalid @enderror" 
                                   id="breed_name" name="breed_name" 
                                   value="{{ old('breed_name') }}" required>
                            @error('breed_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="imageInput">Breed Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="imageInput" name="image" accept="image/jpeg,image/jpg,image/png">
                            @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="image-preview-container">
                                <img id="currentImagePreview" class="image-preview" alt="Current image">
                                <img id="imagePreview" class="image-preview" alt="Preview">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="bark_characteristics">Bark Characteristics *</label>
                            <input type="text" class="form-control @error('bark_characteristics') is-invalid @enderror" 
                                   id="bark_characteristics" name="bark_characteristics" 
                                   value="{{ old('bark_characteristics') }}" 
                                   placeholder="e.g., High pitch, soft tone" required>
                            @error('bark_characteristics')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="response_voice_style">Response Voice Style *</label>
                            <input type="text" class="form-control @error('response_voice_style') is-invalid @enderror" 
                                   id="response_voice_style" name="response_voice_style" 
                                   value="{{ old('response_voice_style') }}" 
                                   placeholder="e.g., Calm, reassuring" required>
                            @error('response_voice_style')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="frequency">Frequency *</label>
                            <select class="form-select @error('frequency') is-invalid @enderror" 
                                    id="frequency" name="frequency" required>
                                <option value="">Select Frequency</option>
                                <option value="Frequent" {{ old('frequency') == 'Frequent' ? 'selected' : '' }}>Frequent</option>
                                <option value="Moderate" {{ old('frequency') == 'Moderate' ? 'selected' : '' }}>Moderate</option>
                                <option value="Infrequent" {{ old('frequency') == 'Infrequent' ? 'selected' : '' }}>Infrequent</option>
                            </select>
                            @error('frequency')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-green">Save Breed</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
