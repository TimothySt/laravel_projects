<!DOCTYPE html>
<html lang="pl">
@include('partials.head')
<body>
    @include('partials.navi')
    <main id="content">
        {{-- wyszikiwanie użytkownika --}}
        <div class="query">
            <h2>Wyszukaj użytkownika</h2>
            <form action="{{ url('/employees/serach') }}" method="GET">
                <input type="text" name="query" id="query" placeholder="Szukaj...">
                <button type="submit">Szukaj</button>
            </form>
        </div>
        
    </main>
    @include('partials.footer')
</body>
</html>