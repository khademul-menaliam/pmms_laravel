@extends('layouts.app')

@section('title', 'Add Expense · PMMS')
@section('page-title', 'Add expense')

@section('content')
    <section class="panel">
        <form method="POST" action="{{ route('expenses.store') }}" enctype="multipart/form-data">
            @csrf
            @include('expenses._form')
        </form>
    </section>
@endsection
