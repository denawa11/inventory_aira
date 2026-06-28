<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-color: #4e3f9e;
        }

        body {
            background: #f4f6f9;
        }

        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: var(--sidebar-color);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
        }

        .sidebar .brand {
            padding: 18px 20px;
            color: white;
            font-size: 1.1rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar .user-info {
            padding: 15px 20px;
            color: white;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar .user-info img {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
        }

        .sidebar .nav-label {
            padding: 12px 20px 4px;
            color: rgba(255, 255, 255, 0.45);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 9px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: 0.2s;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 600;
        }

        .sidebar .nav-link i {
            width: 18px;
            text-align: center;
        }

        .main-content {
            margin-left: 240px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: white;
            padding: 12px 25px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .topbar .page-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #333;
        }

        .page-header {
            background: linear-gradient(135deg, #4e3f9e, #6c5ce7);
            padding: 18px 25px;
            color: white;
        }

        .page-header h5 {
            margin: 0;
            font-weight: 600;
        }

        .content {
            padding: 25px;
            flex: 1;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .stat-card {
            border-radius: 10px;
            padding: 20px;
            color: white;
        }

        .avatar-circle {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: var(--sidebar-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .dropdown-toggle::after {
            display: none;
        }

        /* ===== TOAST NOTIFICATIONS ===== */
        .toast-container-custom {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
            pointer-events: none;
        }

        .toast-notif {
            min-width: 300px;
            max-width: 400px;
            border-radius: 14px;
            padding: 0.9rem 1.1rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.18);
            pointer-events: all;
            animation: toastIn 0.35s cubic-bezier(.34, 1.56, .64, 1) forwards;
            position: relative;
            overflow: hidden;
        }

        .toast-notif::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: rgba(255, 255, 255, 0.5);
            animation: toastProgress var(--toast-duration, 3500ms) linear forwards;
        }

        .toast-notif.toast-success {
            background: #1a7f4b;
            color: white;
        }

        .toast-notif.toast-error {
            background: #c0392b;
            color: white;
        }

        .toast-notif.toast-warning {
            background: #d97706;
            color: white;
        }

        .toast-notif.toast-info {
            background: #4e3f9e;
            color: white;
        }

        .toast-notif.toast-hide {
            animation: toastOut 0.3s ease forwards;
        }

        .toast-icon {
            font-size: 1.15rem;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .toast-body {
            flex: 1;
        }

        .toast-title {
            font-weight: 700;
            font-size: 0.8rem;
            opacity: 0.85;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1px;
        }

        .toast-msg {
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .toast-close {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.7);
            cursor: pointer;
            font-size: 1rem;
            padding: 0;
            flex-shrink: 0;
            transition: color 0.2s;
        }

        .toast-close:hover {
            color: white;
        }

        @keyframes toastIn {
            from {
                opacity: 0;
                transform: translateX(60px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }

        @keyframes toastOut {
            from {
                opacity: 1;
                transform: translateX(0);
                max-height: 100px;
                margin-bottom: 0;
            }

            to {
                opacity: 0;
                transform: translateX(60px);
                max-height: 0;
                margin-bottom: -0.6rem;
            }
        }

        @keyframes toastProgress {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
            <i class=""></i> Inventory App
        </div>
        <div class="user-info">
            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=ffffff&color=4e3f9e&bold=true"
                alt="avatar">
            <div>
                <div style="font-size:0.85rem;font-weight:600">{{ auth()->user()->name }}</div>
                <div style="font-size:0.75rem;opacity:0.7">Administrator</div>
            </div>
        </div>

        {{-- Dashboard --}}
        <div class="mt-1">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </div>

        {{-- Master --}}
        <div class="nav-label">Master</div>
        <a href="{{ route('barangs.index') }}" class="nav-link {{ request()->routeIs('barangs.*') ? 'active' : '' }}">
            <i class="fas fa-box"></i> Barang
        </a>

        {{-- Transaksi --}}
        <div class="nav-label">Transaksi</div>
        <a href="{{ route('barang-masuk.index') }}"
            class="nav-link {{ request()->routeIs('barang-masuk.*') ? 'active' : '' }}">
            <i class="fas fa-sign-in-alt"></i> Barang Masuk
        </a>
        <a href="{{ route('barang-keluar.index') }}"
            class="nav-link {{ request()->routeIs('barang-keluar.*') ? 'active' : '' }}">
            <i class="fas fa-sign-out-alt"></i> Barang Keluar
        </a>

        {{-- Pengaturan --}}
        <div class="nav-label">Pengaturan</div>
        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Manajemen User
        </a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <span class="page-title">
                @yield('title', 'Dashboard')
            </span>
            <div class="dropdown">
                <button class="btn btn-light btn-sm dropdown-toggle d-flex align-items-center gap-2" type="button"
                    data-bs-toggle="dropdown">
                    <div class="avatar-circle">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <span style="font-size:0.85rem">{{ auth()->user()->name }}</span>
                    <i class="fas fa-chevron-down" style="font-size:0.7rem"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>


        <!-- Content -->
        <div class="content">
            <!-- Toast Container -->
            <div class="toast-container-custom" id="toastContainer"></div>

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Toast Data dari Session --}}
    @if(session('success'))
        <script>window.__toasts = window.__toasts || []; window.__toasts.push({ type: 'success', msg: {{ Js::from(session('success')) }}});</script>
    @endif
    @if(session('error'))
        <script>window.__toasts = window.__toasts || []; window.__toasts.push({ type: 'error', msg: {{ Js::from(session('error')) }}});</script>
    @endif
    @if(session('warning'))
        <script>window.__toasts = window.__toasts || []; window.__toasts.push({ type: 'warning', msg: {{ Js::from(session('warning')) }}});</script>
    @endif
    @if(session('info'))
        <script>window.__toasts = window.__toasts || []; window.__toasts.push({ type: 'info', msg: {{ Js::from(session('info')) }}});</script>
    @endif

    <script>
        const TOAST_CONFIG = {
            success: { icon: 'fa-circle-check', title: 'Berhasil' },
            error: { icon: 'fa-circle-xmark', title: 'Gagal' },
            warning: { icon: 'fa-triangle-exclamation', title: 'Perhatian' },
            info: { icon: 'fa-circle-info', title: 'Info' },
        };

        function showToast(type, msg, duration = 3500) {
            const cfg = TOAST_CONFIG[type] || TOAST_CONFIG.info;
            const container = document.getElementById('toastContainer');
            const el = document.createElement('div');
            el.className = `toast-notif toast-${type}`;
            el.style.setProperty('--toast-duration', duration + 'ms');
            el.innerHTML = `
            <span class="toast-icon"><i class="fas ${cfg.icon}"></i></span>
            <div class="toast-body">
                <div class="toast-title">${cfg.title}</div>
                <div class="toast-msg">${msg}</div>
            </div>
            <button class="toast-close" onclick="dismissToast(this.closest('.toast-notif'))">
                <i class="fas fa-xmark"></i>
            </button>`;
            container.appendChild(el);
            setTimeout(() => dismissToast(el), duration);
        }

        function dismissToast(el) {
            if (!el || el.classList.contains('toast-hide')) return;
            el.classList.add('toast-hide');
            setTimeout(() => el.remove(), 320);
        }

        document.addEventListener('DOMContentLoaded', () => {
            (window.__toasts || []).forEach(t => showToast(t.type, t.msg));
        });
    </script>
    @stack('scripts')
</body>

</html>