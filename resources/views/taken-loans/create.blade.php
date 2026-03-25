@extends('layouts.app')

@section('title', 'Add Taken Money · PMMS')
@section('page-title', 'Add taken money')

@section('content')
    <section class="panel">
        <form method="POST" action="{{ route('taken-loans.store') }}">
            @csrf
            @include('taken-loans._form')
        </form>
    </section>
@endsection
