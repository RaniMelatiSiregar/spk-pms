<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SPK PT PMS - Dashboard</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
      --sidebar-width: 260px;
      --header-height: 65px;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f5f6fa;
      color: #2c3e50;
    }

    /* Top Navigation */
    .top-navbar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: var(--header-height);
      background: white;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      z-index: 1000;
      display: flex;
      align-items: center;
      padding: 0 30px;
    }

    .navbar-brand {
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 22px;
      font-weight: 700;
      background: var(--primary-gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      text-decoration: none;
    }

    .navbar-brand i {
      width: 40px;
      height: 40px;
      background: var(--primary-gradient);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      -webkit-text-fill-color: white;
    }

    .navbar-right {
      margin-left: auto;
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .user-info {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 8px 15px;
      background: #f8f9fa;
      border-radius: 10px;
    }

    .user-avatar {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      background: var(--primary-gradient);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 600;
    }

    .user-details h6 {
      margin: 0;
      font-size: 14px;
      font-weight: 600;
      color: #2c3e50;
    }

    .user-details p {
      margin: 0;
      font-size: 12px;
      color: #7f8c8d;
    }

    .btn-logout {
      padding: 8px 20px;
      background: #e74c3c;
      color: white;
      border: none;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .btn-logout:hover {
      background: #c0392b;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
      color: white;
    }

    /* Sidebar */
    .sidebar {
      position: fixed;
      top: var(--header-height);
      left: 0;
      width: var(--sidebar-width);
      height: calc(100vh - var(--header-height));
      background: white;
      box-shadow: 2px 0 10px rgba(0,0,0,0.05);
      padding: 25px 0;
      overflow-y: auto;
      z-index: 999;
    }

    .sidebar::-webkit-scrollbar {
      width: 6px;
    }

    .sidebar::-webkit-scrollbar-thumb {
      background: #ddd;
      border-radius: 10px;
    }

    .sidebar-menu {
      list-style: none;
      padding: 0 15px;
      margin: 0;
    }

    .menu-title {
      padding: 0 15px;
      margin-bottom: 10px;
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      color: #95a5a6;
      letter-spacing: 1px;
    }

    .menu-item {
      margin-bottom: 5px;
    }

    .menu-link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 15px;
      color: #7f8c8d;
      text-decoration: none;
      border-radius: 10px;
      transition: all 0.3s ease;
      font-weight: 500;
      position: relative;
    }

    .menu-link i {
      width: 20px;
      text-align: center;
      font-size: 16px;
    }

    .menu-link:hover {
      background: #f8f9fa;
      color: #3498db;
      transform: translateX(5px);
    }

    .menu-link.active {
      background: var(--primary-gradient);
      color: white;
      box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
    }

    .menu-link.active::before {
      content: '';
      position: absolute;
      left: 0;
      top: 50%;
      transform: translateY(-50%);
      width: 4px;
      height: 60%;
      background: white;
      border-radius: 0 10px 10px 0;
    }

    /* Main Content */
    .main-content {
      margin-left: var(--sidebar-width);
      margin-top: var(--header-height);
      padding: 30px;
      min-height: calc(100vh - var(--header-height));
    }

    /* Alert Styles */
    .alert {
      border-radius: 12px;
      border: none;
      padding: 15px 20px;
      margin-bottom: 25px;
      display: flex;
      align-items: center;
      gap: 12px;
      animation: slideDown 0.4s ease;
    }

    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .alert-success {
      background: #d4edda;
      color: #155724;
    }

    .alert-danger {
      background: #f8d7da;
      color: #721c24;
    }

    .alert i {
      font-size: 20px;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
      }

      .sidebar.show {
        transform: translateX(0);
      }

      .main-content {
        margin-left: 0;
        padding: 20px;
      }

      .user-details {
        display: none;
      }
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
      width: 10px;
    }

    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
      background: #3498db;
      border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: #2980b9;
    }
  </style>
</head>
<body>
  <!-- Top Navbar -->
  <nav class="top-navbar">
    <a href="{{ route('dashboard') }}" class="navbar-brand">
      <i class="fas fa-chart-line"></i>
      <span>SPK PT PMS</span>
    </a>

    <div class="navbar-right">
      <div class="user-info">
        <div class="user-avatar">
          {{ strtoupper(substr(session('admin_name', 'A'), 0, 1)) }}
        </div>
        <div class="user-details">
          <h6>{{ session('admin_name', 'Admin') }}</h6>
          <p>Administrator</p>
        </div>
      </div>
      <a href="{{ route('logout') }}" class="btn-logout">
        <i class="fas fa-sign-out-alt"></i>
        Logout
      </a>
    </div>
  </nav>

  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="menu-title">Main Menu</div>
    <ul class="sidebar-menu">
      <li class="menu-item">
        <a href="{{ route('dashboard') }}" class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>
    </ul>

    <div class="menu-title" style="margin-top: 20px;">Data Master</div>
    <ul class="sidebar-menu">
      <li class="menu-item">
        <a href="{{ route('kriteria.index') }}" class="menu-link {{ request()->routeIs('kriteria.*') ? 'active' : '' }}">
          <i class="fas fa-list-check"></i>
          <span>Data Kriteria</span>
        </a>
      </li>
      <li class="menu-item">
        <a href="{{ route('supplier.index') }}" class="menu-link {{ request()->routeIs('supplier.*') ? 'active' : '' }}">
          <i class="fas fa-users"></i>
          <span>Data Alternatif</span>
        </a>
      </li>
    </ul>

   <div class="menu-title" style="margin-top: 20px;">SPK System</div>
<ul class="sidebar-menu">

  <li class="menu-item">
    <a href="{{ route('spk.compute') }}" class="menu-link {{ request()->routeIs('spk.compute') ? 'active' : '' }}">
      <i class="fas fa-calculator"></i>
      <span>Perhitungan SMART</span>
    </a>
  </li>

  <li class="menu-item">
    <a href="{{ route('spk.result') }}" class="menu-link {{ request()->routeIs('spk.result') ? 'active' : '' }}">
      <i class="fas fa-chart-bar"></i>
      <span>Hasil Perankingan</span>
    </a>
  </li>

  <li class="menu-item">
    <a href="{{ route('spk.history') }}" class="menu-link {{ request()->routeIs('spk.history') ? 'active' : '' }}">
      <i class="fas fa-clock-rotate-left"></i>
      <span>History Perhitungan</span>
    </a>
  </li>

  <li class="menu-item">
    <a href="{{ route('periode.index') }}" class="menu-link {{ request()->routeIs('periode.*') ? 'active' : '' }}">
      <i class="fas fa-calendar-alt"></i>
      <span>Data Periode</span>
    </a>
  </li>

</ul>

</aside>

  <!-- Main Content -->
  <main class="main-content">
    @if(session('success'))
      <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
      </div>
    @endif

    @yield('content')
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  @stack('scripts')

  <script>
    // Auto dismiss alerts after 5 seconds
    setTimeout(() => {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(alert => {
        alert.style.transition = 'all 0.3s ease';
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-20px)';
        setTimeout(() => alert.remove(), 300);
      });
    }, 5000);

    // Mobile menu toggle
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    
    if (menuToggle) {
      menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('show');
      });
    }
  </script>
</body>
</html>