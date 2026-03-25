@extends('layouts.app')

@section('title', 'Edit Taken Money · PMMS')
@section('page-title', 'Edit taken money')

@section('content')
    <section class="panel">
        <form method="POST" action="{{ route('taken-loans.update', $loan) }}">
            @csrf
            @method('PUT')
            @include('taken-loans._form')
        </form>
    </section>
@endsection
