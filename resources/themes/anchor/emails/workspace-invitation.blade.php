@component('mail::message')
{{ __('You have been invited to join the :workspace workspace!', ['workspace' => $invitation->workspace->name]) }}

{{ __('If you do not have an account, you may create one by clicking the button below. After creating an account, you may click the invitation acceptance button in this email to accept the workspace invitation:') }}

@component('mail::button', ['url' => route('register')])
{{ __('Create Account') }}
@endcomponent

{{ __('If you already have an account, you may accept this invitation by clicking the button below:') }}

@component('mail::button', ['url' => $acceptUrl])
{{ __('Accept Invitation') }}
@endcomponent

{{ __('If you did not expect to receive an invitation to this workspace, you may discard this email.') }}
@endcomponent
