@extends('layouts.auth')

@section('title', 'Đăng nhập')

@section('content')
<h2 data-aos="fade-down" data-aos-duration="600">ĐĂNG NHẬP</h2>
<p class="subtitle" data-aos="fade-down" data-aos-duration="600" data-aos-delay="100">Chào mừng trở lại</p>

<!-- Success Message -->
@if(session('success'))
    <div class="alert alert-success" role="alert" data-aos="fade-down" data-aos-duration="500">
        {{ session('success') }}
    </div>
@endif

<!-- Error Messages -->
@if($errors->any())
    <div class="alert alert-danger" role="alert" data-aos="shake" data-aos-duration="500">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<!-- Login Form -->
<form method="POST" action="{{ route('login') }}" id="loginForm">
    @csrf
    
    <!-- Username -->
    <div class="mb-3" data-aos="fade-right" data-aos-duration="600" data-aos-delay="200">
        <label for="username" class="form-label">
            Tên đăng nhập <span class="required">*</span>
        </label>
        <input 
            type="text" 
            class="form-control @error('username') is-invalid @enderror" 
            id="username" 
            name="username" 
            value="{{ old('username') }}"
            placeholder="Nhập tên đăng nhập"
            required 
            autofocus
        >
        @error('username')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Password -->
    <div class="mb-3" data-aos="fade-left" data-aos-duration="600" data-aos-delay="300">
        <label for="password" class="form-label">
            Mật khẩu <span class="required">*</span>
        </label>
        <div class="password-wrapper">
            <input 
                type="password" 
                class="form-control @error('password') is-invalid @enderror" 
                id="password" 
                name="password" 
                placeholder="Nhập mật khẩu"
                required
            >
            <span class="password-toggle" onclick="togglePassword('password')">
                <i class="bi bi-eye" id="eye-password"></i>
            </span>
        </div>
        @error('password')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Remember Me -->
    <div class="mb-3 d-flex justify-content-between align-items-center" data-aos="fade-up" data-aos-duration="600" data-aos-delay="400">
        <div class="form-check">
            <input 
                class="form-check-input" 
                type="checkbox" 
                id="remember" 
                name="remember"
            >
            <label class="form-check-label" for="remember">
                Lưu đăng nhập
            </label>
        </div>
        <a href="#" class="auth-link">Quên mật khẩu?</a>
    </div>

    <!-- Submit Button -->
    <div class="mb-3" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="500">
        <button type="submit" class="btn btn-primary-custom">
            <span>ĐĂNG NHẬP</span>
        </button>
    </div>

    <!-- Register Link -->
    <div class="text-center mt-3" data-aos="fade-up" data-aos-duration="600" data-aos-delay="600">
        <p class="mb-0" style="color: var(--text-gray);">
            Chưa có tài khoản? 
            <a href="{{ route('register') }}" class="auth-link">
                Đăng ký ngay
            </a>
        </p>
    </div>
</form>
@endsection

@section('styles')
<style>
    /* Additional login-specific animations */
    #loginForm input:focus {
        animation: inputFocus 0.3s ease;
    }

    @keyframes inputFocus {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.02);
        }
        100% {
            transform: scale(1);
        }
    }

    /* Button loading state */
    .btn-primary-custom.loading {
        pointer-events: none;
        opacity: 0.7;
    }

    .btn-primary-custom.loading span::after {
        content: '...';
        animation: dots 1.5s steps(4, end) infinite;
    }

    @keyframes dots {
        0%, 20% {
            content: '.';
        }
        40% {
            content: '..';
        }
        60%, 100% {
            content: '...';
        }
    }
</style>
@endsection

@section('scripts')
<script>
    // Toggle password visibility với animation
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const eye = document.getElementById('eye-' + inputId);
        
        // Animation
        eye.style.transform = 'scale(0.8)';
        setTimeout(() => {
            if (input.type === 'password') {
                input.type = 'text';
                eye.classList.remove('bi-eye');
                eye.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                eye.classList.remove('bi-eye-slash');
                eye.classList.add('bi-eye');
            }
            eye.style.transform = 'scale(1.2)';
            setTimeout(() => {
                eye.style.transform = 'scale(1)';
            }, 200);
        }, 100);
    }

    // Form submission với loading state
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const btn = this.querySelector('.btn-primary-custom');
        btn.classList.add('loading');
    });

    // Auto-hide success messages với fade out
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-success');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);

    // Input focus animation
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.01)';
            this.parentElement.style.transition = 'transform 0.3s ease';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });
</script>
@endsection
