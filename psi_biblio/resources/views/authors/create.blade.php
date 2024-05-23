<!DOCTYPE html>
<html lang="pl">
@include('partials.head')
<body>
    @include('partials.navi')

    <main id="content">

        <h2>Dodaj autora</h2>
        <form action="{{ route('authors.store') }}" method="post">
            @csrf
            <p>
                <label for="name">Imię i nazwisko autora</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required>
            </p>
            <p>
                <label for="description">Opis autora</label>
                <textarea name="description" id="description" >{{ old('description') }}</textarea>
            </p>
            <p>
                <button type="submit" class="btn btn-primary mb-2">Dodaj autora</button>
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