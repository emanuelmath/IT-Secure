<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe de Estado - {{ $centro->codigo }}</title>
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
            font-size: 20px;
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
            margin-top: 20px;
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
        .kpi-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .kpi-box {
            text-align: center;
            padding: 12px 8px;
            border: 1px solid #1f2937;
            background-color: #111827;
        }
        .kpi-val {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .kpi-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
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
            padding: 7px 10px;
            border-bottom: 1px solid #1f2937;
            background-color: #111827;
            color: #cbd5e1;
            font-size: 10px;
        }
        .badge-code {
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
            color: #38bdf8;
            font-size: 12px;
        }
        .badge-status {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-operativo {
            background-color: rgba(16, 185, 129, 0.15);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        .status-mantenimiento {
            background-color: rgba(245, 158, 11, 0.15);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }
        .status-averiado {
            background-color: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        .status-baja {
            background-color: rgba(148, 163, 184, 0.15);
            color: #94a3b8;
            border: 1px solid rgba(148, 163, 184, 0.3);
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
            <div class="title-noc">IT-<span>SECURE</span> | REPORTE EJECUTIVO DE ESTADO</div>
            <div style="color: #64748b; font-size: 11px; margin-top: 4px; text-transform: uppercase;">
                Diagnóstico y Disponibilidad de Centro de Cómputo
            </div>
        </td>
        <td class="meta-right">
            <strong>Código Centro:</strong><br>
            <span class="badge-code">{{ $centro->codigo }}</span><br><br>
            <strong>Fecha de Emisión:</strong><br>
            <span style="color: #cbd5e1;">{{ date('d/m/Y H:i') }}</span>
        </td>
    </tr>
</table>

<div class="section-header">1. Información General del Centro de Cómputo</div>
<table class="info-table">
    <tr>
        <td class="info-label">Nombre del Centro:</td>
        <td class="info-val"><strong style="color: #38bdf8;">{{ $centro->nombre }}</strong></td>
        <td class="info-label">Código:</td>
        <td class="info-val font-monospace">{{ $centro->codigo }}</td>
    </tr>
    <tr>
        <td class="info-label">Sede / Ubicación:</td>
        <td class="info-val">{{ $centro->sede->nombre ?? 'N/A' }}</td>
        <td class="info-label">Empresa:</td>
        <td class="info-val">{{ $centro->sede->empresa->nombre ?? 'N/A' }}</td>
    </tr>
</table>

<div class="section-header">2. Resumen de Disponibilidad Operativa</div>
<table class="kpi-table">
    <tr>
        <td class="kpi-box" style="width: 20%;">
            <div class="kpi-val" style="color: #38bdf8;">{{ $totalEquipos }}</div>
            <div class="kpi-label">Total Activos</div>
        </td>
        <td class="kpi-box" style="width: 20%;">
            <div class="kpi-val" style="color: #10b981;">{{ $operativos }}</div>
            <div class="kpi-label">Funcionan (Operativos)</div>
        </td>
        <td class="kpi-box" style="width: 20%;">
            <div class="kpi-val" style="color: #f59e0b;">{{ $enMantenimiento }}</div>
            <div class="kpi-label">En Mantenimiento</div>
        </td>
        <td class="kpi-box" style="width: 20%;">
            <div class="kpi-val" style="color: #ef4444;">{{ $averiados }}</div>
            <div class="kpi-label">Arruinados (Averiados)</div>
        </td>
        <td class="kpi-box" style="width: 20%;">
            @php
                $tasaDisponibilidad = $totalEquipos > 0 ? round(($operativos / $totalEquipos) * 100, 1) : 0;
            @endphp
            <div class="kpi-val" style="color: {{ $tasaDisponibilidad >= 80 ? '#10b981' : ($tasaDisponibilidad >= 50 ? '#f59e0b' : '#ef4444') }};">
                {{ $tasaDisponibilidad }}%
            </div>
            <div class="kpi-label">Tasa Operativa</div>
        </td>
    </tr>
</table>

<div class="section-header">3. Inventario Exacto por Tipo de Hardware</div>
<table class="info-table">
    <tr>
        @php $colIndex = 0; @endphp
        @forelse($desgloseTipos as $tipo => $cantidad)
            <td class="info-label">{{ ucfirst($tipo) }}:</td>
            <td class="info-val"><strong style="color: #38bdf8;">{{ $cantidad }}</strong> unidad(es)</td>
            @php
                $colIndex++;
                if ($colIndex % 2 == 0 && !$loop->last) {
                    echo '</tr><tr>';
                }
            @endphp
        @empty
            <td colspan="4" style="text-align: center; color: #64748b; padding: 12px;">
                No hay equipos registrados en este centro de cómputo.
            </td>
        @endforelse
        @if($colIndex % 2 != 0)
            <td class="info-label"></td>
            <td class="info-val"></td>
        @endif
    </tr>
</table>

<div class="section-header">4. Detalle y Expediente del Parque Informático</div>
<table class="history-table">
    <thead>
    <tr>
        <th style="width: 18%;">Código</th>
        <th style="width: 16%;">Tipo</th>
        <th style="width: 24%;">Marca / Modelo</th>
        <th style="width: 22%;">N° de Serie</th>
        <th style="width: 20%; text-align: center;">Estado Actual</th>
    </tr>
    </thead>
    <tbody>
    @forelse($equipos as $eq)
        <tr>
            <td style="font-family: 'Courier New', monospace; font-weight: bold; color: #38bdf8;">
                {{ $eq->codigo_inventario }}
            </td>
            <td>{{ ucfirst($eq->tipo) }}</td>
            <td>{{ $eq->marca }} {{ $eq->modelo }}</td>
            <td style="font-family: 'Courier New', monospace; color: #94a3b8;">{{ $eq->numero_serie ?? 'S/N' }}</td>
            <td style="text-align: center;">
                @php
                    $cssStatus = match($eq->estado) {
                        'operativo' => 'status-operativo',
                        'mantenimiento' => 'status-mantenimiento',
                        'averiado' => 'status-averiado',
                        default => 'status-baja',
                    };
                @endphp
                <span class="badge-status {{ $cssStatus }}">
                    {{ strtoupper(str_replace('_', ' ', $eq->estado)) }}
                </span>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" style="text-align: center; color: #475569; padding: 20px;">
                No hay registros de equipos asociados a este centro.
            </td>
        </tr>
    @endforelse
    </tbody>
</table>

<div class="footer">
    IT-Secure NOC &mdash; Documento de auditoría de infraestructura emitido por {{ auth()->user()->nombre ?? 'Sistema' }}.
</div>

</body>
</html>
