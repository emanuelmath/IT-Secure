<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha Técnica - {{ $equipo->codigo_inventario }}</title>
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
            margin-bottom: 24px;
        }
        .title-noc {
            font-size: 22px;
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
            margin-top: 24px;
            margin-bottom: 12px;
            border-left: 3px solid #38bdf8;
            border-radius: 2px 4px 4px 2px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        .info-table td {
            padding: 8px 10px;
            border: 1px solid #1f2937;
        }
        .info-label {
            background-color: #090e17;
            font-weight: bold;
            color: #94a3b8;
            width: 22%;
            text-transform: uppercase;
            font-size: 10px;
        }
        .info-val {
            width: 28%;
            color: #f8fafc;
            background-color: #111827;
        }
        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        .history-table th {
            background-color: #090e17;
            color: #94a3b8;
            text-align: left;
            padding: 8px 10px;
            font-size: 10px;
            text-transform: uppercase;
            border-bottom: 2px solid #1f2937;
        }
        .history-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #1f2937;
            background-color: #111827;
            vertical-align: top;
            color: #cbd5e1;
        }
        .badge-code {
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
            color: #38bdf8;
            font-size: 13px;
        }
        .badge-status {
            display: inline-block;
            padding: 2px 6px;
            background-color: rgba(56, 189, 248, 0.1);
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.3);
            border-radius: 4px;
            font-size: 10px;
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
            <div class="title-noc">IT-<span>SECURE</span> | FICHA TÉCNICA DE ACTIVO</div>
            <div style="color: #64748b; font-size: 11px; margin-top: 4px; text-transform: uppercase;">Hoja de Vida y Trazabilidad de Hardware</div>
        </td>
        <td class="meta-right">
            <strong>Código de Inventario:</strong><br>
            <span class="badge-code">{{ $equipo->codigo_inventario }}</span><br><br>
            <strong>Fecha de Emisión:</strong><br>
            <span style="color: #cbd5e1;">{{ date('d/m/Y H:i') }}</span>
        </td>
    </tr>
</table>

<div class="section-header">1. Especificaciones Técnicas y Ubicación Actual</div>
<table class="info-table">
    <tr>
        <td class="info-label">Código Inventario:</td>
        <td class="info-val"><strong style="color: #38bdf8;">{{ $equipo->codigo_inventario }}</strong></td>
        <td class="info-label">Estado Operativo:</td>
        <td class="info-val">
            <span class="badge-status">{{ strtoupper(str_replace('_', ' ', $equipo->estado)) }}</span>
        </td>
    </tr>
    <tr>
        <td class="info-label">Tipo de Equipo:</td>
        <td class="info-val">{{ ucfirst($equipo->tipo) }}</td>
        <td class="info-label">Número de Serie:</td>
        <td class="info-val" style="font-family: 'Courier New', monospace;">{{ $equipo->numero_serie ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td class="info-label">Marca / Modelo:</td>
        <td class="info-val">{{ $equipo->marca }} / {{ $equipo->modelo }}</td>
        <td class="info-label">Empresa / Sede:</td>
        <td class="info-val">
            {{ $equipo->centroDeComputo->sede->empresa->nombre ?? 'N/A' }}<br>
            <span style="color: #94a3b8; font-size: 10px;">{{ $equipo->centroDeComputo->sede->nombre ?? 'N/A' }}</span>
        </td>
    </tr>
    <tr>
        <td class="info-label">Centro de Cómputo:</td>
        <td class="info-val">{{ $equipo->centroDeComputo->nombre ?? 'Sin asignar' }}</td>
        <td class="info-label">Frecuencia Mant.:</td>
        <td class="info-val">
            Cada {{ $equipo->dias_frecuencia_mantenimiento }} días<br>
            <span style="font-size:9px; color:#64748b;">(Último: {{ $equipo->fecha_ultimo_mantenimiento ?? 'N/A' }})</span>
        </td>
    </tr>
</table>

<div class="section-header">2. Historial de Mantenimientos (Preventivos y Correctivos)</div>
<table class="history-table">
    <thead>
    <tr>
        <th style="width: 14%;">Fecha</th>
        <th style="width: 16%;">Tipo</th>
        <th style="width: 32%;">Trabajo Realizado</th>
        <th style="width: 22%;">Piezas Reemplazadas</th>
        <th style="width: 16%;">Técnico Asignado</th>
    </tr>
    </thead>
    <tbody>
    @forelse($equipo->mantenimientos as $mant)
        <tr>
            <td style="color: #94a3b8;">{{ $mant->fecha_mantenimiento }}</td>
            <td><strong style="color: #f8fafc;">{{ ucfirst($mant->tipo) }}</strong></td>
            <td>{{ $mant->descripcion_trabajo }}</td>
            <td style="color: #94a3b8;">{{ $mant->piezas_reemplazadas ?: 'Ninguna' }}</td>
            <td>{{ $mant->usuario->nombre ?? 'N/A' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="5" style="text-align: center; color: #475569; padding: 20px;">
                Este activo no registra mantenimientos hasta la fecha de emisión.
            </td>
        </tr>
    @endforelse
    </tbody>
</table>

<div class="section-header">3. Historial de Traslados de Infraestructura</div>
<table class="history-table">
    <thead>
    <tr>
        <th style="width: 14%;">Fecha</th>
        <th style="width: 21%;">Centro Origen</th>
        <th style="width: 21%;">Centro Destino</th>
        <th style="width: 28%;">Motivo del Traslado</th>
        <th style="width: 16%;">Autorizado Por</th>
    </tr>
    </thead>
    <tbody>
    @forelse($equipo->traslados as $traslado)
        <tr>
            <td style="color: #94a3b8;">{{ $traslado->fecha_traslado }}</td>
            <td>{{ $traslado->centroOrigen->nombre ?? 'N/A' }}</td>
            <td><strong style="color: #f8fafc;">{{ $traslado->centroDestino->nombre ?? 'N/A' }}</strong></td>
            <td>{{ $traslado->motivo }}</td>
            <td>{{ $traslado->autorizadoPor->nombre ?? 'N/A' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="5" style="text-align: center; color: #475569; padding: 20px;">
                Este activo no registra traslados estructurales desde su alta.
            </td>
        </tr>
    @endforelse
    </tbody>
</table>

<div class="footer">
    IT-Secure &mdash; Documento de auditoría generado automáticamente por {{ auth()->user()->nombre ?? 'Sistema' }}.
</div>

</body>
</html>
