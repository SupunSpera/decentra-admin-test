@component('mail::message')
### Hello {{ $institute['first_name'] }} {{ $institute['last_name'] }},

Your Institute Registration Request Has Been Approved.

Please use the button below to log in to your account.

@component('mail::button', ['url' => env('PUBLIC_URL').'/login', 'color' => 'primary'])
Log in to your account
@endcomponent

If the above link doesn't work, you can manually copy and paste the following URL into your browser:
<a href="{{ env('PUBLIC_URL').'/login' }}" target="_blank">{{ env('PUBLIC_URL').'/login' }}</a>

Thanks, <br>
DECENTRA X Team
@endcomponent
