<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a IT-Secure</title>
</head>
<body style="margin: 0; padding: 0; background-color: #030712; color: #f8fafc; font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; -webkit-font-smoothing: antialiased; line-height: 1.6;">

<table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #030712; padding: 40px 15px;">
    <tr>
        <td align="center">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 580px; background-color: #111827; border: 1px solid rgba(255, 255, 255, 0.07); border-radius: 16px; overflow: hidden; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);">

                <tr>
                    <td align="center" style="background-color: #090e17; padding: 32px 32px; border-bottom: 1px solid rgba(255, 255, 255, 0.07);">
                        <div style="display: inline-block; padding: 8px 16px; border-radius: 10px; background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(56, 189, 248, 0.1)); border: 1px solid rgba(56, 189, 248, 0.2); color: #38bdf8; font-weight: 700; font-size: 16px; letter-spacing: 0.15em; text-transform: uppercase;">
                            IT-SECURE
                        </div>
                        <div style="margin-top: 12px; font-size: 11px; color: #94a3b8; font-weight: 500; letter-spacing: 0.1em; text-transform: uppercase;">
                            Protección & Mantenimiento IT
                        </div>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 40px 32px;">
                        <h1 style="margin: 0 0 16px 0; font-size: 22px; font-weight: 600; color: #f8fafc; letter-spacing: 0.02em;">
                            ¡Bienvenido(a), <span style="color: #38bdf8;">{{ $nombre ?? 'Usuario' }}</span>!
                        </h1>
                        <p style="margin: 0 0 32px 0; font-size: 15px; color: #94a3b8; font-weight: 300;">
                            Tu cuenta ha sido registrada en la plataforma <strong style="color: #f8fafc; font-weight: 500;">IT-Secure</strong>. A continuación se detallan tus credenciales de acceso seguro:
                        </p>

                        <div style="background-color: #030712; border: 1px solid rgba(255, 255, 255, 0.07); border-left: 3px solid #38bdf8; border-radius: 12px; padding: 24px; margin-bottom: 32px;">
                            <div style="margin-bottom: 20px;">
                                <span style="display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: #475569; margin-bottom: 8px;">Correo Electrónico</span>
                                <span style="font-family: 'Cascadia Code', Consolas, monospace; font-size: 15px; font-weight: 600; color: #f8fafc; background-color: #111827; border: 1px solid rgba(255, 255, 255, 0.05); padding: 8px 14px; border-radius: 6px; display: inline-block; word-break: break-all;">{{ $email }}</span>
                            </div>
                            <div>
                                <span style="display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: #475569; margin-bottom: 8px;">Contraseña Temporal</span>
                                <span style="font-family: 'Cascadia Code', Consolas, monospace; font-size: 15px; font-weight: 600; color: #38bdf8; background-color: #111827; border: 1px solid rgba(56, 189, 248, 0.2); padding: 8px 14px; border-radius: 6px; display: inline-block; word-break: break-all;">{{ $password }}</span>
                            </div>
                        </div>

                        <div style="text-align: center; margin: 32px 0 16px 0;">
                            <a href="{{ url('/login') }}" style="display: inline-block; background: linear-gradient(135deg, #3b82f6 0%, #38bdf8 100%); color: #ffffff; text-decoration: none; font-size: 15px; font-weight: 600; padding: 14px 32px; border-radius: 8px; box-shadow: 0 4px 15px rgba(56, 189, 248, 0.2);">
                                Iniciar Sesión en IT-Secure
                            </a>
                        </div>

                        <p style="font-size: 13px; color: #94a3b8; font-weight: 300; border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 24px; margin-top: 32px; text-align: center;">
                            Por protocolos de seguridad operativa, te requerimos actualizar tu contraseña al ingresar por primera vez.
                        </p>
                    </td>
                </tr>

                <tr>
                    <td align="center" style="background-color: #090e17; border-top: 1px solid rgba(255, 255, 255, 0.07); padding: 24px 32px; font-size: 12px; color: #475569; font-weight: 400;">
                        &copy; {{ date('Y') }} IT-Secure &mdash; Mensaje automático de infraestructura, no responder a esta dirección.
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
