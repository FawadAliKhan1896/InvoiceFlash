<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $invoice->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 13px; color: #1e293b; line-height: 1.5; }
        .container { padding: 40px; }
        .header { display: flex; margin-bottom: 40px; }
        .header-left { width: 50%; float: left; }
        .header-right { width: 50%; float: right; text-align: right; }
        .header::after { content: ''; display: table; clear: both; }
        .brand-bar { height: 6px; background: {{ $invoice->brand_color }}; border-radius: 3px; margin-bottom: 30px; }
        .invoice-title { font-size: 32px; font-weight: 800; color: {{ $invoice->brand_color }}; text-transform: uppercase; letter-spacing: 2px; }
        .invoice-number { font-size: 16px; font-weight: 600; color: #475569; margin-top: 5px; }
        .meta-label { font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 3px; }
        .business-name { font-size: 18px; font-weight: 700; color: #0f172a; }
        .business-detail { font-size: 12px; color: #64748b; }
        .bill-to { background: #f8fafc; border-radius: 8px; padding: 15px; margin-bottom: 30px; }
        .bill-to-name { font-size: 15px; font-weight: 700; color: #0f172a; }
        .bill-to-detail { font-size: 12px; color: #64748b; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        table.items thead th { background: {{ $invoice->brand_color }}; color: white; padding: 10px 12px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        table.items thead th:first-child { border-radius: 6px 0 0 0; }
        table.items thead th:last-child { border-radius: 0 6px 0 0; }
        table.items tbody td { padding: 10px 12px; border-bottom: 1px solid #e2e8f0; }
        table.items tbody tr:last-child td { border-bottom: none; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .mono { font-family: 'Courier New', monospace; }
        .totals { width: 280px; float: right; }
        .totals::after { content: ''; display: table; clear: both; }
        .total-row { display: flex; padding: 6px 0; font-size: 13px; }
        .total-row .label { width: 55%; float: left; color: #64748b; }
        .total-row .value { width: 45%; float: right; text-align: right; font-weight: 500; }
        .total-row::after { content: ''; display: table; clear: both; }
        .total-final { border-top: 2px solid #0f172a; margin-top: 8px; padding-top: 10px; }
        .total-final .label { font-size: 16px; font-weight: 700; color: #0f172a; }
        .total-final .value { font-size: 18px; font-weight: 800; color: {{ $invoice->brand_color }}; }
        .notes-section { clear: both; margin-top: 60px; padding-top: 20px; border-top: 1px solid #e2e8f0; }
        .notes-title { font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
        .notes-text { font-size: 11px; color: #64748b; }
        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #94a3b8; }
        .logo { max-height: 50px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="brand-bar"></div>

        <div class="header">
            <div class="header-left">
                @if($invoice->user->logo_path)
                    <img src="{{ storage_path('app/public/' . $invoice->user->logo_path) }}" class="logo" alt="Logo">
                @endif
                <div class="business-name">{{ $invoice->user->display_name }}</div>
                @if($invoice->user->address)<div class="business-detail">{{ $invoice->user->address }}</div>@endif
                @if($invoice->user->city)<div class="business-detail">{{ $invoice->user->city }}, {{ $invoice->user->country }}</div>@endif
                @if($invoice->user->phone)<div class="business-detail">{{ $invoice->user->phone }}</div>@endif
                <div class="business-detail">{{ $invoice->user->email }}</div>
            </div>
            <div class="header-right">
                <div class="invoice-title">{{ $invoice->type === 'receipt' ? 'Receipt' : 'Invoice' }}</div>
                <div class="invoice-number"># {{ $invoice->invoice_number }}</div>
                <div style="margin-top: 12px;">
                    <div class="business-detail">Issue Date: {{ $invoice->issue_date->format('M d, Y') }}</div>
                    @if($invoice->due_date)
                        <div class="business-detail">Due Date: {{ $invoice->due_date->format('M d, Y') }}</div>
                    @endif
                </div>
            </div>
        </div>

        @if($invoice->client_name)
            <div class="bill-to">
                <div class="meta-label">Bill To</div>
                <div class="bill-to-name">{{ $invoice->client_name }}</div>
                @if($invoice->client_email)<div class="bill-to-detail">{{ $invoice->client_email }}</div>@endif
                @if($invoice->client_address)<div class="bill-to-detail">{{ $invoice->client_address }}</div>@endif
            </div>
        @endif

        <table class="items">
            <thead>
                <tr>
                    <th class="text-left" style="width: 40px;">#</th>
                    <th class="text-left">Description</th>
                    <th class="text-right" style="width: 70px;">Qty</th>
                    <th class="text-right" style="width: 100px;">Price</th>
                    <th class="text-right" style="width: 110px;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $index => $item)
                    <tr>
                        <td class="mono" style="color: #94a3b8;">{{ $index + 1 }}</td>
                        <td>{{ $item->description }}</td>
                        <td class="text-right mono">{{ rtrim(rtrim(number_format($item->quantity, 2), '0'), '.') }}</td>
                        <td class="text-right mono">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right mono" style="font-weight: 600;">{{ number_format($item->amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div class="total-row">
                <div class="label">Subtotal</div>
                <div class="value mono">{{ $invoice->currency }} {{ number_format($invoice->subtotal, 2) }}</div>
            </div>
            @if($invoice->discount_amount > 0)
                <div class="total-row">
                    <div class="label">Discount {{ $invoice->discount_type === 'percentage' ? '('.$invoice->discount_value.'%)' : '' }}</div>
                    <div class="value mono" style="color: #ef4444;">-{{ $invoice->currency }} {{ number_format($invoice->discount_amount, 2) }}</div>
                </div>
            @endif
            @if($invoice->tax_amount > 0)
                <div class="total-row">
                    <div class="label">{{ $invoice->tax_label }} ({{ $invoice->tax_rate }}%)</div>
                    <div class="value mono">{{ $invoice->currency }} {{ number_format($invoice->tax_amount, 2) }}</div>
                </div>
            @endif
            <div class="total-row total-final">
                <div class="label">Total</div>
                <div class="value mono">{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</div>
            </div>
        </div>

        @if($invoice->notes || $invoice->terms)
            <div class="notes-section">
                @if($invoice->notes)
                    <div style="margin-bottom: 15px;">
                        <div class="notes-title">Notes</div>
                        <div class="notes-text">{{ $invoice->notes }}</div>
                    </div>
                @endif
                @if($invoice->terms)
                    <div>
                        <div class="notes-title">Terms & Conditions</div>
                        <div class="notes-text">{{ $invoice->terms }}</div>
                    </div>
                @endif
            </div>
        @endif

        <div class="footer">
            Generated by InvoiceFlash — Smart Invoice Generator
        </div>
    </div>
</body>
</html>
