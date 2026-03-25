@extends('layouts.app')

@section('title', 'Add Given Money · PMMS')
@section('page-title', 'Add given money')

@section('content')
    <section class="panel">
        <form method="POST" action="{{ route('given-loans.store') }}">
            @csrf
            @include('given-loans._form')
        </form>
    </section>
@endsection
