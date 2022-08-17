<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PersonsController extends Controller {
    public function index(): View {
        $persons = Person::withCount('descriptors')->orderBy('last_name')->orderBy('first_name')->get();
        return \view('persons.index', compact('persons'));
    }

    public function create(): View {
        return \view('persons.create');
    }

    public function store(Request $request): RedirectResponse {
        $this->validate($request, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $person = new Person;
        $person->first_name = $request->input('first_name');
        $person->last_name = $request->input('last_name');
        $person->is_active = $request->boolean('is_active');

        if ($person->save()) {
            return redirect()->route('persons.index');
        }

        return back()->withInput();
    }

    public function edit(Person $person): View {
        return \view('persons.edit', compact('person'));
    }

    public function update(Request $request, Person $person): RedirectResponse {
        $this->validate($request, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $person->first_name = $request->input('first_name');
        $person->last_name = $request->input('last_name');
        $person->is_active = $request->boolean('is_active');

        if ($person->save()) {
            return redirect()->route('persons.index');
        }

        return back()->withInput();
    }

    public function destroy(Person $person): RedirectResponse {
        DB::beginTransaction();
        try {
            $person->descriptors()->delete();
            $person->delete();
            DB::commit();
        } catch (\Throwable) {
            DB::rollBack();
        }

        return back();
    }
}
