<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Logs Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.5;
            color: #333;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #1F4E78;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header-title h1 {
            font-size: 28px;
            color: #1F4E78;
            margin: 0 0 5px 0;
        }

        .header-title p {
            font-size: 12px;
            color: #666;
            margin: 0;
        }

        .header-meta {
            text-align: right;
            font-size: 11px;
            color: #999;
        }

        .filters {
            background-color: #f0f4f8;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
            font-size: 11px;
        }

        .filter-item {
            display: inline-block;
            margin-right: 20px;
            margin-bottom: 8px;
        }

        .filter-label {
            color: #666;
            font-weight: bold;
        }

        .filter-value {
            color: #1F4E78;
            font-weight: 600;
        }

        .summary {
            display: flex;
            gap: 30px;
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border-left: 4px solid #1F4E78;
            border-radius: 4px;
        }

        .summary-item {
            flex: 1;
        }

        .summary-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .summary-value {
            font-size: 22px;
            font-weight: bold;
            color: #1F4E78;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 10px;
        }

        thead {
            background-color: #1F4E78;
            color: white;
            position: sticky;
            top: 0;
        }

        th {
            padding: 12px 8px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #1F4E78;
            word-break: break-word;
        }

        td {
            padding: 10px 8px;
            border: 1px solid #ddd;
            vertical-align: top;
            word-break: break-word;
        }

        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        tbody tr:nth-child(even) {
            background-color: #f5f5f5;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            white-space: nowrap;
        }

        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .method-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            color: white;
            white-space: nowrap;
        }

        .method-get {
            background-color: #61affe;
        }

        .method-post {
            background-color: #49cc90;
        }

        .method-put {
            background-color: #fca130;
        }

        .method-delete {
            background-color: #f93e3e;
        }

        .method-patch {
            background-color: #50e3c2;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #999;
            text-align: center;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-title">
                <h1>Audit Logs Report</h1>
                <p>System Activity and Transaction Tracking</p>
            </div>
            <div class="header-meta">
                <p><strong>Generated:</strong> {{ $generatedAt }}</p>
                <p><strong>Total Records:</strong> {{ $totalRecords }}</p>
            </div>
        </div>

        @if($filters && count($filters) > 0)
            <div class="filters">
                @foreach($filters as $key => $value)
                    <div class="filter-item">
                        <span class="filter-label">{{ ucfirst($key) }}:</span>
                        <span class="filter-value">{{ $value }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="summary">
            <div class="summary-item">
                <div class="summary-label">Total Records</div>
                <div class="summary-value">{{ $totalRecords }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Period</div>
                <div class="summary-value">{{ $periodLabel }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Report Type</div>
                <div class="summary-value">Complete</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">ID</th>
                    <th style="width: 12%;">User</th>
                    <th style="width: 15%;">Action</th>
                    <th style="width: 10%;">Method</th>
                    <th style="width: 20%;">Endpoint</th>
                    <th style="width: 8%;">Status</th>
                    <th style="width: 12%;">IP Address</th>
                    <th style="width: 15%;">Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ $log['id'] }}</td>
                        <td>
                            @if($log['user_id'])
                                <strong>{{ $log['user_name'] ?? 'System' }}</strong><br>
                                <small>{{ $log['user_email'] ?? 'N/A' }}</small>
                            @else
                                <span style="color: #999;">Anonymous</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-info">
                                {{ str_replace('_', ' ', ucfirst($log['action'])) }}
                            </span>
                        </td>
                        <td>
                            <span class="method-badge method-{{ strtolower($log['method']) }}">
                                {{ $log['method'] }}
                            </span>
                        </td>
                        <td>
                            <code style="font-size: 9px; background: #f4f4f4; padding: 2px 4px; border-radius: 2px;">
                                {{ substr($log['endpoint'], 0, 50) }}{{ strlen($log['endpoint']) > 50 ? '...' : '' }}
                            </code>
                        </td>
                        <td>
                            @if($log['status_code'] >= 200 && $log['status_code'] < 300)
                                <span class="badge badge-success">{{ $log['status_code'] }}</span>
                            @elseif($log['status_code'] >= 300 && $log['status_code'] < 400)
                                <span class="badge badge-info">{{ $log['status_code'] }}</span>
                            @elseif($log['status_code'] >= 400 && $log['status_code'] < 500)
                                <span class="badge badge-warning">{{ $log['status_code'] }}</span>
                            @else
                                <span class="badge badge-danger">{{ $log['status_code'] }}</span>
                            @endif
                        </td>
                        <td>
                            <code style="font-size: 9px;">{{ $log['ip_address'] ?? 'N/A' }}</code>
                        </td>
                        <td>{{ $log['created_at'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: #999; padding: 20px;">
                            No audit logs found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            <p>This is an automatically generated audit report. © {{ date('Y') }} System. All rights reserved.</p>
            <p style="margin-top: 5px;">For security and compliance purposes only.</p>
        </div>
    </div>
</body>
</html>
