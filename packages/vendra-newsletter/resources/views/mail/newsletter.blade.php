<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $newsletter->subject }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #18181b;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f5; padding: 24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 8px; overflow: hidden;">
                    <tr>
                        <td style="padding: 32px;">
                            <h1 style="margin: 0 0 24px; font-size: 22px; line-height: 1.3;">{{ $newsletter->subject }}</h1>

                            <div style="font-size: 15px; line-height: 1.6;">
                                {!! $newsletter->content !!}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 24px 32px; border-top: 1px solid #e4e4e7; font-size: 12px; line-height: 1.5; color: #71717a;">
                            <p style="margin: 0;">
                                {{ __('vendra-newsletter::mail.footer_reason') }}
                            </p>
                            <p style="margin: 8px 0 0;">
                                <a href="{{ $unsubscribeUrl }}" style="color: #71717a;">{{ __('vendra-newsletter::mail.unsubscribe') }}</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
