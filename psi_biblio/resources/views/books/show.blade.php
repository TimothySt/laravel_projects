<!DOCTYPE html>
<html lang="en">
@include('partials.head')

<body>
    @include('partials.navi')
    <main id="content">
        {{-- "title_id" => 1
        "isbn" => "1234567890"
        "title" => "Przykładowa książka"
        "description" => "Opis książki"
        "published_date" => "2024-01-06"
        "publisher_id" => 1
        "pages" => 200
        "language" => "Polski"
        "created_at" => "2024-01-06 17:32:54"
        "updated_at" => "2024-01-06 17:32:54" --}}
        <div class="book">
            <div class="book__image">
                {{-- <img src="{{ $book->image }}" alt="{{ $book->title }}"> --}}
            </div>
            <div class="book__title">
                <h3>{{ $book->title }}</h3>
            </div>
            <div class="book__authors" >
                <h4>Autorzy:</h4>
                @foreach ($book->authors as $author)
                    <div class="book__author">
                        <p><a href="{{ route('authors.show', $author->author_id) }}">{{ $author->name }}</a></p>
                        {{-- <p>Autor: {{ $author->name }}</p> --}}
                        {{-- <p>O autorze: </p> --}}
                        {{-- <p>{{ $author->description }}</p> --}}
                    </div>
                @endforeach
            </div>
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
            {{-- book publisher --}}
            <div class="book__publisher">
                <p>Wydawca: {{ $book->publisher_name }}</p>
                {{-- descryption --}}
                <p>{{ $book->publisher_description }}</p>
            </div>
            <div class="book__description">
                <p>{{ $book->description }}</p>
            </div>
            {{-- book published date --}}
            <div class="book__published_date">
                <p>Data publikacji: {{ $book->published_date }}</p>
            </div>
            <div class="book__actions">
                <a href="{{ route('books.edit', $book->isbn) }}">Edytuj</a>
                {{-- <form action="{{ route('books.destroy', $book->isbn) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Usuń</button>
                </form> --}}
            </div>
            {{-- egzemplarze --}}
            <div class="book__copies">
                <h3>Egzemplarze</h3>
                {{-- from post dodaj kopie --}}
                <form action="{{ route('copies.store', $book->isbn) }}" method="POST">
                    @csrf
                    {{-- input copy_id --}}
                    <label for="copy_id">Numer egzemplarza</label>
                    <input type="text" name="copy_id" id="copy_id" min="10" max="10" value="{{ old('copy_id') }}"required>
                    {{-- input hidden title_id --}}
                    <input type="hidden" name="title_id" id="title_id" value="{{ $book->title_id }}">
                    <input type="hidden" name="isbn" id="isbn" value="{{ $book->isbn }}">
                    {{-- button submit --}}
                    <button type="submit">Dodaj egzemplarz</button>
                </form>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{-- error and sukcess --}}
                @if (session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif
                <div class="book__copies__list">
                    @foreach ($copies as $copy)
                        <div class="book__copies__list__item">
                            <p>Numer egzemplarza: {{ $copy->copy_id }}</p>
                            <p>Status: {{ $copy->available == 1 ? 'dostępne' : 'niedostępne' }}</p>
                        </div>
                    @endforeach 
                </div>
            </div>
    </main>
    @include('partials.footer')
</body>

</html>
