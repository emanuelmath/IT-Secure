<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acta de Traslado - {{ $traslado->equipo->codigo_inventario }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #f8fafc;
            background-color: #030712;
            font-size: 11px;
            margin: 0;
            padding: 20px;
        }
        .header-table {
            width: 100%;
            border-bottom: 1px solid #1f2937;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .title-noc {
            font-size: 19px;
            font-weight: bold;
            color: #f8fafc;
            letter-spacing: 1px;
        }
        .title-noc span { color: #38bdf8; }
        .meta-right {
            text-align: right;
            color: #94a3b8;
            font-size: 10px;
            line-height: 1.5;
        }
        .section-header {
            background-color: #0f172a;
            color: #38bdf8;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 18px;
            margin-bottom: 12px;
            border-left: 3px solid #38bdf8;
            border-radius: 2px 4px 4px 2px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .info-table td {
            padding: 8px 10px;
            border: 1px solid #1f2937;
        }
        .info-label {
            background-color: #090e17;
            font-weight: bold;
            color: #94a3b8;
            width: 24%;
            text-transform: uppercase;
            font-size: 10px;
        }
        .info-val {
            width: 26%;
            color: #f8fafc;
            background-color: #111827;
        }
        .route-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        .route-box {
            width: 48%;
            padding: 12px;
            border: 1px solid #1f2937;
            background-color: #111827;
            vertical-align: top;
        }
        .route-title {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 1px solid #1f2937;
        }
        .badge-code {
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
            color: #38bdf8;
            font-size: 12px;
        }
        .clausula-box {
            padding: 10px 12px;
            border: 1px solid #1f2937;
            background-color: #090e17;
            color: #94a3b8;
            font-size: 9.5px;
            line-height: 1.5;
            margin-top: 16px;
            margin-bottom: 25px;
            border-radius: 4px;
        }
        .firmas-table {
            width: 100%;
            margin-top: 35px;
            border-collapse: collapse;
        }
        .firma-box {
            width: 45%;
            text-align: center;
            vertical-align: bottom;
        }
        .firma-line {
            border-top: 1px solid #64748b;
            margin: 0 auto 6px auto;
            width: 80%;
        }
        .footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            border-top: 1px solid #1f2937;
            padding-top: 8px;
            text-align: center;
            font-size: 9px;
            color: #475569;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

<table class="header-table">
    <tr>
        <td>
            <div class="title-noc">IT-<span>SECURE</span> | ACTA OFICIAL DE TRASLADO</div>
            <div style="color: #64748b; font-size: 11px; margin-top: 4px; text-transform: uppercase;">
                Constancia de Movimiento y Custodia de Hardware
            </div>
        </td>
        <td class="meta-right">
            <strong>N° de Acta:</strong><br>
            <span class="badge-code">ACTA-{{ str_pad($traslado->id, 5, '0', STR_PAD_LEFT) }}</span><br><br>
            <strong>Fecha de Expedición:</strong><br>
            <span style="color: #cbd5e1;">{{ date('d/m/Y H:i') }}</span>
        </td>
    </tr>
</table>

<div class="section-header">1. Ruta de Traslado de Infraestructura</div>
<table class="route-table">
    <tr>
        <td class="route-box">
            <div class="route-title" style="color: #f59e0b;">
                &bull; Ubicación de Origen (Remitente)
            </div>
            <p style="margin: 0 0 4px 0; color: #f8fafc; font-weight: bold;">
                {{ $traslado->centroOrigen->nombre ?? 'N/A' }} ({{ $traslado->centroOrigen->codigo ?? '' }})
            </p>
            <span style="color: #94a3b8; font-size: 10px;">
                Sede: {{ $traslado->centroOrigen->sede->nombre ?? 'N/A' }}<br>
                Empresa: {{ $traslado->centroOrigen->sede->empresa->nombre ?? 'N/A' }}
            </span>
        </td>
        <td style="width: 4%;"></td>
        <td class="route-box">
            <div class="route-title" style="color: #10b981;">
                &bull; Ubicación de Destino (Receptor)
            </div>
            <p style="margin: 0 0 4px 0; color: #f8fafc; font-weight: bold;">
                {{ $traslado->centroDestino->nombre ?? 'N/A' }} ({{ $traslado->centroDestino->codigo ?? '' }})
            </p>
            <span style="color: #94a3b8; font-size: 10px;">
                Sede: {{ $traslado->centroDestino->sede->nombre ?? 'N/A' }}<br>
                Empresa: {{ $traslado->centroDestino->sede->empresa->nombre ?? 'N/A' }}
            </span>
        </td>
    </tr>
</table>

<div class="section-header">2. Activo Informático Transferido</div>
<table class="info-table">
    <tr>
        <td class="info-label">Código Inventario:</td>
        <td class="info-val"><strong style="color: #38bdf8;">{{ $traslado->equipo->codigo_inventario ?? 'N/A' }}</strong></td>
        <td class="info-label">Tipo de Hardware:</td>
        <td class="info-val">{{ ucfirst($traslado->equipo->tipo ?? '') }}</td>
    </tr>
    <tr>
        <td class="info-label">Marca / Modelo:</td>
        <td class="info-val">{{ $traslado->equipo->marca ?? 'N/A' }} {{ $traslado->equipo->modelo ?? '' }}</td>
        <td class="info-label">Número de Serie:</td>
        <td class="info-val" style="font-family: 'Courier New', monospace;">{{ $traslado->equipo->numero_serie ?? 'S/N' }}</td>
    </tr>
    <tr>
        <td class="info-label">Estado Operativo:</td>
        <td class="info-val" style="color: #10b981; font-weight: bold;">{{ strtoupper(str_replace('_', ' ', $traslado->equipo->estado ?? 'OPERATIVO')) }}</td>
        <td class="info-label">Fecha del Traslado:</td>
        <td class="info-val">{{ optional($traslado->fecha_traslado)->format('d/m/Y') ?? $traslado->fecha_traslado }}</td>
    </tr>
</table>

<div class="section-header">3. Motivo y Autorización del Movimiento</div>
<table class="info-table">
    <tr>
        <td class="info-label">Autorizado Por:</td>
        <td class="info-val" colspan="3"><strong style="color: #f8fafc;">{{ $traslado->autorizador->nombre ?? 'Sistema' }}</strong> ({{ $traslado->autorizador->email ?? '' }})</td>
    </tr>
    <tr>
        <td class="info-label">Motivo Justificado:</td>
        <td class="info-val" colspan="3" style="line-height: 1.4; color: #cbd5e1;">{{ $traslado->motivo }}</td>
    </tr>
</table>

<div class="clausula-box">
    <strong>Declaración de Custodia y Entrega de Hardware:</strong><br>
    Por medio del presente documento, se hace constar el traslado físico y la transferencia de responsabilidad del activo computacional descrito. El responsable receptor asume la custodia, conservación y monitoreo de la unidad en el centro de cómputo de destino bajo las políticas de seguridad de la infraestructura IT-Secure.
</div>

<table class="firmas-table">
    <tr>
        <td class="firma-box">
            <div class="firma-line"></div>
            <strong style="color: #f8fafc; font-size: 10px;">{{ $traslado->autorizador->nombre ?? 'Responsable Remitente' }}</strong><br>
            <span style="color: #64748b; font-size: 9px; text-transform: uppercase;">Emisor / Autorizador del Movimiento</span>
        </td>
        <td style="width: 10%;"></td>
        <td class="firma-box">
            <div class="firma-line"></div>
            <strong style="color: #f8fafc; font-size: 10px;">Responsable Centro Destino</strong><br>
            <span style="color: #64748b; font-size: 9px; text-transform: uppercase;">Receptor de Activo & Conforme</span>
        </td>
    </tr>
</table>

<div class="footer">
    IT-Secure NOC &mdash; Registro Oficial de Movimientos de Infraestructura &bull; Trazabilidad Inmutable.
</div>

</body>
</html>
