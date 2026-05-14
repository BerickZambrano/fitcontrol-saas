<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <style>
        @page {
            margin: 1.5cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #334155;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 2px solid #0f172a;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-logo {
            width: 80px;
        }
        .header-content {
            text-align: right;
        }
        .header-content h1 {
            margin: 0;
            color: #0f172a;
            font-size: 22px;
            text-transform: uppercase;
        }
        .header-content p {
            margin: 2px 0;
            font-size: 10px;
            color: #64748b;
        }
        .meta-info {
            background-color: #f8fafc;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 11px;
            border: 1px solid #e2e8f0;
        }
        .meta-info table {
            width: 100%;
        }
        .meta-info td {
            padding: 2px 0;
        }
        table.main-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.main-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 10px;
            padding: 10px 5px;
            text-align: center;
            text-transform: uppercase;
        }
        table.main-table td {
            font-size: 10px;
            padding: 8px 5px;
            border-bottom: 1px solid #f1f5f9;
            text-align: center;
        }
        table.main-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .footer {
            position: fixed;
            bottom: -30px;
            left: 0px;
            right: 0px;
            height: 30px;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-success { background-color: #dcfce7; color: #166534; }
        .badge-danger { background-color: #fee2e2; color: #991b1b; }
        .badge-info { background-color: #e0f2fe; color: #075985; }
        .text-left { text-align: left !important; }
        .text-right { text-align: right !important; }
        .bold { font-weight: bold; }
        @yield('custom-styles')
    </style>
</head>
<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="header-logo">
                    @php
                        $logoPath = public_path('images/logo.png');
                        $logoData = "";
                        if (file_exists($logoPath)) {
                            $logoData = base64_encode(file_get_contents($logoPath));
                        }
                    @endphp
                    @if($logoData)
                        <img src="data:image/png;base64,{{ $logoData }}" style="height: 60px;">
                    @else
                        <div style="width: 60px; height: 60px; background: #0f172a; border-radius: 5px;"></div>
                    @endif
                </td>
                <td class="header-content">
                    <h1>FitControl</h1>
                    <p>Sistema de Gestión Deportiva Inteligente</p>
                    <p>{{ now()->format('d/m/Y H:i') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="meta-info">
        @yield('meta')
    </div>

    <div class="content">
        @yield('content')
    </div>

    <div class="footer">
        © {{ date('Y') }} FitControl - Documento generado automáticamente por el sistema. Página <span class="pagenum"></span>
    </div>
</body>
</html>
