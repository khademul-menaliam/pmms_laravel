@extends('layouts.app')

@section('title', 'Edit Reminder · PMMS')
@section('page-title', 'Edit reminder')

@section('content')
    <section class="panel">
        <form method="POST" action="{{ route('reminders.update', $reminder) }}">
            @csrf
            @method('PUT')
            @include('reminders._form')
        </form>
    </section>
@endsection
