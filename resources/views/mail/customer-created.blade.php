@component('mail::message')
### Hello {{ $mailData['first_name'] }} {{ $mailData['last_name'] }},

Your Have Successfully Registered With DECENTRA X.

Your Email is:
**{{ $mailData['email'] }}**

Your temporary password is:
**{{ $password }}**

Please update this password once you log in to your account.

@component('mail::button', ['url' => env('PUBLIC_URL').'/login'])
Log in to your account
@endcomponent

If the above link doesn't work, use the following URL:
<a href="{{ env('PUBLIC_URL').'/login' }}" target="_blank">{{ env('PUBLIC_URL').'/login' }}</a>

Thanks, <br>
DECENTRA X Team
@endcomponent
