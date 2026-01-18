@extends('layouts.auth')

@section('title', 'Đăng ký')

@section('content')
<h2 data-aos="fade-down" data-aos-duration="600">ĐĂNG KÝ</h2>
<p class="subtitle" data-aos="fade-down" data-aos-duration="600" data-aos-delay="100">Tạo tài khoản mới</p>

<!-- Error Messages -->
@if($errors->any())
    <div class="alert alert-danger" role="alert" data-aos="shake" data-aos-duration="500">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<!-- Register Form -->
<form method="POST" action="{{ route('register') }}" id="registerForm">
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
            oninput="this.value = this.value.toLowerCase().replace(/[^a-z0-9_]/g, '')"
        >
        <small class="form-text">Chỉ chứa chữ thường, số và dấu gạch dưới (không viết hoa, không cách, không ký tự đặc biệt)</small>
        @error('username')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Full Name -->
    <div class="mb-3" data-aos="fade-left" data-aos-duration="600" data-aos-delay="250">
        <label for="name" class="form-label">
            Họ và tên <span class="required">*</span>
        </label>
        <input 
            type="text" 
            class="form-control @error('name') is-invalid @enderror" 
            id="name" 
            name="name" 
            value="{{ old('name') }}"
            placeholder="Nhập họ và tên"
            required
        >
        @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Email -->
    <div class="mb-3" data-aos="fade-right" data-aos-duration="600" data-aos-delay="300">
        <label for="email" class="form-label">
            Gmail <span class="required">*</span>
        </label>
        <input 
            type="email" 
            class="form-control @error('email') is-invalid @enderror" 
            id="email" 
            name="email" 
            value="{{ old('email') }}"
            placeholder="example@gmail.com"
            required
        >
        @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Phone -->
    <div class="mb-3" data-aos="fade-left" data-aos-duration="600" data-aos-delay="350">
        <label for="phone" class="form-label">
            Số điện thoại <span class="required">*</span>
        </label>
        <input 
            type="tel" 
            class="form-control @error('phone') is-invalid @enderror" 
            id="phone" 
            name="phone" 
            value="{{ old('phone') }}"
            placeholder="Nhập số điện thoại (10-11 số)"
            required
            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
            maxlength="11"
        >
        <small class="form-text">Nhập số điện thoại (10-11 số)</small>
        @error('phone')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Password -->
    <div class="mb-3" data-aos="fade-right" data-aos-duration="600" data-aos-delay="400">
        <label for="password" class="form-label">
            Mật khẩu <span class="required">*</span>
        </label>
        <div class="password-wrapper">
            <input 
                type="password" 
                class="form-control @error('password') is-invalid @enderror" 
                id="password" 
                name="password" 
                placeholder="Nhập mật khẩu (tối thiểu 8 ký tự)"
                required
            >
            <span class="password-toggle" onclick="togglePassword('password')">
                <i class="bi bi-eye" id="eye-password"></i>
            </span>
        </div>
        <small class="form-text">Nhập mật khẩu (tối thiểu 8 ký tự)</small>
        @error('password')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Confirm Password -->
    <div class="mb-3" data-aos="fade-left" data-aos-duration="600" data-aos-delay="450">
        <label for="password_confirmation" class="form-label">
            Đặt lại mật khẩu <span class="required">*</span>
        </label>
        <div class="password-wrapper">
            <input 
                type="password" 
                class="form-control @error('password_confirmation') is-invalid @enderror" 
                id="password_confirmation" 
                name="password_confirmation" 
                placeholder="Nhập lại mật khẩu"
                required
            >
            <span class="password-toggle" onclick="togglePassword('password_confirmation')">
                <i class="bi bi-eye" id="eye-password_confirmation"></i>
            </span>
        </div>
        @error('password_confirmation')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Submit Button -->
    <div class="mb-3" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="500">
        <button type="submit" class="btn btn-primary-custom">
            <span>ĐĂNG KÝ</span>
        </button>
    </div>

    <!-- Login Link -->
    <div class="text-center mt-3" data-aos="fade-up" data-aos-duration="600" data-aos-delay="600">
        <p class="mb-0" style="color: var(--text-gray);">
            Đã có tài khoản? 
            <a href="{{ route('login') }}" class="auth-link">
                Đăng nhập ngay
            </a>
        </p>
    </div>
</form>
@endsection

@section('styles')
<style>
    /* Additional register-specific animations */
    #registerForm input:focus {
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

    /* Password strength indicator (visual feedback) */
    .password-strength {
        height: 4px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        margin-top: 0.5rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .password-strength-bar {
        height: 100%;
        width: 0;
        transition: all 0.3s ease;
        border-radius: 2px;
    }

    .password-strength-bar.weak {
        width: 33%;
        background: #dc2626;
    }

    .password-strength-bar.medium {
        width: 66%;
        background: #f59e0b;
    }

    .password-strength-bar.strong {
        width: 100%;
        background: #22c55e;
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

    // Password confirmation matching indicator với animation
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');

    passwordConfirmation.addEventListener('input', function() {
        if (password.value && passwordConfirmation.value) {
            if (password.value === passwordConfirmation.value) {
                this.style.borderColor = '#22c55e';
                this.style.boxShadow = '0 0 0 3px rgba(34, 197, 94, 0.2)';
            } else {
                this.style.borderColor = '#dc2626';
                this.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.2)';
            }
        } else {
            this.style.borderColor = '';
            this.style.boxShadow = '';
        }
    });

    // Reset border on focus
    passwordConfirmation.addEventListener('focus', function() {
        if (!this.classList.contains('is-invalid')) {
            this.style.borderColor = '';
            this.style.boxShadow = '';
        }
    });

    // Form submission với loading state
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const btn = this.querySelector('.btn-primary-custom');
        btn.classList.add('loading');
    });

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

    // Username validation feedback
    const usernameInput = document.getElementById('username');
    usernameInput.addEventListener('input', function() {
        const value = this.value;
        if (value.length > 0 && /^[a-z0-9_]+$/.test(value)) {
            this.style.borderColor = '#22c55e';
        } else if (value.length > 0) {
            this.style.borderColor = '#dc2626';
        } else {
            this.style.borderColor = '';
        }
    });
</script>
@endsection
