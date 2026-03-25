@extends('layouts.app')

@section('title', 'Add Reminder · PMMS')
@section('page-title', 'Add reminder')

@section('content')
    <section class="panel">
        <form method="POST" action="{{ route('reminders.store') }}">
            @csrf
            @include('reminders._form')
        </form>
    </section>
@endsection
