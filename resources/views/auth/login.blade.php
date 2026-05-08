<x-guest-layout>
    <h2 style="color:white;font-size:18px;font-weight:700;margin-bottom:6px;">Selamat Datang</h2>
    <p style="color:rgba(255,255,255,0.45);font-size:13px;margin-bottom:24px;">Masuk ke akun Anda untuk melanjutkan</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div style="margin-bottom:18px;">
            <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:6px;">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="glass-input"
                   style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;"
                   placeholder="">
            @error('email')<p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>@enderror
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:6px;">Password</label>
            <input id="password" type="password" name="password" required
                   class="glass-input"
                   style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;"
                   placeholder="">
            @error('password')<p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>@enderror
        </div>
        <div style="text-align:right;margin-bottom:4px;margin-top:-10px;">
    <a href="{{ route('password.request') }}"
       style="color:rgba(255,255,255,0.45);font-size:12px;text-decoration:none;">
        Lupa Password?
    </a>
</div>
        <div style="display:flex;align-items:center;margin-bottom:24px;">
            <input id="remember_me" type="checkbox" name="remember"
                   style="width:15px;height:15px;accent-color:#6366f1;cursor:pointer;">
            <label for="remember_me" style="margin-left:8px;font-size:13px;color:rgba(255,255,255,0.55);cursor:pointer;">Ingat saya</label>
        </div>

        <button type="submit" class="btn-primary"
                style="width:100%;padding:11px;border-radius:10px;font-size:14px;border:none;cursor:pointer;">
            Masuk
        </button>

    </form>
</x-guest-layout>
