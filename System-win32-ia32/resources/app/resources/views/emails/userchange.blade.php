@component('mail::message')
    # Hello {{ $user->name }}

    Your email has been change please confirm the your link below.

    @component('mail::button', ['url' => route('verify',$user->verification_token)])
        Varify user
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
