<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Facebook data deletion status title') }} · Blinest</title>
    <style>
        :root { color-scheme: dark; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: #0b1020;
            color: #f8fafc;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        .card {
            width: min(100%, 32rem);
            margin: 1.5rem;
            padding: 1.5rem;
            border: 1px solid rgb(255 255 255 / 0.1);
            border-radius: 1rem;
            background: rgb(255 255 255 / 0.04);
        }
        h1 { font-size: 1.25rem; margin: 0 0 0.75rem; }
        p { margin: 0.5rem 0; color: rgb(255 255 255 / 0.65); line-height: 1.5; }
        code {
            display: inline-block;
            margin-top: 0.75rem;
            padding: 0.35rem 0.55rem;
            border-radius: 0.5rem;
            background: rgb(255 255 255 / 0.08);
            color: #fde68a;
            font-size: 0.875rem;
        }
        .status {
            display: inline-block;
            margin-top: 0.5rem;
            padding: 0.25rem 0.6rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .status--pending { background: rgb(251 191 36 / 0.15); color: #fcd34d; }
        .status--deleted, .status--unlinked, .status--not_found { background: rgb(16 185 129 / 0.15); color: #6ee7b7; }
        .status--failed { background: rgb(239 68 68 / 0.15); color: #fca5a5; }
    </style>
</head>
<body>
    <main class="card">
        <h1>{{ __('Facebook data deletion status title') }}</h1>

        <span @class([
            'status',
            'status--' . $deletionRequest->action->value,
        ])>{{ __('Facebook data deletion status ' . $deletionRequest->action->value) }}</span>

        <p>{{ __('Facebook data deletion status intro') }}</p>

        @if ($deletionRequest->processed_at)
            <p>{{ __('Facebook data deletion processed at', ['date' => $deletionRequest->processed_at->timezone(config('app.timezone'))->format('d/m/Y H:i')]) }}</p>
        @endif

        <code>{{ $deletionRequest->confirmation_code }}</code>
    </main>
</body>
</html>
