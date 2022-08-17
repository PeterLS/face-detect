@extends('layouts.main')
@section('content')
    <h1>Descriptors of {{ $person->last_name }} {{ $person->forst_name }}</h1>

    <div class="btn-group btn-group-sm mb-3">
        <a href="{{ route('persons.index') }}" class="btn btn-outline-primary">Back</a>
        <a href="{{ route('persons.descriptors.create', $person) }}" class="btn btn-primary">Add descriptor</a>
    </div>

    @foreach($person->descriptors as $descriptor)
        <div class="card mb-3 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <img src="{{ Storage::url($descriptor->url) }}" alt="{{ $person->name }}"
                     style="height:100px"/>

                <div class="btn-group ms-auto">
                    <button type="submit" form="{{ $descriptor->id }}_delete" class="btn btn-sm btn-danger">
                        Delete
                    </button>
                </div>
                <form action="{{ route('persons.descriptors.destroy', [$person, $descriptor]) }}"
                      id="{{ $descriptor->id }}_delete" method="post">
                    @method('DELETE')
                    @csrf
                </form>
            </div>
        </div>
    @endforeach
@endsection
