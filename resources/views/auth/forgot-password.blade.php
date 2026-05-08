<x-guest-layout>
    <h2 style="color:white;font-size:18px;font-weight:700;margin-bottom:6px;">Lupa Password?</h2>
    <p style="color:rgba(255,255,255,0.45);font-size:13px;margin-bottom:24px;line-height:1.6;">
        Masukkan email Anda dan kami akan mengirimkan link untuk mereset password.
    </p>

   @if (session('status'))
    <div style="background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.3);color:#86efac;border-radius:10px;padding:12px 14px;font-size:13px;margin-bottom:20px;">
        ✅ Link reset password telah dikirim ke email Anda. Silakan cek inbox atau folder spam.
    </div>
@endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div style="margin-bottom:20px;">
            <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:6px;">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="glass-input"
                   style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;"
                   placeholder="contoh@email.com">
            @error('email')
                <p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-primary"
                style="width:100%;padding:11px;border-radius:10px;font-size:14px;border:none;cursor:pointer;">
            Kirim Link Reset
        </button>

        <div style="text-align:center;margin-top:18px;">
            <a href="{{ route('login') }}"
               style="color:rgba(255,255,255,0.45);font-size:13px;text-decoration:none;">
                ← Kembali ke Login
            </a>
        </div>

    </form>
</x-guest-layout>