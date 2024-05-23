<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
use Illuminate\Support\Facades\DB;


class BookController extends Controller
{
    public function index()
    {
        $books = Book::query()->available()->with(['authors', 'publisher'])->get();

        return response()->json(['data' => $books], 200);
    }


    public function store(Request $request)
    {
        // Sprawdź, czy książka o danym ISBN już istnieje
        $existingBook = Book::where('isbn', $request->input('isbn'))->first();

        if ($existingBook) {
            return response()->json(['message' => 'Książka o podanym ISBN już istnieje.'], 422);
        }

        // Walidacja danych z formularza lub JSONa
        $validatedData = $request->validate([
            'isbn' => 'required|unique:books,isbn',
            'title' => 'required|string',
            'description' => 'required|string',
            'published_date' => 'required|date',
            'publisher_name' => 'required|string',
            'pages' => 'required|integer|min:1',
            'language' => 'required|string',
            'authors' => 'required|array',
        ]);

        // Sprawdź, czy wydawca istnieje
        $publisher = Publisher::where('name', $request->input('publisher_name'))->first();

        if (!$publisher) {
            // Jeśli nie istnieje, stwórz nowego wydawcę
            $publisher = Publisher::create(['name' => $request->input('publisher_name')]);
        }

        // Pobierz id wydawcy
        $publisherId = $publisher->publisher_id;

        // Tworzenie książki
        $book = Book::create([
            'isbn' => $validatedData['isbn'],
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'published_date' => $validatedData['published_date'],
            'publisher_id' => $publisherId,
            'pages' => $validatedData['pages'],
            'language' => $validatedData['language'],
        ]);

        // Dodaj połączenia między książką a autorami
        $authors = [];

        foreach ($request->input('authors') as $authorName) {
            // Sprawdź, czy autor istnieje
            $author = Author::where('name', $authorName)->first();

            if (!$author) {
                // Jeśli nie istnieje, stwórz nowego autora
                $author = Author::create(['name' => $authorName]);
            }

            $authors[] = $author->author_id;
        }

        // Dodaj połączenie
        $book->authors()->attach($authors);

        return response()->json(['message' => 'Książka została pomyślnie dodana.'], 201);
    }

    public function update(Request $request, $isbn)
    {
        // Walidacja danych z formularza lub JSONa
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'published_date' => 'required|date',
            'publisher_name' => 'required|string',
            'pages' => 'required|integer|min:1',
            'language' => 'required|string',
            'authors' => 'required|array',
        ]);

        // Sprawdź, czy książka istnieje na podstawie numeru ISBN
        $book = Book::where('isbn', $isbn)->first();

        if (!$book) {
            return response()->json(['error' => 'Książka o podanym numerze ISBN nie istnieje.'], 404);
        }

        // Sprawdź, czy wydawca istnieje
        $publisher = Publisher::where('name', $request->input('publisher_name'))->first();

        if (!$publisher) {
            // Jeśli nie istnieje, stwórz nowego wydawcę
            $publisher = Publisher::create(['name' => $request->input('publisher_name')]);
        }

        // Pobierz id wydawcy
        $publisherId = $publisher->publisher_id;

        // Zaktualizuj dane książki
        $book->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'published_date' => $validatedData['published_date'],
            'publisher_id' => $publisherId,
            'pages' => $validatedData['pages'],
            'language' => $validatedData['language'],
        ]);

        // Zaktualizuj połączenia między książką a autorami
        $authors = [];

        foreach ($validatedData['authors'] as $authorName) {
            $author = Author::firstOrCreate(['name' => $authorName]);
            $authors[] = $author->author_id;
        }

        $book->authors()->sync($authors);

        return response()->json(['message' => 'Książka została pomyślnie zaktualizowana.'], 200);
    }

    public function show($isbn)
    {
        // Pobierz książkę wraz z autorami
        $book = Book::query()->available()->where('isbn', $isbn)->with('authors')->first();

        // Jeśli książka nie istnieje, zwróć błąd 404
        if (!$book) {
            return response()->json(['message' => 'Książka nie znaleziona.'], 404);
        }
        

        // Zwróć książkę wraz z imionami autorów
        return response()->json(['data' => $book], 200);
    }

    public function destroy($isbn)
    {
        $book = Book::where('isbn', $isbn)->first();

        if (!$book) {
            return response()->json(['message' => 'Książka nie znaleziona.'], 404);
        }

        // Usuń połączenia z autorami
        $book->authors()->detach();

        // Usuń książkę
        $book->delete();

        return response()->json(['message' => 'Książka została pomyślnie usunięta.'], 200);
    }

    public function search(Request $request)
    {
        // Pobierz zapytanie wyszukiwania z parametru żądania
        $searchQuery = $request->input('query');

        // Wyszukaj książki po nazwie lub autorze
        $books = Book::query()
        ->available()->with(['authors', 'publisher'])->where('title', 'ILIKE', "%$searchQuery%")
                    ->orWhereHas('authors', function ($query) use ($searchQuery) {
                        $query->where('name', 'ILIKE', "%$searchQuery%");
                    })
                    ->get();

        // Sprawdź, czy znaleziono wyniki
        if ($books->isEmpty()) {
            return response()->json(['message' => 'Brak wyników wyszukiwania.', 'data' => $books], 404);
        }

        // Zwróć znalezione książki
        return response()->json(['data' => $books], 200);
    }



}
