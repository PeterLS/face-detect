@extends('layouts.main')
@section('content')
    <h1>New person</h1>

    <form action="{{ route('persons.store') }}" method="post">
        @include('persons._form')
    </form>
@endsection
