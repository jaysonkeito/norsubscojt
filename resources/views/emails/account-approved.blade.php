@component('mail::message')
# You're approved!

Hi {{ $name }},

Good news — your **{{ $roleLabel }}** account on the OJT Tracker (NORSU Bayawan-Sta. Catalina Campus) has been approved. You can now log in and get started.

@component('mail::button', ['url' => route('login')])
Log In Now
@endcomponent

If you weren't expecting this, you can safely ignore this email.

Thanks,<br>
OJT Tracker — NORSU BSC
@endcomponent
