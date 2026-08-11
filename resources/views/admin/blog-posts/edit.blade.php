@extends('layouts.admin')

@section('title', 'Edit Blog Post')
@section('page_heading', 'Edit post')

@section('content')
<x-admin.page-header title="Edit blog post" description="{{ $post->title }}" />

<form method="POST" action="{{ route('admin.blog-posts.update', $post) }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')
    @include('admin.blog-posts._form', ['post' => $post, 'defaultAuthor' => $post->author_name])
</form>
@endsection
