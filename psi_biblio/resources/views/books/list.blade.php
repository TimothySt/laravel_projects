<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Serwis biblioteczny</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description"
        content="Serwis biblioteczny - projekt zaliczeniowy z przedmiotu Projektowanie serwisów internetowych." />
    <meta name="keywords" content="Biblioteka, Książki, Serwis biblioteczny" />
    <meta name="author" content="Tymoteusz Staszkiewicz, Krystian Borowski" />
    <link rel="stylesheet" href="{{ URL::asset('style.css') }}" />
    {{-- ssh://s48502@foka.umg.edu.pl:22/home/s48502/public_html/PSI/projekt/psi_biblio/public/script.js --}}
    <script type="text/javascript" src="{{ URL::asset('script.js') }}"></script>
</head>

<body>
    @include('partials.navi')
    <main id="content">
        @if (Auth::check() && (Auth::user()->hasRole('admin') || Auth::user()->hasRole('employee')))
            <a class="addLink" href="{{ url('/books/create') }}">Dodaj książkę</a>
        @endif
        @if ($books==null)
            <p>Brak książek w bazie danych.</p>
        @endif
        @foreach ($books as $book)
            <div class="list_element">
                <div class="book__image">
                    {{-- <img src="{{ $book->image }}" alt="{{ $book->title }}"> --}}
                </div>
                <div class="book__title">
                    <a href="{{ route('books.show', $book->isbn) }}" style="text-decoration: none">
                        <h3>{{ $book->title }}</h3>
                    </a>
                </div>
                <div class="book__description">
                    <p>{{ $book->description }}</p>
                </div>
                <div class="book__authors">
                    @foreach ($book->authors as $author)
                        <div class="book__author">
                            <p>Autor: {{ $author->name }}</p>
                            <p>Opis: </p>
                            <div>{{ $author->description }}</div>
                        </div>
                    @endforeach
                </div>
                <div class="book__con">
                    {{-- book language --}}
                    <div class="book__language">
                        <p>Język: {{ $book->language }}</p>
                    </div>
                    {{-- isbn --}}
                    <div class="book__isbn">
                        <p>ISBN: {{ $book->isbn }}</p>
                    </div>
                    {{-- book pages --}}
                    <div class="book__pages">
                        <p>Liczba stron: {{ $book->pages }}</p>
                    </div>
                    {{-- book published date --}}
                    <div class="book__published_date">
                        <p>Data publikacji: {{ $book->published_date }}</p>
                    </div>
                    {{-- liczba dostępnych kopii --}}
                    <div class="book__available">
                        <p>Liczba dostępnych kopii: {{ $book->available_copies_count }}</p>
                    </div>
                </div>
                {{-- book publisher --}}
                <div class="book__publisher">
                    <div class="book__publisher_name">
                        <p>Wydawca: {{ $book->publisher_name }}</p>
                    </div>
                    {{-- descryption --}}
                    <div class="book__publisher_description">
                        <p>Opis: </p>
                        <p>{{ $book->publisher_description }}</p>
                    </div>
                </div>
                @if (Auth::check() && (Auth::user()->hasRole('admin') || Auth::user()->hasRole('employee')))
                    <div class="book__actions">
                        <a href="{{ route('books.show', $book->isbn) }}">Pokaż</a>
                        <a href="{{ route('books.edit', $book->isbn) }}">Edytuj</a>
                        {{-- <form action="{{ route('books.destroy', $book->isbn) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Usuń</button>
                        </form> --}}
                    </div>
                @endif

            </div>
        @endforeach
        {{-- <div class="pagination">
            {{ $books->links() }}
        </div> --}}
    </main>
    @include('partials.footer')
</body>

</html>
