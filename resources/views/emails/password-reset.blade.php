<x-mail::message>
# Reset your password, {{ $name }} 🔐

We received a request to reset the password for your **{{ config('app.name') }}** account. No worries, it happens to the best of us!

To choose a new password and get back to your AI writing tools, simply click the button below:

<x-mail::button :url="$url" color="primary">
Reset My Password
</x-mail::button>

**Important Security Details:**
* 🛡️ **Safety:** If you did not request a password reset, no further action is required. Your account remains secure.
* 🚫 **Privacy:** For your security, never share this link with anyone.

Once you reset your password, you'll have full access back to your specialized AI buttons and instant drafting tools.

See you back in the dashboard soon!

Best regards,<br>
The {{ config('app.name') }} Team
</x-mail::message>
