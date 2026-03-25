@extends('layouts.app')

@section('title', 'Edit Income · PMMS')
@section('page-title', 'Edit income')

@section('content')
    <section class="panel">
        <form method="POST" action="{{ route('incomes.update', $income) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('incomes._form')
        </form>
    </section>
@endsection
