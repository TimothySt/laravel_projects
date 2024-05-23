<?php

// app/Http/Controllers/BookController.php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookController extends Controller
{
    public function create()
    {
        // Publisher
        $myApi_URL_1 = config('app.url') . "/api/publishers/";
        //dump($myApi_URL_1);
        $res1 = Http::withbasicAuth('student', 'student')->get($myApi_URL_1);
        //dump($res1);
        $publishers = json_decode(json_encode($res1->json()), false);
        // dump($publishers);
        // Author
        $myApi_URL_2 = config('app.url') . "/api/authors/";
        // dump($myApi_URL_2);
        $res2 = Http::withbasicAuth('student', 'student')->get($myApi_URL_2);
        // dump($res2);
        $authors = $res2->json()['authors'];
        // dump($authors);

        return view('books.create', compact('authors', 'publishers'));
    }
    public function store(Request $request)
    {
        //dump($request->all());
        //new book
        //https://foka.umg.edu.pl/~s48502/PSI/projekt/psi_biblio/public/api/books/new
        $myApi_URL_new_book = "https://foka.umg.edu.pl/~s48502/PSI/projekt/psi_biblio/public/api/books/new";
        //dump($myApi_URL_new_book);
        //new author
        $myApi_URL_new_author = "https://foka.umg.edu.pl/~s48502/PSI/projekt/psi_biblio/public/api/authors/new";
        //dump($myApi_URL_new_author);
        //new publisher
        $myApi_URL_new_publisher = "https://foka.umg.edu.pl/~s48502/PSI/projekt/psi_biblio/public/api/publishers/new";
        //dump($myApi_URL_new_publisher);
        //JSON
        //          {
        //     "isbn": "1234567893",
        //     "title": "Przykładowa Książka",
        //     "description": "Opis książki",
        //     "published_date": "2022-01-10",
        //     "publisher_name": "Przykładowy Wydawca",
        //     "pages": 300,
        //     "language": "Polski",
        //     "authors": ["Autor1", "Autor2"]
        // }
        //publisher
        $publisher_data = [
            'name' => $request->input('publisher_name'),
            'description' => $request->input('publisher_description'),
        ];
        // dump($publisher_data);
        //walidacja
        $validatedData = $request->validate([
            'publisher_name' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if ($value === 'new') {
                        $fail($attribute.' cannot be "new".');
                    }
                },
            ],
            'publisher_description' => 'string',
        ]);
        $myApi_URL_list_publisher = "https://foka.umg.edu.pl/~s48502/PSI/projekt/psi_biblio/public/api/publishers/";
        //dump($myApi_URL_list_publisher);
        $res_list_publisher = Http::withbasicAuth('student', 'student')->get($myApi_URL_list_publisher);
        // dump($res_list_publisher);
        $publishers = json_decode(json_encode($res_list_publisher->json()), false);
        // dump($publishers);
        //sprawdzenie czy istnieje
        $publisher_id = 0;
        foreach ($publishers as $publisher) {
            if ($publisher->name == $publisher_data['name']) {
                $publisher_id = $publisher->publisher_id;
            }
        }
        // dump($publisher_id);
        //jeśli nie istnieje to dodajemy
        if ($publisher_id == 0) {
            $res_new_publisher = Http::withbasicAuth('student', 'student')->post($myApi_URL_new_publisher, $publisher_data);
            // dump($res_new_publisher);
            $publisher_id = $res_new_publisher->json();
            // dump($publisher_id);
        }
        //author
        // {
        //     "authors": [
        //         {
        //             "author_id": 1,
        //             "name": "Krystian Borowski",
        //             "description": "sadsa",
        //             "created_at": "2024-01-09T10:23:18.000000Z",
        //             "updated_at": "2024-01-09T10:23:18.000000Z"
        //         }
        //     ]
        // }
        $authors_data = $request->input('authors');
        //dump($authors_data);
        $authors_description = $request->input('authorsDescription');
        //dump($authors_description);
        $authors_data_with_description = [];
        for ($i = 0; $i < count($authors_data); $i++) {
            $authors_data_with_description[$i]['name'] = $authors_data[$i];
            $authors_data_with_description[$i]['description'] = $authors_description[$i];
        }
        //dump($authors_data_with_description);
        $myApi_URL_list_author = "https://foka.umg.edu.pl/~s48502/PSI/projekt/psi_biblio/public/api/authors/";
        // dump($myApi_URL_list_author);
        $res_list_author = Http::withbasicAuth('student', 'student')->get($myApi_URL_list_author);
        // dump($res_list_author);
        $authors = json_decode(json_encode($res_list_author->json()), false);
        //dump($authors);
        //$authors
        //         {#469 ▼ // app/Http/Controllers/BookController.php:104
        //   +"authors": array:2 [▼
        //   0 => {#361 ▼
        //     +"author_id": 1
        //     +"name": "Krystian Borowski"
        //     +"description": "sadsa"
        //     +"created_at": "2024-01-09T10:23:18.000000Z"
        //     +"updated_at": "2024-01-09T10:23:18.000000Z"
        //   }
        //   1 => {#440 ▼
        //     +"author_id": 2
        //     +"name": "countries"
        //     +"description": "sdasad"
        //     +"created_at": "2024-01-09T10:30:14.000000Z"
        //     +"updated_at": "2024-01-09T10:30:14.000000Z"
        //   }
        // ]
        // }
        //z JSONa do tablicy zawsze jest jeden element
        $authors_array = [];
        foreach ($authors as $author) {
            $authors_array = $author;
            // dump($author);
        }
        //dump($authors_array);

        //sprawdzenie czy istnieje dodaje kilku autorów
        $authors_id = [];
        foreach ($authors_data_with_description as $author_data_added) {
            $author_id = 0;
            foreach ($authors_array as $author_existing) {
                if ($author_existing->name == $author_data_added['name']) {
                    $author_id = $author_existing->author_id;
                }
            }
            // dump($author_id);
            //jeśli nie istnieje to dodajemy
            if ($author_id == 0) {
                $res_new_author = Http::withbasicAuth('student', 'student')->post($myApi_URL_new_author, $author_data_added);
                // dump($res_new_author);
                $author_id = $res_new_author->json();
                // dump($author_id);
            }
            array_push($authors_id, $author_id);
        }
        //dump($authors_id);



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
        //dump($validatedData);
        //to JSON
        $json_data = json_encode($validatedData);
        //dump($json_data);
        //dodanie ksiazki
        $res_new_book = Http::withbasicAuth('student', 'student')->post($myApi_URL_new_book, $validatedData);
        //dump($res_new_book);
        $book_id = $res_new_book->json();
        //dump($book_id);
        //przekierowanie do listy ksiazek z message od api
        if($res_new_book->status() == 400){
            return redirect()->back()->with('message', $res_new_book->json()['error']);
        }else{
            return redirect()->route('books.list')->with('message', $res_new_book->json()['message']);
        }

    }


    public function list()
    {
        $myApi_URL = config('app.url') . "/api/books/";
        //dump($myApi_URL);
        $res = Http::withbasicAuth('student', 'student')->get($myApi_URL);
        //dump($res);
        $books = json_decode(json_encode($res->json()['data']), false);
        //dump($books);

        $myApi_URL_2 = config('app.url') . "/api/authors/";
        $res2 = Http::withbasicAuth('student', 'student')->get($myApi_URL_2);
        $authors = json_decode(json_encode($res2->json()), false);

        foreach ($books as $book) {
            $myApi_URL_3 = config('app.url') . "/api/publishers/find/" . $book->publisher_id;
            // dump($myApi_URL_3);
            $res3 = Http::withbasicAuth('student', 'student')->get($myApi_URL_3);
            //dump($res3);
            $publishers = json_decode(json_encode($res3->json()), false);
            //dump($publishers);
            $book->publisher_name = $publishers->name;
            //dump($book);
            $book->publisher_description = $publishers->description;
            //dump($book);
        }
        // dump($books);
        return view('books.list', compact('books'));
    }

    // search
    public function search(Request $request)
    {
        // dump($request->input('query'));
        $myApi_URL = config('app.url') . "/api/books/search?query=" . $request->input('query');
        // dump($myApi_URL);
        $res = Http::withbasicAuth('student', 'student')->get($myApi_URL);
        // dump($res);
        $books = json_decode(json_encode($res->json()['data']), false);
        // dump($books);

        $myApi_URL_2 = config('app.url') . "/api/authors/";
        $res2 = Http::withbasicAuth('student', 'student')->get($myApi_URL_2);
        $authors = json_decode(json_encode($res2->json()), false);

        foreach ($books as $book) {
            $myApi_URL_3 = config('app.url') . "/api/publishers/find/" . $book->publisher_id;
            // dump($myApi_URL_3);
            $res3 = Http::withbasicAuth('student', 'student')->get($myApi_URL_3);
            //dump($res3);
            $publishers = json_decode(json_encode($res3->json()), false);
            //dump($publishers);
            $book->publisher_name = $publishers->name;
            //dump($book);
            $book->publisher_description = $publishers->description;
            //dump($book);
        }
        // dump($books);
        return view('books.list', compact('books'));
    }

    // show
    public function show($isbn)
    {
        $myApi_URL = config('app.url') . "/api/books/find/" . $isbn;
        //dump($myApi_URL);
        $res = Http::withbasicAuth('student', 'student')->get($myApi_URL);
        //dump($res);
        $book = json_decode(json_encode($res->json()['data']), false);
        //dump($book);
        //authors
        $authorsRaw = $book->authors;
        //dump($authorsRaw);
        //publisher
        $myApi_URL_3 = config('app.url') . "/api/publishers/find/" . $book->publisher_id;
        //dump($myApi_URL_3);
        $res3 = Http::withbasicAuth('student', 'student')->get($myApi_URL_3);
        //dump($res3);
        $publisher = json_decode(json_encode($res3->json()), false);
        //dump($publisher);
        $book->publisher_name = $publisher->name;
        //dump($book);
        $book->publisher_description = $publisher->description;
        //dump($book);
        //copy
        $myApi_URL_4 = config('app.url') . "/api/copies/forbook/" . $book->title_id;
        //dump($myApi_URL_4);
        $res4 = Http::withbasicAuth('student', 'student')->get($myApi_URL_4);
        //dump($res4);
        $copies = json_decode(json_encode($res4->json()['copies']), false);
        //dump($copies);
        return view('books.show', compact('book'), compact('copies'));
    }

    // edit
    public function edit($isbn)
    {
        // Publisher
        $myApi_URL_1 = config('app.url') . "/api/publishers/";
        //dump($myApi_URL_1);
        $res1 = Http::withbasicAuth('student', 'student')->get($myApi_URL_1);
        //dump($res1);
        $publishers = json_decode(json_encode($res1->json()), false);
        //dump($publishers);
        // Author
        $myApi_URL_2 = config('app.url') . "/api/authors/";
        // dump($myApi_URL_2);
        $res2 = Http::withbasicAuth('student', 'student')->get($myApi_URL_2);
        // dump($res2);
        $authors = $res2->json()['authors'];
        //dump($authors);

        $myApi_URL = config('app.url') . "/api/books/find/" . $isbn;
        //dump($myApi_URL);
        $res = Http::withbasicAuth('student', 'student')->get($myApi_URL);
        //dump($res);
        $book = json_decode(json_encode($res->json()['data']), false);
        //dump($book);
        //authorsPresent
        $authorsPresent = [];
        foreach ($book->authors as $author) {
            array_push($authorsPresent, $author);
        }
        //dump($authorsPresent);
        //delete RawAuthors from Authors
        $authorsNotPresent = [];
        foreach ($authors as $author) {
            $isPresent = false;
            foreach ($authorsPresent as $authorRaw) {
                if ($author['name'] == $authorRaw->name) {
                    $isPresent = true;
                }
            }
            if (!$isPresent) {
                array_push($authorsNotPresent, $author);
            }
        }
        //dump($authorsNotPresent);
        //publisher
        $myApi_URL_3 = config('app.url') . "/api/publishers/find/" . $book->publisher_id;
        //dump($myApi_URL_3);
        $res3 = Http::withbasicAuth('student', 'student')->get($myApi_URL_3);
        //dump($res3);
        $publisher = json_decode(json_encode($res3->json()), false);
        //dump($publisher);
        $book->publisher_name = $publisher->name;
        //dump($book);
        $book->publisher_description = $publisher->description;
        //dump($book);
        return view('books.edit', compact('book','authorsPresent','authorsNotPresent','publishers'));
    }

    // update
    public function update(Request $request)
    {
        dump($request->all());
        //new book
        //https://foka.umg.edu.pl/~s48502/PSI/projekt/psi_biblio/public/api/books/new
        $myApi_URL_new_book = "https://foka.umg.edu.pl/~s48502/PSI/projekt/psi_biblio/public/api/books/edit/".$request->input('isbn');
        //dump($myApi_URL_new_book);
        //new author
        $myApi_URL_new_author = "https://foka.umg.edu.pl/~s48502/PSI/projekt/psi_biblio/public/api/authors/new";
        //dump($myApi_URL_new_author);
        //new publisher
        $myApi_URL_new_publisher = "https://foka.umg.edu.pl/~s48502/PSI/projekt/psi_biblio/public/api/publishers/new";
        //dump($myApi_URL_new_publisher);
        //JSON
        //          {
        //     "isbn": "1234567893",
        //     "title": "Przykładowa Książka",
        //     "description": "Opis książki",
        //     "published_date": "2022-01-10",
        //     "publisher_name": "Przykładowy Wydawca",
        //     "pages": 300,
        //     "language": "Polski",
        //     "authors": ["Autor1", "Autor2"]
        // }
        //publisher
        $publisher_data = [
            'name' => $request->input('publisher_name'),
            'description' => $request->input('publisher_description'),
        ];
        // dump($publisher_data);
        //walidacja
        $validatedData = $request->validate([
            'publisher_name' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if ($value === 'new') {
                        $fail($attribute.' cannot be "new".');
                    }
                },
            ],
            'publisher_description' => 'string',
        ]);
        $myApi_URL_list_publisher = "https://foka.umg.edu.pl/~s48502/PSI/projekt/psi_biblio/public/api/publishers/";
        //dump($myApi_URL_list_publisher);
        $res_list_publisher = Http::withbasicAuth('student', 'student')->get($myApi_URL_list_publisher);
        // dump($res_list_publisher);
        $publishers = json_decode(json_encode($res_list_publisher->json()), false);
        // dump($publishers);
        //sprawdzenie czy istnieje
        $publisher_id = 0;
        foreach ($publishers as $publisher) {
            if ($publisher->name == $publisher_data['name']) {
                $publisher_id = $publisher->publisher_id;
            }
        }
        // dump($publisher_id);
        //jeśli nie istnieje to dodajemy
        if ($publisher_id == 0) {
            $res_new_publisher = Http::withbasicAuth('student', 'student')->post($myApi_URL_new_publisher, $publisher_data);
            // dump($res_new_publisher);
            $publisher_id = $res_new_publisher->json();
            // dump($publisher_id);
        }
        //author
        // {
        //     "authors": [
        //         {
        //             "author_id": 1,
        //             "name": "Krystian Borowski",
        //             "description": "sadsa",
        //             "created_at": "2024-01-09T10:23:18.000000Z",
        //             "updated_at": "2024-01-09T10:23:18.000000Z"
        //         }
        //     ]
        // }
        $authors_data = $request->input('authors');
        //dump($authors_data);
        $authors_description = $request->input('authorsDescription');
        //dump($authors_description);
        $authors_data_with_description = [];
        for ($i = 0; $i < count($authors_data); $i++) {
            $authors_data_with_description[$i]['name'] = $authors_data[$i];
            $authors_data_with_description[$i]['description'] = $authors_description[$i];
        }
        //dump($authors_data_with_description);
        $myApi_URL_list_author = "https://foka.umg.edu.pl/~s48502/PSI/projekt/psi_biblio/public/api/authors/";
        // dump($myApi_URL_list_author);
        $res_list_author = Http::withbasicAuth('student', 'student')->get($myApi_URL_list_author);
        // dump($res_list_author);
        $authors = json_decode(json_encode($res_list_author->json()), false);
        //dump($authors);
        //$authors
        //         {#469 ▼ // app/Http/Controllers/BookController.php:104
        //   +"authors": array:2 [▼
        //   0 => {#361 ▼
        //     +"author_id": 1
        //     +"name": "Krystian Borowski"
        //     +"description": "sadsa"
        //     +"created_at": "2024-01-09T10:23:18.000000Z"
        //     +"updated_at": "2024-01-09T10:23:18.000000Z"
        //   }
        //   1 => {#440 ▼
        //     +"author_id": 2
        //     +"name": "countries"
        //     +"description": "sdasad"
        //     +"created_at": "2024-01-09T10:30:14.000000Z"
        //     +"updated_at": "2024-01-09T10:30:14.000000Z"
        //   }
        // ]
        // }
        //z JSONa do tablicy zawsze jest jeden element
        $authors_array = [];
        foreach ($authors as $author) {
            $authors_array = $author;
            // dump($author);
        }
        //dump($authors_array);

        //sprawdzenie czy istnieje dodaje kilku autorów
        $authors_id = [];
        foreach ($authors_data_with_description as $author_data_added) {
            $author_id = 0;
            foreach ($authors_array as $author_existing) {
                if ($author_existing->name == $author_data_added['name']) {
                    $author_id = $author_existing->author_id;
                }
            }
            // dump($author_id);
            //jeśli nie istnieje to dodajemy
            if ($author_id == 0) {
                $res_new_author = Http::withbasicAuth('student', 'student')->post($myApi_URL_new_author, $author_data_added);
                // dump($res_new_author);
                $author_id = $res_new_author->json();
                // dump($author_id);
            }
            array_push($authors_id, $author_id);
        }
        //dump($authors_id);

        //dump($validatedData);
        //to JSON
        $json_data = json_encode($request->all());
        //dump($json_data);
        //dodanie ksiazki
        //dump($myApi_URL_new_book);
        $res_new_book = Http::withbasicAuth('student', 'student')->post($myApi_URL_new_book, $request);
        //dump($res_new_book);
        $book_id = $res_new_book->json();
        //dump($book_id);
        // przekierowanie do listy ksiazek z message od api
        // return redirect()->route('books.list')->with('message', 'Książka została dodana');
        if($res_new_book->status() == 400){
            return redirect()->back()->with('message', $res_new_book->json()['error']);
        }else{
            return redirect()->route('books.list')->with('message', $res_new_book->json()['message']);
        }
        //dump($res_new_book->json()['message']);

    }

}

