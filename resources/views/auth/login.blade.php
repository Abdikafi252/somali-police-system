<!DOCTYPE html>
<html lang="so">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soo Gal - Somali Police System</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Outfit', sans-serif;
        overflow: hidden;
        background: linear-gradient(135deg, #2d5016 0%, #4a7c2c 50%, #5a8f3a 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    /* Animated Background Blur Effect */
    .bg-blur {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at 30% 50%, rgba(90, 143, 58, 0.4) 0%, transparent 50%),
                    radial-gradient(circle at 70% 50%, rgba(45, 80, 22, 0.4) 0%, transparent 50%);
        filter: blur(60px);
        animation: bgShift 15s ease-in-out infinite;
    }

    @keyframes bgShift {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(20px, -20px) scale(1.1); }
    }

    /* Login Card Container */
    .login-container {
        position: relative;
        z-index: 10;
        width: 420px;
        animation: slideIn 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* User Icon Circle */
    .user-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #2d5016 0%, #3d6622 100%);
        border-radius: 50%;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        z-index: 11;
        margin-bottom: -50px;
        border: 5px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        animation: pulse 2s ease-in-out infinite;
    }

    .user-icon i {
        font-size: 2.5rem;
        color: white;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
        }
    }

    /* Login Card */
    .login-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(20px);
        border-radius: 25px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        padding: 4rem 3rem 3rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3),
                    inset 0 1px 0 rgba(255, 255, 255, 0.4);
        animation: fadeIn 1s ease 0.3s both;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Form Groups */
    .form-group {
        margin-bottom: 1.5rem;
        animation: fadeInUp 0.6s ease both;
    }

    .form-group:nth-child(1) { animation-delay: 0.5s; }
    .form-group:nth-child(2) { animation-delay: 0.7s; }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .input-wrapper:focus-within {
        background: white;
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    .input-icon {
        width: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.05);
        height: 50px;
    }

    .input-icon i {
        color: #2d5016;
        font-size: 1.1rem;
    }

    .form-control {
        flex: 1;
        padding: 1rem 1.2rem;
        border: none;
        background: transparent;
        font-size: 0.95rem;
        color: #333;
        outline: none;
    }

    .form-control::placeholder {
        color: #999;
    }

    /* Remember Me & Forgot Password */
    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        animation: fadeInUp 0.6s ease 0.9s both;
    }

    .remember-me {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.9rem;
        cursor: pointer;
    }

    .remember-me input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .forgot-password {
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .forgot-password:hover {
        color: white;
        text-decoration: underline;
    }

    /* Login Button */
    .btn-login {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, #2d5016 0%, #3d6622 100%);
        border: none;
        border-radius: 8px;
        color: white;
        font-weight: 700;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.6s ease 1.1s both;
    }

    .btn-login::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .btn-login:hover::before {
        left: 100%;
    }

    .btn-login:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(45, 80, 22, 0.4);
    }

    .btn-login:active {
        transform: translateY(-1px);
    }

    /* Error Messages */
    .error-message {
        color: #ff6b6b;
        background: rgba(255, 107, 107, 0.1);
        padding: 0.5rem;
        border-radius: 5px;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        display: block;
    }

    /* Responsive */
    @media (max-width: 500px) {
        .login-container {
            width: 95%;
            margin: 0 1rem;
        }

        .login-card {
            padding: 3rem 2rem 2rem;
        }

        .user-icon {
            width: 80px;
            height: 80px;
            margin-bottom: -40px;
        }

        .user-icon i {
            font-size: 2rem;
        }
    }
</style>

<div class="bg-blur"></div>

<div class="login-container">
    <!-- User Icon -->
    <div class="user-icon">
        <i class="fa-solid fa-user"></i>
    </div>

    <!-- Login Card -->
    <div class="login-card">
        @if (session('status'))
            <div style="color: #4ade80; background: rgba(74, 222, 128, 0.1); padding: 0.75rem; border-radius: 8px; font-size: 0.9rem; margin-bottom: 1rem; border: 1px solid rgba(74, 222, 128, 0.3); text-align: center;">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <div class="input-wrapper">
                    <div class="input-icon">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <input type="email" name="email" class="form-control" placeholder="Email ID" required autofocus>
                </div>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <div class="input-wrapper">
                    <div class="input-icon">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
            </div>

            <div class="form-options">
                <label class="remember-me">
                    <input type="checkbox" name="remember">
                    <span>Remember me</span>
                </label>
                <a href="{{ route('password.request') }}" class="forgot-password">Forgot Password?</a>
            </div>

            <button type="submit" class="btn-login">
                Login
            </button>
        </form>
    </div>
</div>

</body>
</html>
