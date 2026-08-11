@extends('layouts.admin')

@section('title', 'New Blog Post')
@section('page_heading', 'New post')

@section('content')
<x-admin.page-header title="New blog post" description="Markdown with live Cursor-style preview. Cover ~1200×630 for share cards." />

<form method="POST" action="{{ route('admin.blog-posts.store') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @include('admin.blog-posts._form', ['post' => null, 'defaultAuthor' => $defaultAuthor])
</form>
@endsection
