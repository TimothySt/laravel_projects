<!DOCTYPE html>
<html lang="pl">
@include('partials.head')
<body>
    @include('partials.navi')

    <main id="content">
        <h2>Edytuj autora</h2>
        <form action="{{ route('authors.update', $author['author_id']) }}" method="POST">
            @csrf
            @method('PUT')
            <p>
                <label for="name">Imię i nazwisko autora</label>
                <input type="text" name="name" id="name" value="{{ old('name', $author['name']) }}" required>
            </p>
            <p>
                <label for="description">Opis autora</label>
                <textarea name="description" id="description">{{ old('description', $author['description']) }}</textarea>
            </p>
            <p>
                <button type="submit" class="btn btn-primary mb-2">Zaktualizuj autora</button>
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
    </main>

    @include('partials.footer')
</body>
</html>