<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a IT-Secure</title>
    <style>
        body.it-secure-email-body {
            margin: 0;
            padding: 0;
            background-color: #070b13;
            color: #f1f5f9;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            line-height: 1.6;
        }

        .email-wrapper {
            width: 100%;
            background-color: #070b13;
            padding: 40px 15px;
            box-sizing: border-box;
        }

        .email-container {
            max-width: 560px;
            margin: 0 auto;
            background-color: #111a2e;
            border: 1px solid #1e3a5f;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.45);
        }

        /* Cabecera NOC */
        .email-header {
            background-color: #0c1220;
            padding: 24px 32px;
            border-bottom: 1px solid #1e293b;
            text-align: center;
        }

        .brand-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 8px;
            background-color: #122444;
            border: 1px solid #38bdf8;
            color: #38bdf8;
            font-weight: 700;
            font-size: 16px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .brand-sub {
            display: block;
            margin-top: 8px;
            font-size: 11px;
            color: #8493a8;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        /* Cuerpo del Correo */
        .email-body {
            padding: 32px;
        }

        .welcome-title {
            margin: 0 0 12px 0;
            font-size: 20px;
            font-weight: 600;
            color: #f1f5f9;
        }

        .welcome-text {
            margin: 0 0 24px 0;
            font-size: 14px;
            color: #8493a8;
        }

        .credentials-box {
            background-color: #0c1220;
            border: 1px solid #1e293b;
            border-left: 4px solid #38bdf8;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 28px;
        }

        .credential-row {
            margin-bottom: 16px;
        }

        .credential-row-last {
            margin-bottom: 0;
        }

        .credential-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #8493a8;
            margin-bottom: 6px;
        }

        .credential-value {
            font-family: "Cascadia Code", Consolas, Monaco, "Courier New", monospace;
            font-size: 15px;
            font-weight: 600;
            color: #38bdf8;
            background-color: #111a2e;
            border: 1px solid #1e3a5f;
            padding: 6px 12px;
            border-radius: 4px;
            display: inline-block;
            word-break: break-all;
        }

        .btn-container {
            text-align: center;
            margin: 28px 0 10px 0;
        }

        .btn-access {
            display: inline-block;
            background-color: #2563eb;
            color: #ffffff !important;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            padding: 12px 28px;
            border-radius: 6px;
            border: 1px solid #38bdf8;
        }

        .security-note {
            font-size: 12px;
            color: #8493a8;
            border-top: 1px solid #1e293b;
            padding-top: 16px;
            margin-top: 24px;
            text-align: center;
        }

        .email-footer {
            background-color: #0c1220;
            border-top: 1px solid #1e293b;
            padding: 18px 32px;
            text-align: center;
            font-size: 12px;
            color: #8493a8;
        }
    </style>
</head>
<body class="it-secure-email-body">

<div class="email-wrapper">
    <div class="email-container">

        <div class="email-header">
            <span class="brand-badge">IT-SECURE</span>
            <span class="brand-sub">Sistema de Monitoreo y Gestión de Activos TI</span>
        </div>

        <div class="email-body">
            <h1 class="welcome-title">
                ¡Bienvenido(a), {{ $nombre ?? 'Usuario' }}!
            </h1>

            <p class="welcome-text">
                Tu cuenta ha sido registrada en la plataforma <strong style="color: #f1f5f9;">IT-Secure</strong>. A continuación se detallan tus credenciales para iniciar sesión:
            </p>

            <div class="credentials-box">
                <div class="credential-row">
                    <span class="credential-label">Correo Electrónico</span>
                    <span class="credential-value">{{ $email }}</span>
                </div>

                <div class="credential-row-last">
                    <span class="credential-label">Contraseña Temporal</span>
                    <span class="credential-value">{{ $password }}</span>
                </div>
            </div>

            <div class="btn-container">
                <a href="{{ url('/login') }}" class="btn-access">
                    Iniciar Sesión en IT-Secure
                </a>
            </div>

            <p class="security-note">
                Por seguridad, te recomendamos actualizar tu contraseña al ingresar por primera vez.
            </p>
        </div>

        <div class="email-footer">
            &copy; {{ date('Y') }} IT-Secure &mdash; Mensaje automático del sistema, por favor no responder.
        </div>

    </div>
</div>

</body>
</html>
