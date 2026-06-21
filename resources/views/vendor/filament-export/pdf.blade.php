<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $fileName ?? 'Reporte' }}</title>
    <style>
        @page {
            margin: 40px 40px 60px 40px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        /* Header */
        .header-table {
            width: 100%;
            margin-bottom: 15px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 15px;
        }
        .header-table td {
            vertical-align: middle;
            border: none;
            padding: 0;
        }
        .logo-container img {
            max-height: 55px;
        }
        .company-info {
            text-align: right;
        }
        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #0b132b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }
        .company-subtitle {
            font-size: 10px;
            color: #64748b;
            margin-bottom: 2px;
        }
        .company-date {
            font-size: 10px;
            color: #94a3b8;
        }

        /* Info Box */
        .info-box {
            width: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            background-color: #f8fafc;
            margin-bottom: 25px;
            padding: 10px 15px;
        }
        .info-table {
            width: 100%;
        }
        .info-table td {
            border: none;
            padding: 4px 0;
            font-size: 10px;
        }
        .info-label {
            font-weight: bold;
            color: #334155;
            text-transform: uppercase;
        }
        .info-value {
            color: #475569;
        }
        .text-right {
            text-align: right;
        }

        /* Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th {
            background-color: #0b132b;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 10px 8px;
            text-align: left;
            border: none;
        }
        .data-table td {
            padding: 10px 8px;
            font-size: 10px;
            color: #334155;
            border-bottom: 1px solid #e2e8f0;
        }
        .data-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: -30px;
            left: 0px;
            right: 0px;
            height: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            padding-top: 10px;
        }
        .page-number:before {
            content: counter(page);
        }
    </style>
</head>
<body>
    @php
        $logoPath = public_path('images/logo.png');
        $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';
        $reportName = str_replace('_', ' ', $fileName ?? 'Reporte del Sistema');
        $userName = auth()->check() ? auth()->user()->name : 'Sistema';
    @endphp

    <table class="header-table">
        <tr>
            <td style="width: 50%;" class="logo-container">
                @if($logoData)
                    <img src="data:image/png;base64,{{ $logoData }}" alt="FitControl Logo">
                @endif
            </td>
            <td style="width: 50%;" class="company-info">
                <div class="company-name">FITCONTROL</div>
                <div class="company-subtitle">Sistema de Gestión Deportiva</div>
                <div class="company-date">{{ now()->format('d/m/Y H:i') }}</div>
            </td>
        </tr>
    </table>

    <div class="info-box">
        <table class="info-table">
            <tr>
                <td style="width: 50%;">
                    <span class="info-label">REPORTE:</span> <span class="info-value">{{ $reportName }}</span>
                </td>
                <td style="width: 50%;" class="text-right">
                    <span class="info-label">GENERADO POR:</span> <span class="info-value">{{ $userName }}</span>
                </td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                @foreach ($columns as $column)
                    <th>{{ $column->getLabel() }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    @foreach ($columns as $column)
                        <td>{{ $row[$column->getName()] ?? '' }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        © {{ now()->format('Y') }} FitControl - Documento generado automáticamente por el sistema. Página <span class="page-number"></span>
    </div>
</body>
</html>
