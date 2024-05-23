<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::all();

        return response()->json(['authors' => $authors], 200);
    }
    public function show($id)
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json(['message' => 'Autor nie znaleziony.'], 404);
        }

        return response()->json(['author' => $author], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:authors,name',
            'description' => 'nullable|string',
        ]);

        $author = Author::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
        ]);

        return response()->json(['message' => 'Autor został pomyślnie dodany.', 'author' => $author], 201);
    }

    public function update(Request $request, $author_id)
    {
        // $author = Author::find($id);
        $author = Author::where('author_id', $author_id)->first();

        if (!$author) {
            return response()->json(['message' => 'Autor nie znaleziony.'], 404);
        }

        // $validatedData = $request->validate([
        //     'name' => ['required', 'string', Rule::unique('name')->ignore($author->author_id)],
        //     'description' => 'nullable|string',
        // ]);
        // $validatedData = $request->validate([
        //     'name' => ['required', 'string', Rule::unique('authors')->ignore($author->author_id)],
        //     'description' => 'nullable|string',
        // ]);
        
        try
        {
            $validatedData = $request->validate([
            // 'name' => 'required|string|unique:authors,name',
            'name' => [
                'required',
                'string',
                Rule::unique('authors')->ignore($author->author_id, 'author_id'),
            ],
            'description' => 'nullable|string',
            ]);
        }
        catch(\Illuminate\Validation\ValidationException $e)
        {
            return response()->json(['message' => 'Autor o podanej nazwie już istnieje.'], 404);
        }

        $author->update([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
        ]);

        return response()->json(['message' => 'Autor został pomyślnie zaktualizowany.', 'author' => $author], 200);
    }

    public function destroy($id)
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json(['message' => 'Autor nie istnieje.'], 404);
        }

        // Usuń połączenia między autorem a książkami
        $author->books()->detach();

        // Usuń autora
        $author->delete();

        return response()->json(['message' => 'Autor został pomyślnie usunięty.'], 200);
    }
    
    public function listBooksByAuthor($id)
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json(['message' => 'Autor nie istnieje.'], 404);
        }

        $books = $author->books()->get();

        return response()->json(['author' => $author, 'books' => $books], 200);
    }


}
