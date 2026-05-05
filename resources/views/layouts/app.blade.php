{{-- Layout: Main application shell with sidebar navigation, topbar notifications, and dark theme support --}}
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finexa Banking — @yield('page_title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-bg:    #0A2540;
            --brand-blue:    #06101f;
            --brand-accent:  #00d4ff;
            --sidebar-width: 260px;
            --topbar-height: 64px;
        }
        
        [data-bs-theme="dark"] {
            --bs-body-bg: var(--primary-bg);
            --bs-body-color: #f8f9fa;
        }
        
        /* ── GLOBAL DARK THEME OVERRIDES ── */
        [data-bs-theme="dark"] .card,
        [data-bs-theme="dark"] .bg-white { 
            background: rgba(6, 16, 31, 0.5) !important; 
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.06) !important; 
        }
        [data-bs-theme="dark"] .text-dark { color: #f8f9fa !important; }
        [data-bs-theme="dark"] .table-light { background-color: rgba(255,255,255,0.04) !important; color: #fff !important; }
        [data-bs-theme="dark"] .bg-light { background-color: rgba(255,255,255,0.03) !important; color: #fff !important; }
        [data-bs-theme="dark"] .text-muted { color: rgba(255,255,255,0.5) !important; }
        [data-bs-theme="dark"] .text-secondary { color: rgba(255,255,255,0.7) !important; }
        [data-bs-theme="dark"] .border, 
        [data-bs-theme="dark"] .border-bottom, 
        [data-bs-theme="dark"] .border-top { border-color: rgba(255,255,255,0.08) !important; }
        
        [data-bs-theme="dark"] .dropdown-menu { background-color: var(--brand-blue); border: 1px solid rgba(255,255,255,0.08); box-shadow: 0 10px 40px rgba(0,0,0,0.5); }
        [data-bs-theme="dark"] .dropdown-item { color: #e2e8f0; }
        [data-bs-theme="dark"] .dropdown-item:hover { background-color: rgba(255,255,255,0.05); color: #fff; }
        
        [data-bs-theme="dark"] .card-header { background-color: transparent !important; border-bottom: 1px solid rgba(255,255,255,0.05) !important; }
        [data-bs-theme="dark"] .card-footer { background-color: transparent !important; border-top: 1px solid rgba(255,255,255,0.05) !important; }
        
        [data-bs-theme="dark"] .table { --bs-table-bg: transparent; --bs-table-color: #f8f9fa; --bs-table-border-color: rgba(255,255,255,0.05); }
        [data-bs-theme="dark"] .table-hover tbody tr:hover { background-color: rgba(255,255,255,0.03); color: #fff; }
        [data-bs-theme="dark"] .modal-content { background-color: var(--brand-blue); border: 1px solid rgba(255,255,255,0.1); }
        [data-bs-theme="dark"] .form-control, [data-bs-theme="dark"] .form-select { background-color: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: #fff; }
        [data-bs-theme="dark"] .form-control:focus, [data-bs-theme="dark"] .form-select:focus { background-color: rgba(255,255,255,0.08); border-color: var(--brand-accent); color: #fff; box-shadow: 0 0 0 0.25rem rgba(0, 212, 255, 0.25); }
        * { box-sizing: border-box; }
        body {
            background-color: var(--primary-bg);
            font-family: 'Inter', sans-serif;
            margin: 0;
            overflow-x: hidden;
            user-select: auto !important; /* Ensure everything is copyable */
        }

        /* ── SELECT OPTION VISIBILITY FIX ── */
        [data-bs-theme="dark"] select option {
            background-color: #06101f !important;
            color: #fff !important;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0; left: 0;
            background: var(--brand-blue);
            color: white;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            border-right: 1px solid rgba(255,255,255,0.05);
        }
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 1.4rem 1.5rem;
            text-decoration: none;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .sidebar-brand-icon {
            width: 36px; height: 36px;
            background: var(--brand-accent);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            color: var(--brand-blue);
            font-weight: 800;
            flex-shrink: 0;
        }
        .sidebar-brand-text {
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
            letter-spacing: 1px;
        }
        .sidebar nav { flex: 1; overflow-y: auto; padding: 0.75rem 0; }
        .nav-section-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: rgba(255,255,255,0.35);
            padding: 1rem 1.5rem 0.4rem;
            font-weight: 600;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 0.65rem 1.5rem;
            font-size: 0.88rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            border-left: 3px solid transparent;
            transition: all 0.18s ease;
            text-decoration: none;
        }
        .sidebar .nav-link i { font-size: 1rem; width: 18px; text-align: center; }
        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255,255,255,0.07);
            border-left-color: rgba(0,212,255,0.5);
        }
        .sidebar .nav-link.active {
            color: white;
            background: rgba(0,212,255,0.12);
            border-left-color: var(--brand-accent);
        }

        /* ── TOPBAR ── */
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; display: flex; flex-direction: column; }
        .top-navbar {
            height: var(--topbar-height);
            background: rgba(6, 16, 31, 0.85);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(255,255,255,0.07);
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .page-title-text { font-size: 1rem; font-weight: 600; color: #fff; }

        /* Profile Dropdown */
        .profile-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 50px;
            padding: 0.35rem 0.8rem 0.35rem 0.35rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .profile-btn:hover { background: rgba(255,255,255,0.07); border-color: rgba(255,255,255,0.2); }
        .profile-avatar {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, var(--brand-accent), #0099cc);
            color: var(--brand-blue);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem;
            font-weight: 800;
            flex-shrink: 0;
        }
        .profile-name { font-size: 0.85rem; font-weight: 500; color: #fff; }
        .profile-role { font-size: 0.7rem; color: rgba(255,255,255,0.6); }
        .dropdown-menu {
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 40px rgba(0,0,0,0.12);
            border-radius: 12px;
            padding: 0.5rem;
            min-width: 200px;
            animation: dropdownFade 0.15s ease;
        }
        @keyframes dropdownFade {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .dropdown-item {
            border-radius: 8px;
            padding: 0.55rem 0.85rem;
            font-size: 0.87rem;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.15s;
        }
        .dropdown-item:hover { background: #f0f4f8; color: var(--brand-blue); }
        .dropdown-item.text-danger:hover { background: #fff5f5; }
        .dropdown-divider { border-color: #e2e8f0; margin: 0.3rem 0; }

        /* ── CONTENT ── */
        .content-area { padding: 2rem; flex-grow: 1; }
        .card {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }
        .btn-brand { background: var(--brand-blue); color: white; }
        .btn-brand:hover { background: #07192a; color: white; }
        .text-accent { color: var(--brand-accent) !important; }

        /* ── ALERT TOAST ANIMATION ── */
        .alert { animation: slideInDown 0.3s ease; }
        @keyframes slideInDown {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <!-- ── SIDEBAR ── -->
    <div class="sidebar">
        <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}" class="sidebar-brand">
            <div class="sidebar-brand-icon"><i class="bi bi-bank2"></i></div>
            <span class="sidebar-brand-text">FINEXA</span>
        </a>

        <nav>
            @if(Auth::user()->isAdmin())
                {{-- ADMIN NAV --}}
                <div class="nav-section-label">Admin Panel</div>
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-grid-1x2"></i> Dashboard
                </a>
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="bi bi-people"></i> Manage Users
                </a>
                <a class="nav-link {{ request()->routeIs('admin.loans') ? 'active' : '' }}" href="{{ route('admin.loans') }}">
                    <i class="bi bi-cash-coin"></i> Loan Approvals
                </a>
                <a class="nav-link {{ request()->routeIs('admin.cards_accounts') ? 'active' : '' }}" href="{{ route('admin.cards_accounts') }}">
                    <i class="bi bi-credit-card"></i> Manage Cards & Accounts
                </a>
                <a class="nav-link {{ request()->routeIs('admin.support*') ? 'active' : '' }}" href="{{ route('admin.support') }}">
                    <i class="bi bi-headset"></i> Support Tickets
                </a>
                <a class="nav-link {{ request()->routeIs('admin.transactions') ? 'active' : '' }}" href="{{ route('admin.transactions') }}">
                    <i class="bi bi-arrow-left-right"></i> All Transactions
                </a>
            @else
                {{-- CUSTOMER NAV --}}
                <div class="nav-section-label">Main Menu</div>
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-grid-1x2"></i> Dashboard
                </a>
                <a class="nav-link {{ request()->routeIs('accounts.*') ? 'active' : '' }}" href="{{ route('accounts.index') }}">
                    <i class="bi bi-wallet2"></i> Accounts
                </a>
                <a class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}" href="{{ route('transactions.index') }}">
                    <i class="bi bi-arrow-left-right"></i> Transactions
                </a>
                <a class="nav-link {{ request()->routeIs('services.cards') ? 'active' : '' }}" href="{{ route('services.cards') }}">
                    <i class="bi bi-credit-card"></i> My Cards
                </a>
                <a class="nav-link {{ request()->routeIs('services.loans') ? 'active' : '' }}" href="{{ route('services.loans') }}">
                    <i class="bi bi-cash-coin"></i> Loans
                </a>
                <a class="nav-link {{ request()->routeIs('services.savings') ? 'active' : '' }}" href="{{ route('services.savings') }}">
                    <i class="bi bi-piggy-bank"></i> Savings Plans
                </a>
                <a class="nav-link {{ request()->routeIs('services.payments') ? 'active' : '' }}" href="{{ route('services.payments') }}">
                    <i class="bi bi-receipt"></i> Bill Payments
                </a>
                <div class="nav-section-label">More</div>
                <a class="nav-link {{ request()->routeIs('services.rewards') ? 'active' : '' }}" href="{{ route('services.rewards') }}">
                    <i class="bi bi-gift"></i> Rewards
                </a>
                <a class="nav-link {{ request()->routeIs('support.*') ? 'active' : '' }}" href="{{ route('support.index') }}">
                    <i class="bi bi-headset"></i> Support
                </a>
                <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                    <i class="bi bi-person-gear"></i> Profile
                </a>
            @endif
        </nav>
    </div>

    <!-- ── MAIN CONTENT ── -->
    <div class="main-content">

        <!-- Top Navbar -->
        <header class="top-navbar">
            <span class="page-title-text">
                <i class="bi bi-chevron-right text-muted me-1" style="font-size:0.75rem;"></i>
                @yield('page_title', 'Dashboard')
            </span>

            <div class="d-flex align-items-center gap-3">
                {{-- Notification Bell --}}
                <div class="dropdown">
                    <div class="position-relative" style="cursor:pointer;" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                        <i class="bi bi-bell fs-5 text-secondary"></i>
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" style="width:8px;height:8px;"></span>
                        @endif
                    </div>
                    <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 p-0" aria-labelledby="notificationDropdown" style="width: 320px; animation: dropdownFade 0.2s ease;">
                        <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light rounded-top-3">
                            <h6 class="mb-0 fw-bold">Notifications</h6>
                            @if(Auth::user()->unreadNotifications->count() > 0)
                                <form action="{{ route('notifications.read.all') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-link text-decoration-none p-0" style="font-size: 0.8rem;">Mark all as read</button>
                                </form>
                            @endif
                        </div>
                        <div style="max-height: 350px; overflow-y: auto;">
                            @forelse(Auth::user()->unreadNotifications as $notification)
                                <a href="{{ route('notifications.read', $notification->id) }}" class="dropdown-item p-3 border-bottom text-wrap" style="transition: background 0.2s;">
                                    <div class="d-flex gap-3">
                                        <div class="bg-{{ $notification->data['color'] ?? 'primary' }} bg-opacity-10 text-{{ $notification->data['color'] ?? 'primary' }} rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 35px; height: 35px;">
                                            <i class="bi {{ $notification->data['icon'] ?? 'bi-bell' }}"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold mb-1" style="font-size: 0.85rem;">{{ $notification->data['title'] ?? 'Notification' }}</div>
                                            <div class="text-muted mb-1" style="font-size: 0.75rem; line-height: 1.3;">{{ $notification->data['message'] ?? '' }}</div>
                                            <div class="text-secondary" style="font-size: 0.65rem;">{{ $notification->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="p-4 text-center text-muted">
                                    <i class="bi bi-bell-slash fs-3 d-block mb-2 text-light"></i>
                                    <span style="font-size: 0.85rem;">No new notifications</span>
                                </div>
                            @endforelse
                        </div>
                        <div class="p-2 text-center bg-light rounded-bottom-3 border-top">
                            <span class="text-muted" style="font-size: 0.75rem;">Showing unread notifications</span>
                        </div>
                    </div>
                </div>

                {{-- Profile Dropdown --}}
                <div class="dropdown">
                    <button class="profile-btn dropdown-toggle-no-caret" id="profileDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="profile-avatar">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="text-start">
                            <div class="profile-name">{{ Auth::user()->name ?? 'User' }}</div>
                            <div class="profile-role">{{ Auth::user()->isAdmin() ? 'Administrator' : 'Customer' }}</div>
                        </div>
                        <i class="bi bi-chevron-down text-muted ms-1" style="font-size:0.7rem;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        @if(!Auth::user()->isAdmin())
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person-circle text-primary"></i> My Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('dashboard') }}">
                                <i class="bi bi-grid text-secondary"></i> Dashboard
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        @endif
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        <main class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-dismiss alerts after 4 seconds
        document.querySelectorAll('.alert').forEach(el => {
            setTimeout(() => {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(el);
                bsAlert.close();
            }, 4000);
        });
    </script>
</body>
</html>
