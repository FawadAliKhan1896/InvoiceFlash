<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $invoice->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; line-height: 1.5; }
        .banner { background: #1e293b; color: white; padding: 30px 40px; }
        .banner-left { width: 50%; float: left; }
        .banner-right { width: 50%; float: right; text-align: right; }
        .banner::after { content: ''; display: table; clear: both; }
        .banner .title { font-size: 28px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; }
        .banner .subtitle { font-size: 12px; color: #94a3b8; margin-top: 3px; }
        .banner .biz-name { font-size: 16px; font-weight: 700; }
        .banner .biz-detail { font-size: 11px; color: #94a3b8; }
        .content { padding: 30px 40px; }
        .meta-row { background: #f1f5f9; padding: 15px 20px; border-radius: 6px; margin-bottom: 25px; }
        .meta-row::after { content: ''; display: table; clear: both; }
        .meta-item { width: 25%; float: left; }
        .meta-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; }
        .meta-value { font-size: 13px; font-weight: 600; color: #1e293b; margin-top: 2px; }
        .client-box { border-left: 3px solid #1e293b; padding-left: 15px; margin-bottom: 30px; }
        .client-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 3px; }
        .client-name { font-size: 15px; font-weight: 700; color: #1e293b; }
        .client-detail { font-size: 11px; color: #64748b; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        table.items thead th { background: #1e293b; color: white; padding: 10px 12px; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        table.items tbody td { padding: 10px 12px; border-bottom: 1px solid #e2e8f0; }
        table.items tbody tr:nth-child(even) td { background: #f8fafc; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .mono { font-family: 'Courier New', monospace; }
        .totals { width: 280px; float: right; border: 1px solid #e2e8f0; border-radius: 6px; overflow: hidden; }
        .totals::after { content: ''; display: table; clear: both; }
        .total-row { padding: 8px 15px; }
        .total-row .tl { width: 50%; float: left; font-size: 12px; color: #64748b; }
        .total-row .tv { width: 50%; float: right; text-align: right; font-size: 12px; font-weight: 500; }
        .total-row::after { content: ''; display: table; clear: both; }
        .total-final { background: #1e293b; color: white; }
        .total-final .tl { color: #94a3b8; font-weight: 600; }
        .total-final .tv { color: white; font-size: 16px; font-weight: 700; }
        .notes-section { clear: both; margin-top: 50px; padding-top: 20px; border-top: 1px solid #e2e8f0; }
        .notes-title { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 4px; }
        .notes-text { font-size: 11px; color: #64748b; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #94a3b8; padding-top: 15px; border-top: 1px solid #e2e8f0; }
        .logo { max-height: 40px; margin-bottom: 8px; }
    </style>
</head>
<body>
    <div class="banner">
        <div class="banner-left">
            @if($invoice->user->logo_path)
                <img src="{{ storage_path('app/public/' . $invoice->user->logo_path) }}" class="logo" alt="Logo">
            @endif
            <div class="biz-name">{{ $invoice->user->display_name }}</div>
            @if($invoice->user->address)<div class="biz-detail">{{ $invoice->user->address }}</div>@endif
            @if($invoice->user->city)<div class="biz-detail">{{ $invoice->user->city }}, {{ $invoice->user->country }}</div>@endif
            <div class="biz-detail">{{ $invoice->user->email }}</div>
        </div>
        <div class="banner-right">
            <div class="title">{{ $invoice->type === 'receipt' ? 'Receipt' : 'Invoice' }}</div>
            <div class="subtitle"># {{ $invoice->invoice_number }}</div>
        </div>
    </div>

    <div class="content">
        <div class="meta-row">
            <div class="meta-item">
                <div class="meta-label">Issue Date</div>
                <div class="meta-value">{{ $invoice->issue_date->format('M d, Y') }}</div>
            </div>
            @if($invoice->due_date)
                <div class="meta-item">
                    <div class="meta-label">Due Date</div>
                    <div class="meta-value">{{ $invoice->due_date->format('M d, Y') }}</div>
                </div>
            @endif
            <div class="meta-item">
                <div class="meta-label">Status</div>
                <div class="meta-value">{{ ucfirst($invoice->status) }}</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">Currency</div>
                <div class="meta-value">{{ $invoice->currency }}</div>
            </div>
        </div>

        @if($invoice->client_name)
            <div class="client-box">
                <div class="client-label">Bill To</div>
                <div class="client-name">{{ $invoice->client_name }}</div>
                @if($invoice->client_email)<div class="client-detail">{{ $invoice->client_email }}</div>@endif
                @if($invoice->client_address)<div class="client-detail">{{ $invoice->client_address }}</div>@endif
            </div>
        @endif

        <table class="items">
            <thead>
                <tr>
                    <th class="text-left" style="width: 35px;">#</th>
                    <th class="text-left">Description</th>
                    <th class="text-right" style="width: 65px;">Qty</th>
                    <th class="text-right" style="width: 95px;">Unit Price</th>
                    <th class="text-right" style="width: 105px;">Amount</th>
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
            <div class="total-row"><div class="tl">Subtotal</div><div class="tv mono">{{ $invoice->currency }} {{ number_format($invoice->subtotal, 2) }}</div></div>
            @if($invoice->discount_amount > 0)
                <div class="total-row"><div class="tl">Discount</div><div class="tv mono" style="color: #ef4444;">-{{ $invoice->currency }} {{ number_format($invoice->discount_amount, 2) }}</div></div>
            @endif
            @if($invoice->tax_amount > 0)
                <div class="total-row"><div class="tl">{{ $invoice->tax_label }} ({{ $invoice->tax_rate }}%)</div><div class="tv mono">{{ $invoice->currency }} {{ number_format($invoice->tax_amount, 2) }}</div></div>
            @endif
            <div class="total-row total-final"><div class="tl">Total Due</div><div class="tv mono">{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</div></div>
        </div>

        @if($invoice->notes || $invoice->terms)
            <div class="notes-section">
                @if($invoice->notes)<div style="margin-bottom: 12px;"><div class="notes-title">Notes</div><div class="notes-text">{{ $invoice->notes }}</div></div>@endif
                @if($invoice->terms)<div><div class="notes-title">Terms & Conditions</div><div class="notes-text">{{ $invoice->terms }}</div></div>@endif
            </div>
        @endif

        <div class="footer">Generated by InvoiceFlash — Smart Invoice Generator</div>
    </div>
</body>
</html>
