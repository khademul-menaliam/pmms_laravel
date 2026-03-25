@extends('layouts.app')

@section('title', 'Edit Given Money · PMMS')
@section('page-title', 'Edit given money')

@section('content')
    <section class="panel">
        <form method="POST" action="{{ route('given-loans.update', $loan) }}">
            @csrf
            @method('PUT')
            @include('given-loans._form')
        </form>
    </section>
@endsection
