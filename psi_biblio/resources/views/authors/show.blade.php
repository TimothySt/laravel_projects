<!DOCTYPE html>
<html lang="en">
@include('partials.head')
<body>
    @include('partials.navi')
    <main id="content">
            <div class="author">
                <div class="author__name">
                    <h3> {{ $author->name }} </h3>
                </div>
                <div class="author__description">
                    <p>{{ $author->description }}</p>
                </div>
                <div class="author__actions">
                    {{-- <a href="{{ route('authors.show', $author->author_id) }}">Pokaż</a> --}}
                    <a href="{{ route('authors.edit', $author->author_id) }}">Edytuj</a>
                    {{-- <form action="{{ route('authors.destroy', $author->author_id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Usuń</button>
                    </form>  --}}
                </div>
            </div>
        
            {{-- lista ksiazek autora --}}
            <div class="author__books">
                <h3>Lista książek autora</h3>
                    @foreach ($books as $book)
                        <div class="author__book">
                            <div class="author__book__title">
                                {{-- <h4>{{ $book->title }}</h4> --}}
                                <a href="{{ route('books.show', $book->isbn) }}" style="text-decoration: none">
                                    <h4>{{ $book->title }}</h4>
                                </a>
                            </div>
                            <div class="author__book__description">
                                <p>{{ $book->description }}</p>
                            </div>
                            <div class="author__book__actions">
                                {{-- <a href="{{ route('books.show', $book->isbn) }}">Pokaż</a> --}}
                                <a href="{{ route('books.edit', $book->isbn) }}">Edytuj</a>
                                {{-- <form action="{{ route('books.destroy', $book->isbn) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Usuń</button>
                                </form>  --}}
                            </div>
                        </div>
                    @endforeach
            </div>

    </main>
    @include('partials.footer')
</body>
</html>