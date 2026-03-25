@extends('layouts.app')

@section('title', 'Edit Expense · PMMS')
@section('page-title', 'Edit expense')

@section('content')
    <section class="panel">
        <form method="POST" action="{{ route('expenses.update', $expense) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('expenses._form')
        </form>
    </section>
@endsection
