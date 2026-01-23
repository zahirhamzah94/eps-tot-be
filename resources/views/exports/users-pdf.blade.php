<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 800px;
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
            border-bottom: 3px solid #0066CC;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header-title h1 {
            font-size: 28px;
            color: #0066CC;
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

        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            padding: 15px;
            background-color: #f9f9f9;
            border-left: 4px solid #0066CC;
        }

        .stat-item {
            flex: 1;
        }

        .stat-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #0066CC;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 11px;
        }

        thead {
            background-color: #0066CC;
            color: white;
        }

        th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #0066CC;
        }

        td {
            padding: 10px 12px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #f0f0f0;
        }

        .footer {
            margin-top: 30px;
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
                <h1>Users Report</h1>
                <p>System User Management</p>
            </div>
            <div class="header-meta">
                <p><strong>Generated:</strong> {{ $generatedAt }}</p>
                <p><strong>Total Users:</strong> {{ $totalUsers }}</p>
            </div>
        </div>

        <div class="stats">
            <div class="stat-item">
                <div class="stat-label">Total Users</div>
                <div class="stat-value">{{ $totalUsers }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Report Type</div>
                <div class="stat-value">Complete</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Status</div>
                <div class="stat-value">Active</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user['id'] }}</td>
                        <td>{{ $user['name'] }}</td>
                        <td>{{ $user['email'] }}</td>
                        <td>{{ $user['roles'] ?: 'No roles' }}</td>
                        <td>{{ $user['created_at'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #999;">No users found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            <p>This is an automatically generated report. © {{ date('Y') }} System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
