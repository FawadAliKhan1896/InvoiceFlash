<x-app-layout>
    <x-slot name="header">Clients</x-slot>

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <form method="GET" x-data="{ submit() { $el.submit() } }" class="flex-1 max-w-md">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                       @input.debounce.500ms="submit()"
                       class="form-input pl-10" placeholder="Search clients...">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
            </div>
        </form>
        <a href="{{ route('clients.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Client
        </a>
    </div>

    @if($clients->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($clients as $client)
                <div class="card p-6 flex flex-col justify-between hover:border-primary-500 transition-all group">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded bg-slate-100 flex items-center justify-center text-slate-600 font-bold">
                                {{ strtoupper(substr($client->name, 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 group-hover:text-primary-600 transition-colors">{{ $client->name }}</h3>
                                <p class="text-xs text-slate-500">{{ $client->email ?: 'No email' }}</p>
                            </div>
                        </div>
                        <div class="flex gap-1">
                            <a href="{{ route('clients.edit', $client) }}" class="p-2 text-slate-400 hover:text-blue-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                            </a>
                        </div>
                    </div>
                    
                    <div class="space-y-2 mb-4">
                        @if($client->phone)
                            <p class="text-xs text-slate-500 flex items-center gap-2">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                                {{ $client->phone }}
                            </p>
                        @endif
                        @if($client->city)
                            <p class="text-xs text-slate-500 flex items-center gap-2">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                {{ $client->city }}, {{ $client->country }}
                            </p>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $client->invoices_count }} Invoices</span>
                        <a href="{{ route('invoices.create', ['client_id' => $client->id]) }}" class="text-xs font-bold text-primary-600 hover:text-primary-700">New Invoice +</a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $clients->withQueryString()->links() }}</div>
    @else
        <div class="card p-12 text-center">
            <p class="text-slate-400 mb-4 font-medium">No clients found matching your search.</p>
            <a href="{{ route('clients.create') }}" class="btn-primary">Add Your First Client</a>
        </div>
    @endif
</x-app-layout>
