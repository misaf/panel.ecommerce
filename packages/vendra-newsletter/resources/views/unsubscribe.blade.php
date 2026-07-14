<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), ['fa', 'ar', 'he'], true) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('vendra-newsletter::mail.unsubscribe') }}</title>
</head>
<body style="margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; background-color: #f4f4f5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #18181b;">
    <main style="max-width: 480px; width: 100%; margin: 16px; padding: 40px; background-color: #ffffff; border-radius: 8px; text-align: center;">
        <h1 style="margin: 0 0 12px; font-size: 20px;">
            {{ $subscriber ? __('vendra-newsletter::mail.unsubscribe_confirmed') : __('vendra-newsletter::mail.unsubscribe_unknown') }}
        </h1>
        <p style="margin: 0; font-size: 15px; line-height: 1.6; color: #52525b;">
            {{ $subscriber ? __('vendra-newsletter::mail.unsubscribe_confirmed_body') : __('vendra-newsletter::mail.unsubscribe_unknown_body') }}
        </p>
    </main>
</body>
</html>
