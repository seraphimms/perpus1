<x-guest-layout>
    <h2 style="color:white;font-size:18px;font-weight:700;margin-bottom:6px;">Reset Password</h2>
    <p style="color:rgba(255,255,255,0.45);font-size:13px;margin-bottom:24px;">
        Masukkan password baru Anda di bawah ini.
    </p>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div style="margin-bottom:18px;">
            <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:6px;">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus
                   class="glass-input"
                   style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;">
            @error('email')
                <p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-bottom:18px;">
            <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:6px;">Password Baru</label>
            <input id="password" type="password" name="password" required
                   class="glass-input"
                   style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;"
                   placeholder="Minimal 8 karakter">
            @error('password')
                <p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-bottom:24px;">
            <label style="display:block;color:rgba(255,255,255,0.70);font-size:13px;font-weight:500;margin-bottom:6px;">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                   class="glass-input"
                   style="width:100%;border-radius:10px;padding:10px 14px;font-size:13.5px;box-sizing:border-box;"
                   placeholder="Ulangi password baru">
            @error('password_confirmation')
                <p style="margin-top:5px;font-size:12px;color:#fca5a5;">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-primary"
                style="width:100%;padding:11px;border-radius:10px;font-size:14px;border:none;cursor:pointer;">
            Reset Password
        </button>

    </form>
</x-guest-layout>