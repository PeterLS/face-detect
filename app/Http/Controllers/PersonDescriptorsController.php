<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\PersonDescriptor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PersonDescriptorsController extends Controller {
    public function index(Person $person): View {
        return \view('persons.descriptors.index', compact('person'));
    }

    public function create(Person $person): View {
        return \view('persons.descriptors.create', compact('person'));
    }

    public function store(Request $request, Person $person): RedirectResponse {
        $this->validate($request, [
            'photo' => 'required|image',
            'descriptor' => 'required|string|unique:person_descriptors,descriptor',
        ]);

        $image = Storage::disk('public')->put('/faces', $request->file('photo'));

        $descriptor = $person->descriptors()->create([
            'descriptor' => $request->post('descriptor'),
            'url' => $image,
        ]);

        if (empty($descriptor->id)) {
            return back();
        }

        return redirect()->route('persons.descriptors.index', $person);
    }

    public function destroy(Person $person, PersonDescriptor $descriptor): RedirectResponse {
        $descriptor->delete();
        return back();
    }

    public function apiGetAllDescriptors(): array {
        $result = [];
        foreach (Person::where('is_active', TRUE)->get() as $person) {
            $personResult = [];
            foreach ($person->descriptors as $descriptor) {
                $personResult[] = collect(explode(',', $descriptor->descriptor))->map(static function (string $item): float {
                    return (float)$item;
                })->toArray();
            }

            $result[] = [
                'label' => $person->full_name,
                'descriptors' => $personResult,
            ];
        }

        return $result;
    }
}
