<?php
// app/Http/Controllers/Api/PublisherController.php

namespace App\Http\Controllers\Api;

use App\Models\Publisher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class PublisherController extends Controller
{
    public function index()
    {
        $publishers = Publisher::all();

        return response()->json($publishers, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:publishers,name',
            'description' => 'nullable|string',
        ]);

        $publisher = Publisher::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        return response()->json($publisher, 201);
    }

    public function update(Request $request, $id)
    {
        // Sprawdź, czy wydawca istnieje
        $publisher = Publisher::find($id);

        if (!$publisher) {
            return response()->json(['message' => 'Wydawca nie istnieje.'], 404);
        }
        // Walidacja danych z formularza lub JSONa

        // $validatedData = $request->validate([
        //     'name' => 'required|string',
        //     'description' => 'string', // Możesz dostosować walidację opisu
        // ]);
        try {
            $validatedData = $request->validate([
                'name' => [
                    'required',
                    'string',
                    Rule::unique('publishers')->ignore($id, 'publisher_id'),
                ],
                'description' => 'string', // Adjust validation for description as needed
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Publisher with the given name already exists.'], 400);
        }

        

        // Aktualizuj dane wydawcy
        $publisher->update($validatedData);

        return response()->json(['message' => 'Dane wydawcy zostały zaktualizowane.','publisher'=>$publisher], 200);
    }

    public function show($id)
    {
        $publisher = Publisher::find($id);

        if (!$publisher) {
            return response()->json(['message' => 'Publisher not found'], 404);
        }

        return response()->json($publisher, 200);
    }

    public function destroy($id)
    {
        $publisher = Publisher::find($id);

        if (!$publisher) {
            return response()->json(['message' => 'Publisher not found'], 404);
        }

        $publisher->delete();

        return response()->json(['message' => 'Publisher deleted successfully'], 200);
    }
}
