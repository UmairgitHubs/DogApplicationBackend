<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        DogTranslation.com
    </div>

    <div class="sidebar-nav">
        <div class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                Dashboard Overview
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                User Management
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.breeds') }}" class="nav-link {{ request()->routeIs('admin.breeds') ? 'active' : '' }}">
                <i class="fas fa-paw"></i>
                Dog Breed Management
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.emotions') }}" class="nav-link {{ request()->routeIs('admin.emotions') ? 'active' : '' }}">
                <i class="fas fa-desktop"></i>
                AI emotions Monitoring
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.voice-response') }}" class="nav-link {{ request()->routeIs('admin.voice-response') ? 'active' : '' }}">
                <i class="fas fa-microphone"></i>
                Voice Response Management
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.audio-logs') }}" class="nav-link {{ request()->routeIs('admin.audio-logs') ? 'active' : '' }}">
                <i class="fas fa-microphone-alt"></i>
                Audio Logs
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.history') }}" class="nav-link {{ request()->routeIs('admin.history') ? 'active' : '' }}">
                <i class="fas fa-redo"></i>
                History Module
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.multilingual') }}" class="nav-link {{ request()->routeIs('admin.multilingual') ? 'active' : '' }}">
                <i class="fas fa-globe"></i>
                Multilingual Configuration
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                App Settings
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.payments') }}" class="nav-link {{ request()->routeIs('admin.payments') ? 'active' : '' }}">
                <i class="fas fa-credit-card"></i>
                Payment & Subscription
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.analytics') }}" class="nav-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i>
                Advanced Analytics
            </a>
        </div>
    </div>

    <div class="sidebar-footer">
        <a href="{{ route('admin.logout') }}" class="nav-link">
            <i class="fas fa-arrow-left"></i>
            Logout
        </a>
    </div>
</nav>

<!-- Sidebar Toggle Button -->
<button class="sidebar-toggle" id="sidebarToggle">
    <i class="fas fa-bars"></i>
</button>

<style>
    :root {
        --primary-green: #58A509;
        --dark-bg: #1f2937;
        --sidebar-bg: #111827;
        --card-border: #e5e7eb;
        --text-muted: #6b7280;
    }

    /* Sidebar Styles */
    .sidebar {
        background-color: var(--sidebar-bg);
        min-height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        width: 280px;
        z-index: 1000;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    /* Sidebar responsive behavior */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.show {
            transform: translateX(0);
        }
    }

    .sidebar-brand {
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
        padding: 1.5rem;
        border-bottom: 1px solid #374151;
        flex-shrink: 0;
    }

    .sidebar-nav {
        padding: 1rem 0;
        flex: 1;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #374151 var(--sidebar-bg);
        max-height: calc(100vh - 140px);
    }

    .sidebar-nav::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-nav::-webkit-scrollbar-track {
        background: var(--sidebar-bg);
    }

    .sidebar-nav::-webkit-scrollbar-thumb {
        background: #374151;
        border-radius: 3px;
    }

    .sidebar-nav::-webkit-scrollbar-thumb:hover {
        background: #4b5563;
    }

    .nav-item {
        margin: 0.25rem 1rem;
    }

    .nav-link {
        color: #d1d5db;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.2s ease;
    }

    .nav-link:hover {
        color: white;
        background-color: #374151;
    }

    .nav-link.active {
        background-color: var(--primary-green);
        color: white;
    }

    .nav-link i {
        width: 20px;
        text-align: center;
    }

    .sidebar-footer {
        padding: 1rem;
        border-top: 1px solid #374151;
        flex-shrink: 0;
    }

    /* Toggle Button */
    .sidebar-toggle {
        display: block;
        position: fixed;
        top: 1rem;
        left: 1rem;
        z-index: 1001;
        background: var(--primary-green);
        border: none;
        color: white;
        padding: 0.75rem;
        border-radius: 0.5rem;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease;
    }

    .sidebar-toggle:hover {
        background-color: #4a8a08;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    /* Hide toggle button on desktop when sidebar is visible */
    @media (min-width: 769px) {
        .sidebar-toggle {
            display: none;
        }
    }
</style>

<script>
    // Sidebar Toggle
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');

        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                sidebar.classList.toggle('show');
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768 &&
                    !sidebar.contains(e.target) &&
                    !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('show');
                }
            });

            // Close sidebar on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                }
            });
        }
    });
</script>