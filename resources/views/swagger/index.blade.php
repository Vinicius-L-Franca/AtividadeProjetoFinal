<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instaclone API Docs</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5/swagger-ui.css">
    <style>
        :root {
            color-scheme: light;
            --bg: #f4efe8;
            --panel: rgba(255, 255, 255, 0.88);
            --text: #1f2937;
            --muted: #6b7280;
            --accent: #0f766e;
        }

        html, body {
            margin: 0;
            min-height: 100%;
            background:
                radial-gradient(circle at top left, rgba(15, 118, 110, 0.15), transparent 30%),
                radial-gradient(circle at top right, rgba(217, 119, 6, 0.12), transparent 24%),
                linear-gradient(180deg, #faf7f2 0%, #f4efe8 100%);
            color: var(--text);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .hero {
            max-width: 1180px;
            margin: 0 auto;
            padding: 32px 24px 18px;
        }

        .hero-card {
            background: var(--panel);
            border: 1px solid rgba(31, 41, 55, 0.08);
            border-radius: 24px;
            box-shadow: 0 24px 80px rgba(31, 41, 55, 0.08);
            padding: 24px 28px;
            backdrop-filter: blur(14px);
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 12px;
        }

        .eyebrow::before {
            content: '';
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: var(--accent);
            box-shadow: 0 0 0 6px rgba(15, 118, 110, 0.12);
        }

        h1 {
            margin: 0;
            font-size: clamp(30px, 4vw, 48px);
            line-height: 1.05;
            letter-spacing: -0.04em;
        }

        p {
            margin: 12px 0 0;
            max-width: 760px;
            color: var(--muted);
            font-size: 16px;
            line-height: 1.65;
        }

        .meta {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 18px;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            background: rgba(15, 118, 110, 0.08);
            color: #0f4c49;
            border: 1px solid rgba(15, 118, 110, 0.12);
            padding: 8px 12px;
            font-size: 13px;
            font-weight: 600;
        }

        #swagger-ui {
            max-width: 1180px;
            margin: 18px auto 40px;
            padding: 0 24px 32px;
        }

        .swagger-ui .topbar {
            display: none;
        }

        .swagger-ui .scheme-container,
        .swagger-ui .opblock,
        .swagger-ui .information-container,
        .swagger-ui .btn,
        .swagger-ui select,
        .swagger-ui input {
            border-radius: 16px !important;
        }

        .swagger-ui .opblock-summary {
            border-radius: 14px;
        }
    </style>
</head>
<body>
    <section class="hero">
        <div class="hero-card">
            <div class="eyebrow">Swagger UI</div>
            <h1>Instaclone API</h1>
            <p>Documentação interativa das rotas públicas e autenticadas do projeto. Use o botão Authorize para testar endpoints protegidos com token Bearer do Laravel Sanctum.</p>
            <div class="meta">
                <span class="pill">Base path: /api</span>
                <span class="pill">Auth: Bearer token</span>
                <span class="pill">Formato: OpenAPI 3.0.3</span>
            </div>
        </div>
    </section>

    <div id="swagger-ui"></div>

    <script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-bundle.js"></script>
    <script>
        window.addEventListener('load', () => {
            window.ui = SwaggerUIBundle({
                url: @json($specUrl),
                dom_id: '#swagger-ui',
                deepLinking: true,
                displayRequestDuration: true,
                docExpansion: 'none',
                filter: true,
                persistAuthorization: true,
                syntaxHighlight: {
                    activate: true,
                    theme: 'nord',
                },
            });
        });
    </script>
</body>
</html>