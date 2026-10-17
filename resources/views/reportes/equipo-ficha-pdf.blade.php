<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha Técnica - {{ $equipo->codigo_inventario }}</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 11px;
            margin: 0;
            padding: 15px;
        }
        .header-table {
            width: 100%;
            border-bottom: 3px solid #0c1220;
            padding-bottom: 10px;
            margin-bottom: 18px;
        }
        .title-noc {
            font-size: 20px;
            font-weight: bold;
            color: #0c1220;
        }
        .title-noc span { color: #2563eb; }
        .meta-right {
            text-align: right;
            color: #64748b;
            font-size: 10px;
        }
        .section-header {
            background-color: #0c1220;
            color: #38bdf8;
            padding: 6px 10px;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            margin-top: 16px;
            margin-bottom: 8px;
        }
        /* Tabla de Ficha de Datos */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .info-table td {
            padding: 6px 8px;
            border: 1px solid #cbd5e1;
        }
        .info-label {
            background-color: #f1f5f9;
            font-weight: bold;
            color: #334155;
            width: 22%;
        }
        .info-val {
            width: 28%;
            color: #0f172a;
        }
        /* Tablas de Historial */
        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .history-table th {
            background-color: #e2e8f0;
            color: #1e293b;
            text-align: left;
            padding: 6px;
            font-size: 10px;
            text-transform: uppercase;
            border-bottom: 2px solid #94a3b8;
        }
        .history-table td {
            padding: 6px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .badge-code {
            font-family: Courier, monospace;
            font-weight: bold;
            color: #2563eb;
            font-size: 13px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            border-top: 1px solid #cbd5e1;
            padding-top: 6px;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
        }
    </style>
</head>
<body>

<table class="header-table">
    <tr>
        <td>
            <div class="title-noc">IT-<span>SECURE</span> | FICHA TÉCNICA DE ACTIVO</div>
            <div style="color: #64748b; font-size: 11px;">Hoja de Vida y Trazabilidad de Hardware</div>
        </td>
        <td class="meta-right">
            <strong>Código:</strong> <span class="badge-code">{{ $equipo->codigo_inventario }}</span><br>
            <strong>Fecha de Emisión:</strong> {{ date('d/m/Y H:i') }}
        </td>
    </tr>
</table>

<div class="section-header">1. Especificaciones Técnicas y Ubicación Actual</div>
<table class="info-table">
    <tr>
        <td class="info-label">Código Inventario:</td>
        <td class="info-val"><strong>{{ $equipo->codigo_inventario }}</strong></td>
        <td class="info-label">Estado Operativo:</td>
        <td class="info-val">{{ strtoupper($equipo->estado) }}</td>
    </tr>
    <tr>
        <td class="info-label">Tipo de Equipo:</td>
        <td class="info-val">{{ ucfirst($equipo->tipo) }}</td>
        <td class="info-label">Número de Serie:</td>
        <td class="info-val">{{ $equipo->numero_serie }}</td>
    </tr>
    <tr>
        <td class="info-label">Marca / Modelo:</td>
        <td class="info-val">{{ $equipo->marca }} / {{ $equipo->modelo }}</td>
        <td class="info-label">Empresa / Sede:</td>
        <td class="info-val">
            {{ $equipo->centroDeComputo->sede->empresa->nombre ?? 'N/A' }} -
            {{ $equipo->centroDeComputo->sede->nombre ?? 'N/A' }}
        </td>
    </tr>
    <tr>
        <td class="info-label">Centro de Cómputo:</td>
        <td class="info-val">{{ $equipo->centroDeComputo->nombre ?? 'Sin asignar' }}</td>
        <td class="info-label">Frecuencia Mant.:</td>
        <td class="info-val">Cada {{ $equipo->dias_frecuencia_mantenimiento }} días (Últ: {{ $equipo->fecha_ultimo_mantenimiento ?? 'N/A' }})</td>
    </tr>
</table>

<div class="section-header">2. Historial de Mantenimientos (Preventivos y Correctivos)</div>
<table class="history-table">
    <thead>
    <tr>
        <th style="width: 15%;">Fecha</th>
        <th style="width: 15%;">Tipo</th>
        <th style="width: 35%;">Trabajo Realizado</th>
        <th style="width: 20%;">Piezas Reemplazadas</th>
        <th style="width: 15%;">Técnico</th>
    </tr>
    </thead>
    <tbody>
    @forelse($equipo->mantenimientos as $mant)
        <tr>
            <td>{{ $mant->fecha_mantenimiento }}</td>
            <td><strong>{{ ucfirst($mant->tipo) }}</strong></td>
            <td>{{ $mant->descripcion_trabajo }}</td>
            <td>{{ $mant->piezas_reemplazadas ?: 'Ninguna' }}</td>
            <td>{{ $mant->usuario->nombre ?? 'N/A' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="5" style="text-align: center; color: #64748b;">
                Este equipo no registra mantenimientos hasta la fecha.
            </td>
        </tr>
    @endforelse
    </tbody>
</table>

<div class="section-header">3. Historial de Traslados entre Centros de Cómputo</div>
<table class="history-table">
    <thead>
    <tr>
        <th style="width: 15%;">Fecha</th>
        <th style="width: 22%;">Centro Origen</th>
        <th style="width: 22%;">Centro Destino</th>
        <th style="width: 26%;">Motivo del Traslado</th>
        <th style="width: 15%;">Autorizado Por</th>
    </tr>
    </thead>
    <tbody>
    @forelse($equipo->traslados as $traslado)
        <tr>
            <td>{{ $traslado->fecha_traslado }}</td>
            <td>{{ $traslado->centroOrigen->nombre ?? 'N/A' }}</td>
            <td>{{ $traslado->centroDestino->nombre ?? 'N/A' }}</td>
            <td>{{ $traslado->motivo }}</td>
            <td>{{ $traslado->autorizadoPor->nombre ?? 'N/A' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="5" style="text-align: center; color: #64748b;">
                Este equipo no registra traslados desde su ingreso.
            </td>
        </tr>
    @endforelse
    </tbody>
</table>

<div class="footer">
    IT-Secure &mdash; Documento de auditoría generado por {{ auth()->user()->nombre ?? 'Sistema' }}.
</div>

</body>
</html>
