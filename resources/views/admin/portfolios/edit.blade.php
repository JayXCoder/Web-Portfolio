@extends('layouts.admin')

@section('title', 'Edit Portfolio')
@section('page_heading', 'Edit portfolio')

@section('content')
<x-admin.page-header :title="$portfolio->title" description="Update project details or upload new images.">
    <x-slot:actions>
        <a href="{{ route('portfolio.item', $portfolio->slug) }}" target="_blank" class="btn-secondary">View live</a>
    </x-slot:actions>
</x-admin.page-header>

@include('admin.portfolios._form', [
    'portfolio' => $portfolio,
    'action' => route('admin.portfolios.update', $portfolio),
    'method' => 'PUT',
])

<form method="POST" action="{{ route('admin.portfolios.delete', $portfolio) }}" class="mt-8 max-w-3xl" onsubmit="return confirm('Delete this portfolio permanently?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn-secondary border-danger/50 text-red-300 hover:border-danger">Delete portfolio</button>
</form>
@endsection
