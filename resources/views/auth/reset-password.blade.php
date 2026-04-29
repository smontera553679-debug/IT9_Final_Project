<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Tour & Travel</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: clamp(16px, 4vw, 40px);
            background: linear-gradient(rgba(0,0,0,0.1), rgba(0,0,0,0.1)),
                        url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: clamp(28px, 6vw, 50px) clamp(20px, 5vw, 40px);
            border-radius: clamp(24px, 5vw, 40px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
            width: 100%;
            max-width: 460px;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.35);
        }

        h1 {
            margin: 0;
            font-size: clamp(24px, 6vw, 32px);
            color: #000;
            font-weight: 500;
            margin-bottom: 6px;
        }

        .subtitle {
            font-size: clamp(13px, 3.5vw, 16px);
            color: #333;
            margin: 0 0 clamp(24px, 5vw, 40px);
        }

        .input-group {
            text-align: left;
            background: white;
            border: 1px solid #ccc;
            border-radius: 15px;
            padding: 8px 15px;
            margin-bottom: 16px;
        }

        .input-group label {
            display: block;
            font-size: 10px;
            font-weight: bold;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .input-group input {
            width: 100%;
            border: none;
            outline: none;
            font-size: clamp(15px, 4vw, 18px);
            color: #555;
            padding: 2px 0;
            background: transparent;
        }

        .input-group input::placeholder { color: #aaa; }

        .input-group.has-toggle {
            position: relative;
        }

        .input-group.has-toggle input {
            padding-right: 36px;
        }

        .toggle-pw {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            width: auto;
            color: #aaa;
            display: flex;
            align-items: center;
            font-size: 0;
            transition: color 0.2s;
        }

        .toggle-pw:hover { color: #555; background: none; }
        .toggle-pw svg { width: 20px; height: 20px; }

        button {
            width: 100%;
            padding: clamp(12px, 3vw, 15px);
            background-color: #5bc0de;
            color: white;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: clamp(16px, 4.5vw, 20px);
            font-weight: 400;
            transition: background 0.3s ease;
            margin-top: 10px;
        }

        button:hover { background-color: #46b8da; }

        .footer-link {
            margin-top: clamp(24px, 6vw, 40px);
            display: block;
            color: #333;
            text-decoration: underline;
            font-size: clamp(12px, 3vw, 14px);
        }

        .error-msg {
            color: #d9534f;
            background: rgba(217,83,79,0.1);
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 13px;
            text-align: left;
        }

        .error-msg ul { margin: 0; padding-left: 20px; }
    </style>
</head>
<body>

<div class="login-card">
    <h1>Reset Password</h1>
    <p class="subtitle">Secure your account</p>

    @if($errors->any())
        <div class="error-msg">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <div class="input-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" placeholder="example@mail.com" required value="{{ old('email') }}">
        </div>

        <div class="input-group has-toggle">
            <label for="password">New Password</label>
            <input type="password" name="password" id="password" required>
            <button type="button" class="toggle-pw" onclick="togglePassword('password', this)" aria-label="Show password">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                </svg>
            </button>
        </div>

        <div class="input-group has-toggle">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>
            <button type="button" class="toggle-pw" onclick="togglePassword('password_confirmation', this)" aria-label="Show password">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                </svg>
            </button>
        </div>

        <button type="submit">Update Password</button>

        <a href="{{ route('login') }}" class="footer-link">Back to Login</a>
    </form>
</div>

<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
    btn.querySelector('svg').innerHTML = isHidden
        ? '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>'
        : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
}
</script>

</body>
</html>