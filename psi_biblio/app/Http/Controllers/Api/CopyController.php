<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Copy;
use App\Models\Book;
use Illuminate\Http\Request;

class CopyController extends Controller
{
    public function index()
    {
        $copies = Copy::all();

        return response()->json(['copies' => $copies], 200);
    }

    public function store(Request $request)
    {
        try {
            // czy istnieje książka
            $book = Book::where('title_id', $request->title_id)->first();
            if(!$book)
                return response()->json(['error' => 'Podana książka nie istnieje.'], 400);
            
            // sprawdzenie czy już taki egzemplarz tej samej ed
            $copy = Copy::where('copy_id', $request->copy_id)->first();
            if ($copy) {
                return response()->json(['error' => 'Egzemplarz o podanym numerze już istnieje.'], 400);
            }
            // Walidacja danych
            $validatedData = $request->validate([
                'copy_id' => 'required|string|min:10|unique:copies,copy_id',
                'title_id' => 'required|exists:books,title_id',
            ]);
    
            // Dodanie egzemplarza do bazy danych
            $copy = Copy::create([
                'copy_id' => $validatedData['copy_id'],
                'title_id' => $validatedData['title_id'],
                'available' => true, // Ustawienie dostępności na true
            ]);
    
            return response()->json(['message' => 'Egzemplarz został pomyślnie dodany.'], 201);
        }
        catch (\Exception $e) {
            // Obsługa błędów
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    public function showForBook($title_id)
    {
        $copies = Copy::where('title_id', $title_id)->get();

        return response()->json(['copies' => $copies], 200);
    }

    public function show($copy_id)
    {
        $copy = Copy::findOrFail($copy_id);

        return response()->json(['copy' => $copy], 200);
    }

    public function update(Request $request, $id)
    {
        $copy = Copy::findOrFail($id);
        
        $validatedData = $request->validate([
            'title_id' => 'required|exists:books,title_id',
            'available' => 'required|boolean',
        ]);

        $copy->update($validatedData);

        return response()->json(['copy' => $copy], 200);
    }

    public function destroy($id)
    {
        $copy = Copy::findOrFail($id);
        $copy->delete();

        return response()->json(['message' => 'Egzemplarz został pomyślnie usunięty.'], 200);
    }
}
