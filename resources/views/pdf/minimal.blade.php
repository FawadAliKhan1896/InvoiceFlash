<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $invoice->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; line-height: 1.6; }
        .container { padding: 50px 60px; }
        .header { margin-bottom: 50px; }
        .header-left { width: 50%; float: left; }
        .header-right { width: 50%; float: right; text-align: right; }
        .header::after { content: ''; display: table; clear: both; }
        .invoice-title { font-size: 11px; font-weight: 400; text-transform: uppercase; letter-spacing: 6px; color: #999; }
        .invoice-number { font-size: 13px; color: #666; margin-top: 4px; }
        .business-name { font-size: 20px; font-weight: 300; color: #111; letter-spacing: 1px; }
        .detail { font-size: 11px; color: #999; margin-top: 2px; }
        .divider { border: none; border-top: 1px solid #eee; margin: 25px 0; }
        .bill-section { margin-bottom: 40px; }
        .label { font-size: 9px; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; color: #bbb; margin-bottom: 6px; }
        .client-name { font-size: 14px; font-weight: 600; color: #111; }
        .client-detail { font-size: 11px; color: #999; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        table.items thead th { padding: 10px 0; font-size: 9px; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; color: #bbb; border-bottom: 1px solid #ddd; }
        table.items tbody td { padding: 12px 0; border-bottom: 1px solid #f5f5f5; font-size: 12px; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .mono { font-family: 'Courier New', monospace; }
        .totals { width: 250px; float: right; }
        .totals::after { content: ''; display: table; clear: both; }
        .total-row { padding: 5px 0; }
        .total-row .tl { width: 50%; float: left; font-size: 11px; color: #999; }
        .total-row .tv { width: 50%; float: right; text-align: right; font-size: 12px; }
        .total-row::after { content: ''; display: table; clear: both; }
        .total-final { border-top: 1px solid #111; margin-top: 10px; padding-top: 10px; }
        .total-final .tl { font-size: 13px; color: #111; font-weight: 600; }
        .total-final .tv { font-size: 16px; font-weight: 700; color: #111; }
        .notes-section { clear: both; margin-top: 60px; }
        .notes-label { font-size: 9px; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; color: #bbb; margin-bottom: 5px; }
        .notes-text { font-size: 11px; color: #999; }
        .logo { max-height: 40px; margin-bottom: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-left">
                @if($invoice->user->logo_path)
                    <img src="{{ storage_path('app/public/' . $invoice->user->logo_path) }}" class="logo" alt="Logo">
                @endif
                <div class="business-name">{{ $invoice->user->display_name }}</div>
                @if($invoice->user->address)<div class="detail">{{ $invoice->user->address }}</div>@endif
                @if($invoice->user->city)<div class="detail">{{ $invoice->user->city }}, {{ $invoice->user->country }}</div>@endif
                @if($invoice->user->phone)<div class="detail">{{ $invoice->user->phone }}</div>@endif
                <div class="detail">{{ $invoice->user->email }}</div>
            </div>
            <div class="header-right">
                <div class="invoice-title">{{ $invoice->type === 'receipt' ? 'Receipt' : 'Invoice' }}</div>
                <div class="invoice-number">{{ $invoice->invoice_number }}</div>
                <div style="margin-top: 15px;">
                    <div class="detail">{{ $invoice->issue_date->format('F d, Y') }}</div>
                    @if($invoice->due_date)
                        <div class="detail">Due: {{ $invoice->due_date->format('F d, Y') }}</div>
                    @endif
                </div>
            </div>
        </div>

        <hr class="divider">

        @if($invoice->client_name)
            <div class="bill-section">
                <div class="label">Billed To</div>
                <div class="client-name">{{ $invoice->client_name }}</div>
                @if($invoice->client_email)<div class="client-detail">{{ $invoice->client_email }}</div>@endif
                @if($invoice->client_address)<div class="client-detail">{{ $invoice->client_address }}</div>@endif
            </div>
        @endif

        <table class="items">
            <thead>
                <tr>
                    <th class="text-left">Description</th>
                    <th class="text-right" style="width: 60px;">Qty</th>
                    <th class="text-right" style="width: 90px;">Rate</th>
                    <th class="text-right" style="width: 100px;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                    <tr>
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
                <div class="total-row"><div class="tl">Discount</div><div class="tv mono">-{{ $invoice->currency }} {{ number_format($invoice->discount_amount, 2) }}</div></div>
            @endif
            @if($invoice->tax_amount > 0)
                <div class="total-row"><div class="tl">{{ $invoice->tax_label }} ({{ $invoice->tax_rate }}%)</div><div class="tv mono">{{ $invoice->currency }} {{ number_format($invoice->tax_amount, 2) }}</div></div>
            @endif
            <div class="total-row total-final"><div class="tl">Total</div><div class="tv mono">{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</div></div>
        </div>

        @if($invoice->notes || $invoice->terms)
            <div class="notes-section">
                @if($invoice->notes)
                    <div style="margin-bottom: 15px;"><div class="notes-label">Notes</div><div class="notes-text">{{ $invoice->notes }}</div></div>
                @endif
                @if($invoice->terms)
                    <div><div class="notes-label">Terms</div><div class="notes-text">{{ $invoice->terms }}</div></div>
                @endif
            </div>
        @endif
    </div>
</body>
</html>
