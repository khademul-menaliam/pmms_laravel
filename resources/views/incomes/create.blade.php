@extends('layouts.app')

@section('title', 'Add Income · PMMS')
@section('page-title', 'Add income')

@section('content')
    <section class="panel">
        <form method="POST" action="{{ route('incomes.store') }}" enctype="multipart/form-data">
            @csrf
            @include('incomes._form')
        </form>
    </section>
@endsection
