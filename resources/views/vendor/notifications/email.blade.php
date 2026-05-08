@component('mail::message')
# Halo!

Kami menerima permintaan reset password untuk akun Anda.

@component('mail::button', ['url' => $actionUrl, 'color' => 'blue'])
Reset Password
@endcomponent

Link reset password ini akan **kedaluwarsa dalam 60 menit**.

Jika Anda tidak merasa melakukan permintaan reset password, abaikan email ini.

Salam,
**{{ config('app.name') }}**
@endcomponent