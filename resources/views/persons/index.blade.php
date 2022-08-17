@extends('layouts.main')
@section('content')
    <h1>Persons</h1>

    <a href="{{ route('persons.create') }}" class="btn btn-primary btn-sm mb-3">Add person</a>

    @foreach($persons as $person)
        <div class="card mb-3 shadow-sm">
            <div class="card-header">
                {{ $person->last_name }}
                {{ $person->first_name }}

                <span @class(['badge', 'bg-success' => $person->is_active, 'bg-danger' => !$person->is_active])>
                    {{ $person->is_active ? 'включен' : 'выключен' }}
                </span>
            </div>

            <div class="card-body d-flex align-items-center">
                <img src="{{ Storage::url($person->descriptors()->first()?->url) }}" alt="{{ $person->name }}"
                     style="height:100px" class="me-3"/>

                <a href="{{ route('persons.descriptors.index', $person) }}" class="me-auto">
                    {{ $person->descriptors_count }} descriptors
                </a>

                <div class="btn-group btn-group-sm">
                    <a href="{{ route('persons.edit', $person) }}" class="btn btn-primary">Edit</a>
                    <button type="submit" form="{{ $person->id }}_delete" class="btn btn-sm btn-danger">
                        Delete
                    </button>
                </div>
                <form action="{{ route('persons.destroy', $person) }}"
                      id="{{ $person->id }}_delete" method="post">
                    @method('DELETE')
                    @csrf
                </form>
            </div>
        </div>
    @endforeach
@endsection
