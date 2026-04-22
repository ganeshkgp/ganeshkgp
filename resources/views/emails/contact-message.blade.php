<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>New Contact Message</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .header { background: #111111; padding: 28px 32px; }
        .header h1 { color: #f0a500; margin: 0; font-size: 20px; }
        .header p { color: #888; margin: 4px 0 0; font-size: 13px; }
        .body { padding: 32px; }
        .field { margin-bottom: 20px; }
        .field label { display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #999; margin-bottom: 4px; }
        .field p { margin: 0; color: #222; font-size: 15px; line-height: 1.6; }
        .message-box { background: #f9f9f9; border-left: 3px solid #f0a500; padding: 16px; border-radius: 0 6px 6px 0; }
        .footer { background: #f9f9f9; padding: 18px 32px; border-top: 1px solid #eee; font-size: 12px; color: #aaa; text-align: center; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>📬 New Contact Message</h1>
            <p>Someone reached out via your portfolio contact form</p>
        </div>
        <div class="body">
            <div class="field">
                <label>Name</label>
                <p>{{ $contactMessage->name }}</p>
            </div>
            <div class="field">
                <label>Email</label>
                <p><a href="mailto:{{ $contactMessage->email }}" style="color:#f0a500;">{{ $contactMessage->email }}</a></p>
            </div>
            @if ($contactMessage->phone)
            <div class="field">
                <label>Phone</label>
                <p>{{ $contactMessage->phone }}</p>
            </div>
            @endif
            <div class="field">
                <label>Message</label>
                <div class="message-box">
                    <p>{{ $contactMessage->message }}</p>
                </div>
            </div>
            <div class="field">
                <label>Received</label>
                <p>{{ $contactMessage->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>
        <div class="footer">
            Sent from your portfolio site &mdash; reply directly to this email to respond to {{ $contactMessage->name }}.
        </div>
    </div>
</body>
</html>
