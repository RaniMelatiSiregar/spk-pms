<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Admin - SPK PMS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }

    .bg-animation {
      position: absolute;
      width: 100%;
      height: 100%;
      overflow: hidden;
    }

    .circle {
      position: absolute;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.1);
      animation: float 15s infinite ease-in-out;
    }

    .circle:nth-child(1) {
      width: 80px;
      height: 80px;
      top: 10%;
      left: 20%;
      animation-delay: 0s;
    }

    .circle:nth-child(2) {
      width: 120px;
      height: 120px;
      top: 60%;
      left: 80%;
      animation-delay: 2s;
    }

    .circle:nth-child(3) {
      width: 100px;
      height: 100px;
      top: 80%;
      left: 10%;
      animation-delay: 4s;
    }

    .circle:nth-child(4) {
      width: 60px;
      height: 60px;
      top: 30%;
      left: 70%;
      animation-delay: 1s;
    }

    @keyframes float {
      0%, 100% {
        transform: translateY(0) translateX(0);
        opacity: 0.3;
      }
      50% {
        transform: translateY(-50px) translateX(50px);
        opacity: 0.6;
      }
    }

    .login-container {
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 450px;
      padding: 20px;
    }

    .login-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      padding: 40px;
      animation: slideUp 0.6s ease-out;
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .logo-section {
      text-align: center;
      margin-bottom: 30px;
    }

    .logo-icon {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
      border-radius: 20px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 36px;
      color: white;
      margin-bottom: 15px;
      box-shadow: 0 10px 30px rgba(52, 152, 219, 0.4);
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0%, 100% {
        transform: scale(1);
      }
      50% {
        transform: scale(1.05);
      }
    }

    .logo-section h3 {
      color: #2c3e50;
      font-weight: 700;
      margin-bottom: 5px;
    }

    .logo-section p {
      color: #7f8c8d;
      font-size: 14px;
    }

    .form-group {
      position: relative;
      margin-bottom: 25px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      color: #2c3e50;
      font-weight: 600;
      font-size: 14px;
    }

    .input-wrapper {
      position: relative;
    }

    .input-icon {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #3498db;
      font-size: 18px;
    }

    .form-control {
      padding: 12px 15px 12px 45px;
      border: 2px solid #e0e0e0;
      border-radius: 10px;
      font-size: 15px;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-color: #3498db;
      box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
      outline: none;
    }

    .btn-login {
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
      border: none;
      border-radius: 10px;
      color: white;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 10px;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 30px rgba(52, 152, 219, 0.4);
    }

    .divider {
      text-align: center;
      margin: 25px 0;
      position: relative;
    }

    .divider::before {
      content: '';
      position: absolute;
      left: 0;
      top: 50%;
      width: 100%;
      height: 1px;
      background: #e0e0e0;
    }

    .divider span {
      background: rgba(255, 255, 255, 0.95);
      padding: 0 15px;
      position: relative;
      color: #999;
      font-size: 14px;
    }

    .default-credentials {
      background: #f8f9ff;
      padding: 15px;
      border-radius: 10px;
      border-left: 4px solid #3498db;
    }

    .default-credentials p {
      margin: 0;
      color: #555;
      font-size: 13px;
    }

    .default-credentials strong {
      color: #3498db;
    }

    .alert {
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 20px;
      animation: shake 0.5s ease;
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-10px); }
      75% { transform: translateX(10px); }
    }
  </style>
</head>
<body>
  <div class="bg-animation">
    <div class="circle"></div>
    <div class="circle"></div>
    <div class="circle"></div>
    <div class="circle"></div>
  </div>

  <div class="login-container">
    <div class="login-card">
      <div class="logo-section">
        <div class="logo-icon">
          <i class="fas fa-chart-line"></i>
        </div>
        <h3>SPK PT PMS</h3>
        <p>Sistem Pendukung Keputusan</p>
      </div>

      @if($errors->any())
        <div class="alert alert-danger">
          <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
        </div>
      @endif

      <form method="post" action="{{ route('login.post') }}">
        @csrf
        <div class="form-group">
          <label>Email Address</label>
          <div class="input-wrapper">
            <i class="fas fa-envelope input-icon"></i>
            <input type="email" name="email" class="form-control" placeholder="Masukkan email Anda" required>
          </div>
        </div>

        <div class="form-group">
          <label>Password</label>
          <div class="input-wrapper">
            <i class="fas fa-lock input-icon"></i>
            <input type="password" name="password" class="form-control" placeholder="Masukkan password Anda" required>
          </div>
        </div>

        <button type="submit" class="btn-login">
          <i class="fas fa-sign-in-alt"></i> Login
        </button>
      </form>

      <div class="divider">
        <span>Akun Default</span>
      </div>

      {{-- <div class="default-credentials">
        <p><strong>Email:</strong> admin@pms.local</p>
        <p><strong>Password:</strong> password123</p>
      </div>
    </div>
  </div> --}}

  <script>
    document.querySelectorAll('.form-control').forEach(input => {
      input.addEventListener('focus', function() {
        this.parentElement.style.transform = 'scale(1.02)';
      });
      input.addEventListener('blur', function() {
        this.parentElement.style.transform = 'scale(1)';
      });
    });
  </script>
</body>
</html>