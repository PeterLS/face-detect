@extends('layouts.main')
@section('content')
    <h1>Edit person {{ $person->last_name }} {{ $person->first_name }}</h1>

    <form action="{{ route('persons.update', $person) }}" method="post">
        @method('PUT')
        @include('persons._form', compact('person'))
    </form>
@endsection
