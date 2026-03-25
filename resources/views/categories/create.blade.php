@extends('layouts.app')

@section('title', 'Create Category · PMMS')
@section('page-title', 'Create category')

@section('content')
    <section class="panel">
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf
            @include('categories._form')
        </form>
    </section>
@endsection
