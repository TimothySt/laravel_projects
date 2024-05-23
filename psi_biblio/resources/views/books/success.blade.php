<!DOCTYPE html>
<html lang="pl">
@include('partials.head')
<body>
    @include('partials.navi')
    
    <main>

        <div class="alert alert-success">
            Książka została pomyślnie dodana!
        </div>
        <a href="{{ route('books.create') }}" class="btn btn-primary mb-2">Dodaj kolejną książkę</a>

    </main>

    @include('partials.footer')
</body>
</html>
