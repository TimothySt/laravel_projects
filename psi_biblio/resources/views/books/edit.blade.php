<!DOCTYPE html>
<html lang="pl">
@include('partials.head')

<body>
    @include('partials.navi')

    <div id="content">

        <h2>Edytuj książkę</h2>
        <form action="{{ route('books.update', $book->title_id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" value="{{ $book->title_id }}" />
            <input type="hidden" name="isbn" id="isbn" required value="{{ old('isbn', $book->isbn) }}" />
            <p>
                <label for="title">Tytuł</label>
                <input type="text" name="title" id="title" required value="{{ old('title', $book->title) }}" />
            </p>
            <p>
                <label for="description">Opis</label>
                <textarea name="description" id="description" rows="3" required>{{ old('description', $book->description) }}</textarea>
            </p>
            <p>
                <label for="published_date">Data publikacji</label>
                <input type="date" name="published_date" id="published_date" required
                    value="{{ old('published_date', $book->published_date) }}" />
            </p>
            {{-- liczba stron --}}
            <p>
                <label for="pages">Liczba stron</label>
                <input type="number" name="pages" id="pages" min="1"
                    value="{{ old('pages', $book->pages) }}" />
            </p>
            {{-- jezyk --}}
            <p>
                <label for="language">Język</label>
                <input type="text" name="language" id="language" required="required"
                    value="{{ old('language', $book->language) }}" />
            </p>

            {{-- wydawnictwo --}}
            <p>
                <label for="publisher">Wydawnictwo</label>
                {{-- select z wieloma publisherami zmienna $publishers --}}
                <select name="publisher_name" id="publisher_id" onchange="handlePublisherChange()">
                    {{-- if selected show form to new publisher --}}
                    <option value="new">Wyszukaj lub dodaj nowe wydawnictwo</option>
                    @foreach ($publishers as $publisher)
                        {{-- if selected show form to new publisher --}}
                        @if ($publisher->name == $book->publisher_name)
                            <option value="{{ $publisher->name }}" selected="selected">{{ $publisher->name }}</option>
                        @else
                            <option value="{{ $publisher->name }}">{{ $publisher->name }}</option>
                        @endif
                    @endforeach
                </select>
                {{-- div do wyswiewtlania opisu --}}
            <div id="publisherDescriptionDiv">
                @foreach ($publishers as $publisher)
                    <input type="hidden" id="publisherDescription{{ $publisher->name }}"
                        value="{{ $publisher->description }}">
                @endforeach
            </div>
            {{-- Nazwa nowego wydawnictwa --}}
            <label for="publisher">Nazwa wydawnictwa:</label>
            <input type="text" name="new_publisher" id="new_publisher" onchange="findPublisher()"
                value="{{ old('new_publisher') }}" />
            <p id="publisher_mess"></p>
            {{-- Opis nowego wydawnictwa --}}
            <label for="publisher" class="hidden_publisher">Opis</label>
            <textarea class="hidden_publisher" name="publisher_description" id="publisher_description" rows="3"
                readonly="readonly">{{ old('publisher_description') }}</textarea>
            {{-- przycisk dodaj nowe wydawnictwo --}}
            <button type="button" class="hidden_publisher" class="btn btn-primary mb-2"
                onclick="addNewPublisher()">Dodaj nowe wydawnictwo</button>
            <br class="hidden_publisher" /><br class="hidden_publisher" />
            </p>
            {{-- autorzy --}}
            <p>
                <label>Autorzy</label><br /><br />
                <label for="authors">Wybrani autorzy</label>
            <div id="selected_authors">
                {{-- input checkbox z autorami zmienna $authorsPresent name="authors[]" --}}
                @foreach ($authorsPresent as $author)
                    <input type="checkbox" name="authors[]" value="{{ $author->name }}" checked="checked"
                        onchange="removeAuthor(this)">{{ $author->name }}
                @endforeach
                {{-- input hidden z opiniami autorów --}}
                @foreach ($authorsPresent as $author)
                    <input type="hidden" id="authorDescription{{ $author->name }}" name="authorsDescription[]"
                        value="{{ $author->description }}">
                @endforeach

            </div>
            <label for="authors">Wybierz autora</label>
            <br />
            {{-- select z wieloma autorami zmienna $authors --}}
            <select id="authors" onchange="handleAuthorChange()">
                {{-- if selected show form to new autor --}}
                <option value="new">Dodaj nowego autora</option>
                {{-- if selected show form to new autor --}}
                @foreach ($authorsNotPresent as $author)
                    <option value="{{ $author['name'] }}">{{ $author['name'] }}</option>
                @endforeach
            </select>
            {{-- Przycisk dodaj autora --}}
            <button type="button" class="btn btn-primary mb-2" id="unhidden_autor"
                onclick="addAuthor()">Wybierz</button>
            {{-- hidden input z opisem autora --}}
            <div id="authorDescriptions">
                @foreach ($authorsNotPresent as $author)
                    <input type="hidden" id="authorDescription{{ $author['name'] }}"
                        value="{{ $author['description'] }}">
                @endforeach
            </div>
            <br class="hidden_author" /> <br class="hidden_author" />
            <label for="authors" class="hidden_author">Nowy autor</label>
            <br class="hidden_author" />
            <label for="authors" class="hidden_author">Imię i nazwisko</label>
            {{-- imie i nazwisko nowego autora --}}
            <input type="text" class="hidden_author" name="new_author" id="new_author"
                value="{{ old('new_author') }}" />
            <br class="hidden_author" />
            {{-- descyption for new autor --}}
            <label for="authors" class="hidden_author">Opis</label>
            <textarea class="hidden_author" name="new_author_description" id="new_author_description" rows="3">{{ old('new_author_description') }}</textarea>
            <br class="hidden_author" />
            {{-- przycisk dodaj nowego autora --}}
            <button type="button" class="hidden_author" class="btn btn-primary mb-2" onclick="addNewAuthor()">Dodaj
                nowego autora</button>
            <br class="hidden_author" /><br class="hidden_author" />
            {{-- div na opisy aoutorów --}}
            <div id="authorDescriptionsDiv">

            </div>


            </p>
            <p>
                <button type="submit" class="btn btn-primary mb-2" onclick="return CheckData()">Aktualizuj
                    książkę</button>
            </p>
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

    </div>

    @include('partials.footer')

    
</body>

</html>
