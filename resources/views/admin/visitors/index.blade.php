@extends('layouts.admin')

@section('title', 'Analytics')
@section('page_heading', 'Visitor analytics')

@section('content')
<x-admin.page-header title="Analytics" description="Live stats from your visitor tracking middleware." />

<div class="grid gap-4 sm:grid-cols-3 mb-8">
    <div class="stat-card"><p class="text-sm text-text-dim">Unique visitors</p><p class="mt-1 font-display text-2xl font-bold text-uv-bright" id="stat-visitors">-</p></div>
    <div class="stat-card"><p class="text-sm text-text-dim">Page views</p><p class="mt-1 font-display text-2xl font-bold" id="stat-views">-</p></div>
    <div class="stat-card"><p class="text-sm text-text-dim">Today</p><p class="mt-1 font-display text-2xl font-bold" id="stat-today">-</p></div>
</div>

<div class="card-surface p-5">
    <p class="text-sm text-text-muted mb-4">Detailed charts load via API. Use export for raw data.</p>
    <a href="{{ route('admin.visitors.export') }}" class="btn-secondary">Export CSV</a>
</div>

@push('scripts')
<script>
fetch('{{ route('admin.visitors.stats') }}').then(r=>r.json()).then(d=>{
    document.getElementById('stat-visitors').textContent = d.unique_visitors ?? d.total_visitors ?? '-';
    document.getElementById('stat-views').textContent = d.total_page_views ?? '-';
});
fetch('{{ route('admin.visitors.daily-stats') }}').then(r=>r.json()).then(d=>{
    const today = Array.isArray(d) ? d.find(x => x.date === new Date().toISOString().slice(0,10)) : null;
    document.getElementById('stat-today').textContent = today?.visitors ?? today?.count ?? '-';
}).catch(()=>{});
</script>
@endpush
@endsection
