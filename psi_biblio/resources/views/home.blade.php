<!DOCTYPE html>
<html lang="pl">
    @include('partials.head')
    <body>
        @include('partials.navi')
        
        <main id="content">
            <!-- zalogowano pomyślnie -->
            @if (Auth::check())
            <div class="alert-success" role="alert">
                Zalogowano pomyślnie!
                {{-- pobranie danych użyttkownika --}}
                {{ Auth::user()->first_name }}
                {{ Auth::user()->last_name}}
                {{-- wyświetlenie danych użytkownika --}}
                <p>Imię: {{ Auth::user()->first_name }}</p>
                <p>Nazwisko: {{ Auth::user()->last_name }}</p>
                <p>Email: {{ Auth::user()->email }}</p>
                <p>Rola: {{ Auth::user()->role->name }}</p>
                {{-- wyświtl rolę --}}
                @if (Auth::user()->hasRole('admin'))
                    <p>Użytkownik jest administratorem</p>
                @else
                    @if (Auth::user()->hasRole('employee'))
                        <p>Użytkownik to pracownik biblioteki.</p>
                    @else
                        <p>Użytkownik to czytelnik.</p>
                        <div class="query">
                            <form action="{{ url('/books/search') }}" method="GET">
                                <input type="text" name="query" id="query" placeholder="Szukaj...">
                                <button type="submit">Szukaj</button>
                            </form>
                        </div>
                    @endif
                @endif
                
            </div>
            @else
            <div class="alert-danger" role="alert">
                Witaj w systemie bibliotecznym, aby zalogować się do systemu, kliknij <a href="{{ url('/login') }}">tutaj</a>.
                <p>Jeśli nie masz jeszcze konta, kliknij <a href="{{ url('/register') }}">tutaj</a>.</p>
                <p>Jeśli chcesz wyszukać książki uzyj wyszukiwarki.</p>
                <div class="query">
            <form action="{{ url('/books/search') }}" method="GET">
                <input type="text" name="query" id="query" placeholder="Szukaj...">
                <button type="submit">Szukaj</button>
            </form>
        </div>
            </div>

            @endif

        </main>
        @include('partials.footer')
        
    </body>
</html>