<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PMMS Print Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 32px; color: #0f172a; }
        h1, h2 { margin-bottom: 8px; }
        .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
        .card { border: 1px solid #cbd5e1; border-radius: 12px; padding: 16px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th, td { border: 1px solid #cbd5e1; padding: 10px; text-align: left; }
        th { background: #f8fafc; }
    </style>
</head>
<body onload="window.print()">
    <h1>PMMS Report</h1>
    <p>Generated at {{ now()->format('d M Y h:i A') }}</p>

    <div class="grid">
        <div class="card"><strong>Income</strong><br>৳{{ number_format($summary['income'], 2) }}</div>
        <div class="card"><strong>Expense</strong><br>৳{{ number_format($summary['expense'], 2) }}</div>
        <div class="card"><strong>Net Cash Flow</strong><br>৳{{ number_format($summary['net_cash_flow'], 2) }}</div>
    </div>

    <h2>Income</h2>
    <table>
        <thead><tr><th>Source</th><th>From</th><th>Status</th><th>Amount</th></tr></thead>
        <tbody>
            @foreach ($incomes as $income)
                <tr>
                    <td>{{ $income->category?->name }}</td>
                    <td>{{ $income->received_from ?: '—' }}</td>
                    <td>{{ ucfirst($income->status) }}</td>
                    <td>৳{{ number_format($income->amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Expenses</h2>
    <table>
        <thead><tr><th>Category</th><th>Paid To</th><th>Status</th><th>Amount</th></tr></thead>
        <tbody>
            @foreach ($expenses as $expense)
                <tr>
                    <td>{{ $expense->category?->name }}</td>
                    <td>{{ $expense->paid_to ?: '—' }}</td>
                    <td>{{ ucfirst($expense->status) }}</td>
                    <td>৳{{ number_format($expense->amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
