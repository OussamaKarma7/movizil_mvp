<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Universal Invest Strategy</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --bg-color: #f8fafc;
            --sidebar-bg: #1e293b;
            --sidebar-text: #94a3b8;
            --sidebar-active: #ffffff;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: #334155;
            margin: 0;
            overflow-x: hidden;
        }

        /* Layout Structure */
        #wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        #sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            color: #fff;
            flex-shrink: 0;
            transition: all 0.3s;
            z-index: 1000;
        }

        .sidebar-brand {
            padding: 24px 20px;
            font-size: 1.25rem;
            font-weight: 700;
            color: #fff;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            text-decoration: none;
            display: block;
            letter-spacing: 0.5px;
        }

        .sidebar-nav {
            list-style: none;
            padding: 20px 0;
            margin: 0;
        }

        .sidebar-nav li {
            padding: 4px 16px;
        }

        .sidebar-nav a {
            color: var(--sidebar-text);
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 10px 16px;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .sidebar-nav a:hover, .sidebar-nav a.active {
            background-color: rgba(255,255,255,0.08);
            color: var(--sidebar-active);
        }
        
        .sidebar-nav a.active {
            background-color: var(--primary-color);
        }
        .sidebar-nav a.active:hover {
            background-color: var(--primary-hover);
        }

        .sidebar-nav a i {
            width: 24px;
            font-size: 1.1rem;
            margin-right: 12px;
            opacity: 0.8;
        }

        .sidebar-nav a.active i {
            opacity: 1;
        }

        /* Main Content */
        #page-content-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            width: calc(100% - 260px);
            background-color: var(--bg-color);
        }

        .topbar {
            background: #fff;
            padding: 16px 32px;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
        }

        .main-content {
            padding: 32px;
            flex-grow: 1;
        }

        /* Card Styles */
        .card {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            margin-bottom: 24px;
            background-color: #fff;
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid var(--border-color);
            padding: 16px 24px;
            border-radius: 12px 12px 0 0 !important;
            font-weight: 600;
            color: #1e293b;
        }
        
        .card-body {
            padding: 24px;
        }

        /* Button Styles */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 500;
            padding: 8px 16px;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        /* Table Styles */
        .table {
            vertical-align: middle;
            margin-bottom: 0;
        }
        .table thead th {
            text-transform: uppercase;
            font-size: 0.75rem;
            color: #64748b;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border-color);
            padding: 12px 16px;
            background-color: #f8fafc;
            font-weight: 600;
        }
        .table tbody td {
            padding: 16px;
            color: #475569;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.95rem;
        }
        
        /* Form Styles */
        .form-label {
            font-weight: 500;
            color: #334155;
            font-size: 0.95rem;
        }
        .form-control, .form-select {
            border-color: #cbd5e1;
            padding: 10px 14px;
            font-size: 0.95rem;
            border-radius: 6px;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.25);
        }
    </style>
    @stack('styles')
</head>
<body>
    <div id="wrapper">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <!-- Topbar -->
            <div class="topbar">
                <h4 class="mb-0 text-dark fw-bold fs-5">@yield('title', 'Tableau de bord')</h4>
                <div class="user-profile d-flex align-items-center">
                    <span class="me-3 fw-medium text-secondary" style="font-size: 0.9rem;">
                        {{ auth()->user()->name ?? 'Utilisateur' }}
                        ({{ auth()->user()?->isAdmin() ? 'Admin' : 'Client' }})
                    </span>
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=4f46e5&color=fff" alt="Utilisateur" class="rounded-circle" width="36" height="36">
                    <form action="{{ route('logout') }}" method="POST" class="ms-3">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary">Se deconnecter</button>
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js for Dashboard Charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
</body>
</html>
