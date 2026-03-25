@extends('layouts.app')

@section('title', 'Edit Category · PMMS')
@section('page-title', 'Edit category')

@section('content')
    <section class="panel">
        <form method="POST" action="{{ route('categories.update', $category) }}">
            @csrf
            @method('PUT')
            @include('categories._form')
        </form>
    </section>
@endsection
