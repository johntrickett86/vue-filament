@component('mail::message')
# Let's get started!

You have been invited to join {{ config('app.name') }}.

@component('mail::button', ['url' => $acceptUrl])
    Set up your account
@endcomponent

If you don't think this invitation was meant for you, please ignore this email.

@endcomponent
