<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voice Response Management - DogTranslation.com Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-bg);
            margin-bottom: 1.5rem;
        }

        .mapping-table {
            width: 100%;
            border-collapse: collapse;
        }

        .mapping-table thead {
            background-color: #f9fafb;
            border-bottom: 2px solid var(--card-border);
        }

        .mapping-table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--dark-bg);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .mapping-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--card-border);
            color: var(--dark-bg);
        }

        .mapping-table tbody tr:hover {
            background-color: #f9fafb;
        }

        .action-link {
            color: var(--primary-green);
            text-decoration: none;
            margin-right: 1rem;
            font-weight: 500;
            cursor: pointer;
        }

        .action-link:hover {
            color: #4a8a08;
            text-decoration: underline;
        }

        .btn-primary-green {
            background-color: var(--primary-green);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
        }

        .btn-primary-green:hover {
            background-color: #4a8a08;
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

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--card-border);
            border-radius: 0.5rem;
            font-size: 1rem;
            color: var(--dark-bg);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(88, 165, 9, 0.1);
        }

        .integration-item {
            margin-bottom: 1.5rem;
        }

        .integration-name {
            font-weight: 600;
            color: var(--dark-bg);
            margin-bottom: 0.5rem;
        }

        .integration-link {
            color: var(--primary-green);
            text-decoration: none;
            font-size: 0.875rem;
        }

        .integration-link:hover {
            color: #4a8a08;
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
    @include('admin.partials.sidebar')

    <main class="main-content">
        <div class="page-header">
            <h1 class="page-title">Voice Response Management</h1>
        </div>

        <!-- Emotion to Response Mapping Section -->
        <div class="content-card">
            <h2 class="section-title">Emotion to Response Mapping</h2>
            <table class="mapping-table">
                <thead>
                    <tr>
                        <th>Emotion</th>
                        <th>Response Phrase</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mappings ?? [] as $mapping)
                    <tr>
                        <td><strong>{{ $mapping->emotion }}</strong></td>
                        <td>{{ $mapping->response_phrase }}</td>
                        <td>
                            <a href="#" class="action-link" onclick="editMapping({{ $mapping->id }})">Edit</a>
                            <a href="#" class="action-link" onclick="deleteMapping({{ $mapping->id }})">Delete</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">No mappings found. Please seed the database.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="margin-top: 1.5rem;">
                <button class="btn-primary-green" onclick="openAddMappingModal()">
                    <i class="fas fa-plus me-2"></i>Add New Mapping
                </button>
            </div>
        </div>

        <!-- TTS Settings Section -->
        <div class="content-card">
            <h2 class="section-title">TTS Settings</h2>
            <div class="form-group">
                <label class="form-label">Emotion</label>
                <input type="text" class="form-control" id="ttsEmotion" placeholder="Enter emotion">
            </div>
            <div class="form-group">
                <label class="form-label">Voice</label>
                <input type="text" class="form-control" id="ttsVoice" placeholder="Enter voice">
            </div>
            <button class="btn-primary-green" onclick="uploadTTSSample()">
                <i class="fas fa-upload me-2"></i>Upload TTS Sample
            </button>
        </div>

        <!-- Integrations Section -->
        <div class="content-card">
            <h2 class="section-title">Integrations</h2>
            <div class="integration-item">
                <div class="integration-name">Google TTS</div>
                <a href="#" class="integration-link" onclick="enableGoogleTTS()">Enable Google TTS integration</a>
            </div>
            <div class="integration-item">
                <div class="integration-name">Amazon Polly</div>
                <a href="#" class="integration-link" onclick="enableAmazonPolly()">Enable Amazon Polly integration</a>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editMapping(id) {
            alert('Edit mapping functionality - To be implemented for ID: ' + id);
        }

        function deleteMapping(id) {
            if (confirm('Are you sure you want to delete this mapping?')) {
                alert('Delete mapping functionality - To be implemented for ID: ' + id);
            }
        }

        function openAddMappingModal() {
            alert('Add new mapping functionality - To be implemented');
        }

        function uploadTTSSample() {
            const emotion = document.getElementById('ttsEmotion').value;
            const voice = document.getElementById('ttsVoice').value;
            
            if (!emotion || !voice) {
                alert('Please fill in both Emotion and Voice fields');
                return;
            }
            
            alert('Upload TTS Sample functionality - To be implemented\nEmotion: ' + emotion + '\nVoice: ' + voice);
        }

        function enableGoogleTTS() {
            alert('Enable Google TTS integration - To be implemented');
        }

        function enableAmazonPolly() {
            alert('Enable Amazon Polly integration - To be implemented');
        }
    </script>
</body>

</html>
