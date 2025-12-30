@component('mail::message')
### Hello {{ $customer['email'] }} ,
{{-- {{ $mailData['last_name'] }}, --}}

Your Have Achieved {{ $milestone['name'] }}.



Thanks, <br>
DECENTRA X Team
@endcomponent
