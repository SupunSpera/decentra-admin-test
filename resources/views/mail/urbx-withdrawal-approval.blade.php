@component('mail::message')
### Hello {{ $customer['email'] }} ,


Your Withdrawal Request Of {{ $withdrawal['amount'] }} URBX Has Been Approved.



Thanks, <br>
DECENTRA X Team
@endcomponent
