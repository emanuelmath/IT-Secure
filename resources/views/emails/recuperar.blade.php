<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña | IT-Secure</title>
</head>
<body style="margin: 0; padding: 0; width: 100% !important; background-color: #030712; color: #f8fafc; font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; -webkit-font-smoothing: antialiased;">

<table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #030712; padding: 40px 15px;">
    <tr>
        <td align="center">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 520px; background-color: #111827; border: 1px solid rgba(255, 255, 255, 0.07); border-radius: 16px; overflow: hidden; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);">

                <tr>
                    <td align="center" style="background-color: #090e17; padding: 32px 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.07);">
                        <h2 style="margin: 0; font-size: 22px; font-weight: 700; color: #f8fafc; letter-spacing: 0.15em; text-transform: uppercase;">
                            IT-<span style="color: #38bdf8;">SECURE</span>
                        </h2>
                    </td>
                </tr>

                <tr>
                    <td align="center" style="padding: 48px 32px;">

                        <div style="display: inline-block; width: 64px; height: 64px; line-height: 64px; background-color: rgba(56, 189, 248, 0.08); border: 1px solid rgba(56, 189, 248, 0.2); border-radius: 50%; color: #38bdf8; font-size: 28px; margin-bottom: 24px; box-shadow: 0 0 20px rgba(56, 189, 248, 0.1);">
                            &#128274;
                        </div>

                        <h3 style="margin: 0 0 16px 0; font-size: 20px; font-weight: 600; color: #f8fafc;">Recuperación de Acceso</h3>

                        <p style="margin: 0 0 32px 0; font-size: 15px; line-height: 1.6; color: #94a3b8; font-weight: 300; text-align: center;">
                            Hola, hemos recibido una solicitud para restablecer la contraseña de tu cuenta en <strong style="color: #f8fafc; font-weight: 500;">IT-Secure</strong>. Haz clic en el botón de abajo para asignar una nueva clave de acceso.
                        </p>

                        <table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td align="center" style="border-radius: 8px; background: linear-gradient(135deg, #3b82f6 0%, #38bdf8 100%);">
                                    <a href="{{ $url ?? '#' }}" target="_blank" style="display: inline-block; padding: 14px 32px; font-size: 15px; font-weight: 600; color: #ffffff; text-decoration: none; border-radius: 8px;">
                                        Restablecer Contraseña
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <p style="margin: 32px 0 0 0; font-size: 13px; line-height: 1.6; color: #475569; text-align: center; font-weight: 400;">
                            Este enlace expirará por seguridad en 60 minutos.<br>
                            Si no solicitaste esta acción, desestima este mensaje.
                        </p>
                    </td>
                </tr>

                <tr>
                    <td align="left" style="background-color: #090e17; padding: 24px 32px; border-top: 1px solid rgba(255, 255, 255, 0.07);">
                        <p style="margin: 0; font-size: 12px; color: #475569; word-break: break-all; font-weight: 300;">
                            Si experimentas problemas con el botón, accede al siguiente enlace de forma manual:<br><br>
                            <a href="{{ $url ?? '#' }}" style="color: #38bdf8; text-decoration: none; font-weight: 400;">{{ $url ?? '#' }}</a>
                        </p>
                    </td>
                </tr>

            </table>

            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 520px;">
                <tr>
                    <td align="center" style="padding: 24px 0;">
                        <p style="margin: 0; font-size: 12px; color: #475569; font-weight: 500;">
                            &copy; {{ date('Y') }} <span style="color: #38bdf8;">IT-Secure</span>. Acceso Restringido.
                        </p>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>
</body>
</html>
