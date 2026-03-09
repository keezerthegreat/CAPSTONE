@extends('layouts.guest')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&display=swap');

    :root {
        --navy: #0f1f3d;
        --navy-mid: #162848;
        --gold: #c9a84c;
        --gold-light: #e8c97a;
        --cream: #f5f0e8;
        --white: #ffffff;
        --gray-soft: #8a95a3;
        --danger: #e05252;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'DM Sans', sans-serif;
        background-color: var(--navy);
    }

    .login-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background:
            radial-gradient(ellipse at 20% 50%, rgba(201,168,76,0.08) 0%, transparent 60%),
            radial-gradient(ellipse at 80% 20%, rgba(201,168,76,0.05) 0%, transparent 50%),
            linear-gradient(160deg, #0a1628 0%, #0f1f3d 50%, #071020 100%);
        padding: 2rem 1rem;
        position: relative;
        overflow: hidden;
    }

    /* Decorative background lines */
    .login-wrapper::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(201,168,76,0.04) 1px, transparent 1px),
            linear-gradient(90deg, rgba(201,168,76,0.04) 1px, transparent 1px);
        background-size: 60px 60px;
        pointer-events: none;
    }

    .login-card {
        position: relative;
        width: 100%;
        max-width: 460px;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(201,168,76,0.2);
        border-radius: 20px;
        padding: 2.5rem 2.5rem 2rem;
        backdrop-filter: blur(12px);
        box-shadow:
            0 0 0 1px rgba(255,255,255,0.05),
            0 40px 80px rgba(0,0,0,0.5),
            inset 0 1px 0 rgba(255,255,255,0.07);
        animation: fadeUp 0.6s ease both;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(24px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Seal / Badge */
    .seal {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
    }

    .seal-ring {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        border: 2px solid var(--gold);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        box-shadow: 0 0 20px rgba(201,168,76,0.25);
    }

    .seal-ring::before {
        content: '';
        position: absolute;
        inset: 4px;
        border-radius: 50%;
        border: 1px dashed rgba(201,168,76,0.4);
    }

    .seal-icon {
        font-size: 1.6rem;
        line-height: 1;
    }

    .login-title {
        font-family: 'DM Sans', sans-serif;
        font-size: 1.45rem;
        font-weight: 700;
        color: #ffffff !important;
        opacity: 1 !important;
        text-align: center;
        line-height: 1.3;
        letter-spacing: 0.01em;
    }

    .login-subtitle {
        font-size: 0.78rem;
        color: var(--gold);
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 0.18em;
        margin-top: 0.35rem;
        margin-bottom: 1.8rem;
        font-weight: 500;
    }

    /* Divider */
    .divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(201,168,76,0.3), transparent);
        margin-bottom: 1.8rem;
    }

    /* Form Fields */
    .field-group {
        margin-bottom: 1.1rem;
    }

    .field-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--gray-soft);
        margin-bottom: 0.45rem;
    }

    .field-input {
        width: 100%;
        padding: 0.7rem 1rem;
        background: rgba(255,255,255,0.04);
        border: 1.5px solid rgba(255,255,255,0.09);
        border-radius: 10px;
        color: var(--white);
        font-family: 'DM Sans', sans-serif;
        font-size: 0.92rem;
        outline: none;
        transition: border-color 0.2s, background 0.2s;
    }

    .field-input::placeholder {
        color: rgba(255,255,255,0.2);
    }

    .field-input:focus {
        border-color: var(--gold);
        background: rgba(201,168,76,0.05);
    }

    .password-wrapper {
        position: relative;
    }

    .password-wrapper .field-input {
        padding-right: 2.8rem;
    }

    .toggle-pw {
        position: absolute;
        right: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: var(--gray-soft);
        font-size: 1rem;
        line-height: 1;
        padding: 0.25rem;
        transition: color 0.2s;
    }

    .toggle-pw:hover { color: var(--gold); }

    /* Error */
    .error-box {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        padding: 0.7rem 1rem;
        background: rgba(224,82,82,0.1);
        border: 1px solid rgba(224,82,82,0.3);
        border-radius: 8px;
        color: #f08080;
        font-size: 0.82rem;
        margin-bottom: 1.2rem;
    }

    /* Submit Button */
    .btn-submit {
        width: 100%;
        padding: 0.82rem;
        margin-top: 1rem;
        background: linear-gradient(135deg, var(--gold) 0%, #a87c2a 100%);
        color: var(--navy);
        font-family: 'DM Sans', sans-serif;
        font-size: 0.9rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
        box-shadow: 0 4px 20px rgba(201,168,76,0.3);
    }

    .btn-submit:hover {
        opacity: 0.92;
        transform: translateY(-1px);
        box-shadow: 0 6px 24px rgba(201,168,76,0.45);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    /* Footer note */
    .login-footer {
        text-align: center;
        margin-top: 1.5rem;
        font-size: 0.72rem;
        color: rgba(138,149,163,0.6);
        letter-spacing: 0.04em;
    }
</style>

<div class="login-wrapper">
    <div class="login-card">

        {{-- Seal --}}
        <div class="seal">
            <div class="seal-ring">
                <span class="seal-icon">🏛️</span>
            </div>
        </div>

        <h1 class="login-title">Barangay Management<br>System</h1>
        <p class="login-subtitle">Official Portal</p>

        <div class="divider"></div>

        {{-- Error --}}
        @if ($errors->any())
            <div class="error-box">
                <span>⚠</span>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            {{-- Email --}}
            <div class="field-group">
                <label class="field-label" for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="you@barangay.gov.ph"
                    required
                    class="field-input">
            </div>

            {{-- Password --}}
            <div class="field-group">
                <label class="field-label" for="password">Password</label>
                <div class="password-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        required
                        class="field-input">
                    <button type="button" class="toggle-pw" onclick="togglePassword()" aria-label="Toggle password">
                        <span id="pw-icon">👁</span>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                Sign In
            </button>

        </form>

        <p class="login-footer">Barangay Information System &mdash; Authorized Personnel Only</p>

    </div>
</div>

<script>
function togglePassword() {
    const pw = document.getElementById('password');
    const icon = document.getElementById('pw-icon');
    if (pw.type === 'password') {
        pw.type = 'text';
        icon.textContent = '🙈';
    } else {
        pw.type = 'password';
        icon.textContent = '👁';
    }
}
</script>

@endsection