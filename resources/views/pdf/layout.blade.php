<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Relatorio PDF')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }
        
        .header {
            background: #f8f9fa;
            padding: 20px;
            border-bottom: 3px solid #007bff;
            margin-bottom: 20px;
        }
        
        .header h1 {
            color: #007bff;
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .header .subtitle {
            color: #6c757d;
            font-size: 14px;
        }
        
        .header .date {
            float: right;
            color: #6c757d;
            font-size: 11px;
        }
        
        .content {
            padding: 0 20px;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50px;
            background: #f8f9fa;
            border-top: 1px solid #dee2e6;
            padding: 15px 20px;
            font-size: 10px;
            color: #6c757d;
        }
        
        .footer .page-number {
            float: right;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            background: #e9ecef;
            padding: 10px 15px;
            font-weight: bold;
            color: #495057;
            border-left: 4px solid #007bff;
            margin-bottom: 15px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 8px 15px 8px 0;
            width: 30%;
            vertical-align: top;
            color: #495057;
        }
        
        .info-value {
            display: table-cell;
            padding: 8px 0;
            vertical-align: top;
        }
        
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pendente {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .status-em_andamento {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        .status-resolvido {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .priority-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .priority-baixa {
            background: #d4edda;
            color: #155724;
        }
        
        .priority-media {
            background: #fff3cd;
            color: #856404;
        }
        
        .priority-alta {
            background: #f8d7da;
            color: #721c24;
        }
        
        .priority-critica {
            background: #721c24;
            color: #fff;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .table th,
        .table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        
        .table th {
            background: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        
        .table tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .description-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin: 15px 0;
        }
        
        .description-box h4 {
            color: #495057;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .chart-container {
            text-align: center;
            margin: 20px 0;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .stats-row {
            display: table-row;
        }
        
        .stats-cell {
            display: table-cell;
            width: 25%;
            padding: 15px;
            text-align: center;
            border: 1px solid #dee2e6;
            background: #f8f9fa;
        }
        
        .stats-number {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            display: block;
        }
        
        .stats-label {
            font-size: 11px;
            color: #6c757d;
            text-transform: uppercase;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .no-break {
            page-break-inside: avoid;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .mb-0 {
            margin-bottom: 0;
        }
        
        .mb-10 {
            margin-bottom: 10px;
        }
        
        .mb-20 {
            margin-bottom: 20px;
        }
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="header clearfix">
        <div class="date">
            Gerado em: {{ now()->format('d/m/Y H:i:s') }}
        </div>
        <h1>@yield('header-title', 'Relatório do Sistema')</h1>
        <div class="subtitle">@yield('header-subtitle', 'Sistema de Relatórios')</div>
    </div>

    <div class="content">
        @yield('content')
    </div>

    <div class="footer">
        <div>Sistema de Relatórios - {{ config('app.name') }}</div>
        <div class="page-number">
            Página <span class="pagenum"></span>
        </div>
    </div>
</body>
</html> 