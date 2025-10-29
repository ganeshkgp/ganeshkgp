<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Message</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #16213e 100%);
            border-radius: 12px;
            padding: 30px;
            color: #ffffff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .logo {
            font-size: 2rem;
            font-weight: bold;
            background: linear-gradient(45deg, #00ffff, #ff00ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }
        .title {
            font-size: 1.5rem;
            margin: 0;
            color: #ffffff;
        }
        .content {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .field {
            margin-bottom: 15px;
        }
        .field-label {
            font-weight: 600;
            color: #00ffff;
            margin-bottom: 5px;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .field-value {
            color: rgba(255, 255, 255, 0.9);
            word-wrap: break-word;
        }
        .message-content {
            background: rgba(0, 0, 0, 0.2);
            padding: 15px;
            border-radius: 6px;
            border-left: 4px solid #00ffff;
            margin-top: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(45deg, #00ffff, #ff00ff);
            color: #000000;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 600;
            margin-top: 20px;
            transition: all 0.3s ease;
        }
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 255, 255, 0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">GK</div>
            <h1 class="title">New Contact Message Received</h1>
        </div>

        <div class="content">
            <div class="field">
                <div class="field-label">From</div>
                <div class="field-value">{{ $contactMessage->name }}</div>
            </div>

            <div class="field">
                <div class="field-label">Email</div>
                <div class="field-value">{{ $contactMessage->email }}</div>
            </div>

            @if($contactMessage->subject)
            <div class="field">
                <div class="field-label">Subject</div>
                <div class="field-value">{{ $contactMessage->subject }}</div>
            </div>
            @endif

            @if($contactMessage->phone)
            <div class="field">
                <div class="field-label">Phone</div>
                <div class="field-value">{{ $contactMessage->phone }}</div>
            </div>
            @endif

            @if($contactMessage->company)
            <div class="field">
                <div class="field-label">Company</div>
                <div class="field-value">{{ $contactMessage->company }}</div>
            </div>
            @endif

            <div class="field">
                <div class="field-label">Message</div>
                <div class="message-content">
                    {{ nl2br(e($contactMessage->message)) }}
                </div>
            </div>

            <div class="field">
                <div class="field-label">Received</div>
                <div class="field-value">{{ $contactMessage->created_at->format('F j, Y g:i A') }}</div>
            </div>

            @if($contactMessage->ip_address)
            <div class="field">
                <div class="field-label">IP Address</div>
                <div class="field-value">{{ $contactMessage->ip_address }}</div>
            </div>
            @endif
        </div>

        <div style="text-align: center;">
            <a href="{{ route('contact.messages.show', $contactMessage->id) }}" class="cta-button">
                View in Admin Panel
            </a>
        </div>

        <div class="footer">
            <p>This message was sent from your 3D portfolio website.</p>
            <p style="font-size: 0.8rem; margin-top: 10px;">
                If you didn't expect this message, please check your website security.
            </p>
        </div>
    </div>
</body>
</html>