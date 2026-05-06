<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    <div class="max-w-[1600px] mx-auto">
        <div class="space-y-10 pb-10">
            <!-- Welcome Section -->
            <div class="relative overflow-hidden bg-white rounded-3xl border border-slate-200 p-8 shadow-xl shadow-slate-200/30">
                <div class="absolute top-0 right-0 w-80 h-80 bg-brand opacity-[0.03] blur-[100px] -mr-40 -mt-40 rounded-full"></div>
                <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="max-w-2xl">
                        <h2 class="text-3xl font-black text-slate-900 mb-2">Welcome back, {{ explode(' ', auth()->user()->name)[0] }}!</h2>
                        <p class="text-slate-500 font-medium leading-relaxed">
                            You've earned <span class="text-brand font-bold">{{ auth()->user()->default_currency }} {{ number_format($paidThisMonth, 0) }}</span> this month. You're doing great!
                        </p>
                    </div>
                    <div class="flex shrink-0">
                        <a href="{{ route('invoices.create') }}" class="btn-primary shadow-xl shadow-brand/20 flex items-center gap-2 px-6">
                            <i data-lucide="plus-circle" class="w-5 h-5"></i>
                            New Invoice
                        </a>
                    </div>
                </div>
            </div>

            <!-- Metrics Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="stats-card">
                    <div class="stats-icon bg-emerald-500">
                        <i data-lucide="banknote" class="w-7 h-7 text-white"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Revenue</p>
                        <p class="text-2xl font-black text-slate-900 leading-none">
                            <span class="text-xs text-slate-400 mr-1">{{ auth()->user()->default_currency }}</span>{{ number_format($totalRevenue, 0) }}
                        </p>
                    </div>
                </div>

                <div class="stats-card">
                    <div class="stats-icon bg-amber-500">
                        <i data-lucide="clock" class="w-7 h-7 text-white"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Pending</p>
                        <p class="text-2xl font-black text-slate-900 leading-none">
                            <span class="text-xs text-slate-400 mr-1">{{ auth()->user()->default_currency }}</span>{{ number_format($pendingAmount, 0) }}
                        </p>
                    </div>
                </div>

                <div class="stats-card">
                    <div class="stats-icon bg-brand">
                        <i data-lucide="file-text" class="w-7 h-7 text-white"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Invoices</p>
                        <p class="text-2xl font-black text-slate-900 leading-none">{{ $totalInvoices }}</p>
                    </div>
                </div>

                <div class="stats-card">
                    <div class="stats-icon {{ $overdueCount > 0 ? 'bg-red-500' : 'bg-slate-400' }}">
                        <i data-lucide="alert-circle" class="w-7 h-7 text-white"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Overdue</p>
                        <p class="text-2xl font-black {{ $overdueCount > 0 ? 'text-red-600' : 'text-slate-900' }} leading-none">{{ $overdueCount }}</p>
                    </div>
                </div>
            </div>

            <!-- Content Split -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">
                <!-- Recent Invoices -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="flex items-center justify-between px-2">
                        <h3 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                            <span class="w-1.5 h-6 bg-brand rounded-full"></span>
                            Recent Activity
                        </h3>
                        <a href="{{ route('invoices.index') }}" class="text-[10px] font-black text-brand uppercase tracking-widest hover:translate-x-1 transition-transform flex items-center gap-2">
                            View All History
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </a>
                    </div>

                    @if($recentInvoices->count() > 0)
                        <div class="table-container">
                            <div class="overflow-x-auto">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Invoice</th>
                                            <th>Client</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentInvoices as $invoice)
                                            <tr class="group cursor-pointer" onclick="window.location='{{ route('invoices.show', $invoice) }}'">
                                                <td class="font-mono font-bold text-slate-900">
                                                    <span class="text-slate-300">#</span>{{ $invoice->invoice_number }}
                                                </td>
                                                <td>
                                                    <div class="flex flex-col">
                                                        <span class="font-bold text-slate-900">{{ $invoice->client_name ?: 'Walk-in Client' }}</span>
                                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $invoice->issue_date->format('d M, Y') }}</span>
                                                    </div>
                                                </td>
                                                <td class="font-mono font-bold text-slate-900">{{ $invoice->getFormattedTotalAttribute() }}</td>
                                                <td>
                                                    <span class="badge-{{ $invoice->status_color }}">
                                                        <div class="status-dot-{{ $invoice->status === 'paid' ? 'paid' : ($invoice->status === 'overdue' ? 'overdue' : ($invoice->status === 'sent' ? 'pending' : 'draft')) }}"></div>
                                                        {{ $invoice->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-[2rem] border-2 border-dashed border-slate-200 p-16 text-center shadow-inner">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                                <i data-lucide="file-plus" class="w-10 h-10"></i>
                            </div>
                            <h4 class="text-lg font-black text-slate-900 mb-2">No activity yet</h4>
                            <p class="text-slate-400 mb-8 max-w-[240px] mx-auto">Generate your first invoice to see recent activity here.</p>
                            <a href="{{ route('invoices.create') }}" class="btn-primary px-8">Create First Invoice</a>
                        </div>
                    @endif
                </div>

                <!-- Sidebar Actions -->
                <div class="space-y-10">
                    <div class="space-y-6">
                        <h3 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-3 px-2">
                            <span class="w-1.5 h-6 bg-slate-900 rounded-full"></span>
                            Quick Actions
                        </h3>
                        <div class="space-y-4">
                            <a href="{{ route('invoices.create') }}" class="card p-6 flex items-center gap-5 hover:border-brand hover:shadow-xl hover:shadow-brand/5 transition-all group">
                                <div class="w-12 h-12 bg-orange-50 text-brand rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i data-lucide="plus-square" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <p class="font-black text-slate-900 leading-none mb-1">New Invoice</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Generate PDF</p>
                                </div>
                            </a>
                            <a href="{{ route('receipts.create') }}" class="card p-6 flex items-center gap-5 hover:border-emerald-500 hover:shadow-xl hover:shadow-emerald-500/5 transition-all group">
                                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i data-lucide="receipt" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <p class="font-black text-slate-900 leading-none mb-1">Quick Receipt</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">For Cash Sales</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Growth Widget -->
                    <div class="bg-slate-900 rounded-[2rem] p-8 text-white relative overflow-hidden shadow-2xl shadow-slate-900/40">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-brand opacity-10 blur-3xl -mr-16 -mt-16 rounded-full"></div>
                        <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 mb-8 flex items-center gap-2">
                            <i data-lucide="trending-up" class="w-4 h-4 text-brand"></i>
                            Monthly Goal
                        </h4>
                        
                        <div class="space-y-8">
                            <div>
                                <div class="flex justify-between items-end mb-3">
                                    <span class="text-xs font-black text-slate-400 uppercase">Revenue Progress</span>
                                    <span class="text-sm font-black text-white">{{ number_format(($paidThisMonth / 100000) * 100, 0) }}%</span>
                                </div>
                                <div class="w-full h-3 bg-white/10 rounded-full overflow-hidden">
                                    <div class="h-full bg-brand rounded-full transition-all duration-1000 shadow-[0_0_15px_rgba(255,107,0,0.5)]" style="width: {{ min(($paidThisMonth / 100000) * 100, 100) }}%"></div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-white/5 border border-white/5 rounded-2xl p-5">
                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Receipts</p>
                                    <p class="text-2xl font-black text-white">{{ $totalReceipts }}</p>
                                </div>
                                <div class="bg-white/5 border border-white/5 rounded-2xl p-5">
                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Invoices</p>
                                    <p class="text-2xl font-black text-emerald-400">{{ $totalInvoices }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
