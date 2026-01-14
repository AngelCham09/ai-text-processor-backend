<x-mail::message>
# Welcome to the future of writing, {{ $name }}! ✨

We’re excited to have you on board. Your new AI toolkit is ready to go!

We’ve designed a simple, powerful dashboard with **specialized AI tools** to help you generate content instantly. Whether you need to fix your grammar, expand an idea, or write from scratch, it’s all just one click away.

To start using your AI features, please confirm your email:

<x-mail::button :url="$url" color="primary">
Verify My Account
</x-mail::button>

**What’s inside your toolkit?**
* 🤖 **Direct AI Access** — High-quality AI generations.
* ⚡ **One-Click Features** — Specialized buttons for your specific needs.
* 🖋️ **Instant Drafts** — Get results in seconds, not minutes.

If you didn't sign up for this account, you can safely ignore this email.

Happy writing,<br>
The {{ config('app.name') }} Team
</x-mail::message>
