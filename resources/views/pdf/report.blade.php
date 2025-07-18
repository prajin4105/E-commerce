<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ ucfirst($type) }} Report</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap');
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            background: #f8fafc;
            color: #222;
            margin: 0;
            padding: 0;
        }
        .invoice-container {
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px #e2e8f0;
            padding: 40px 40px 32px 40px;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 18px;
            margin-bottom: 32px;
        }
        .brand {
            font-size: 2rem;
            font-weight: 600;
            color: #2563eb;
            letter-spacing: 1px;
        }
        .report-title {
            font-size: 1.3rem;
            color: #64748b;
            font-weight: 400;
        }
        .summary {
            margin-bottom: 32px;
        }
        .summary-title {
            font-size: 1.1rem;
            color: #334155;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .summary-content {
            color: #475569;
            font-size: 1rem;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .data-table th {
            background: #f1f5f9;
            color: #334155;
            font-weight: 600;
            padding: 12px 8px;
            border-bottom: 2px solid #e5e7eb;
            text-align: left;
        }
        .data-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 0.98rem;
        }
        .data-table tr:last-child td {
            border-bottom: none;
        }
        .footer {
            margin-top: 32px;
            text-align: center;
            color: #64748b;
            font-size: 0.97rem;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <div class="brand">{{ config('app.name', 'Report') }}</div>
            <div class="report-title">{{ ucfirst($type) }} Report</div>
        </div>

        {{-- Optional summary section --}}
        {{--
        <div class="summary">
            <div class="summary-title">Summary</div>
            <div class="summary-content">
                <!-- Add summary info here if needed -->
            </div>
        </div>
        --}}

        @if (!empty($data))
            <table class="data-table">
                <thead>
                    <tr>
                        @foreach (array_keys($data[0]) as $col)
                            <th>{{ $col }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                        <tr>
                            @foreach ($row as $value)
                                <td>{{ $value }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No data available for this report.</p>
        @endif

        <div class="footer">
            <p>Generated on {{ now()->format('M d, Y h:i A') }}</p>
            <p>This is a computer-generated document. No signature is required.</p>
        </div>
    </div>
</body>
</html> 