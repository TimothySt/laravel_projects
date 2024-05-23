<!DOCTYPE html>
<html lang="pl">
@include('partials.head')
<body>
    @include('partials.navi')
    <main id="content">
        {{-- Wyświetlanie książek --}}
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
        {{-- formularz zwrócenia książki --}}
        <form action={{ url('/loan/returne/') }} method="POST">
            @csrf
            <div class="form-group">
                <label for="book_id">ID książki</label>
                <input type="text" class="form-control" name="copy_id" id="copy_id" placeholder="ID książki">
            </div>
            {{-- <div class="form-group">
                <label for="user_id">ID użytkownika</label>
                <input type="text" class="form-control" name="user_id" id="user_id" placeholder="ID użytkownika">
            </div> --}}
            <button type="submit" class="btn btn-primary">Zwróć książkę</button>
        </form>
        

    </main>
    @include('partials.footer')
</body>
</html>