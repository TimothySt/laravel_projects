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
        {{-- wyświetlanie błędów --}}
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
        {{-- wyświetlanie użytkowników --}}
        @foreach($members as $member)
            <div class="member">
                {{-- protected $fillable = [
                    'first_name',
                    'last_name',
                    'email',
                    'tel',
                    'address',
                ]; --}}
                <h2>{{ $member->first_name }} {{ $member->last_name }}</h2>
                <p>{{ $member->email }}</p>
                <p>{{ $member->tel }}</p>
                <p>{{ $member->address }}
                <p>{{ $member->role->name }}</p>
                {{-- przycisk do wypożyczenia książki--}}
                <form action={{ url('/loan') }} method="GET">
                    @csrf
                    <input type="hidden" name="member_id" value="{{$member->member_id}}">
                    <button type="submit" class="btn btn-primary">Wypożycz</button>

                </form>
                
                {{-- if user is admin --}}
                @if (Auth::check() && (Auth::user()->hasRole('admin')))

                    {{-- przycisk do zatrudnienia użytkownika --}}
                    @if($member->role->name == "user")
                        <form action={{ url('/employees/hire') }} method="POST">
                            @csrf
                            <input type="hidden" name="member_id" value="{{$member->member_id}}">
                            <button type="submit" class="btn btn-primary">Zatrudnij</button>
                        </form>
                        <form action={{ url("/employees/fire") }} method="POST">
                            @csrf
                            <input type="hidden" name="member_id" value="{{$member->member_id}}">
                            <button type="submit" class="btn btn-primary" disabled>Zwolnij</button>
                        </form>
                    @endif

                    @if($member->role->name == 'employee')
                        <form action={{ url('/employees/hire') }} method="POST">
                            @csrf
                            <input type="hidden" name="member_id" value="{{$member->member_id}}">
                            <button type="submit" class="btn btn-primary" disabled>Zatrudnij</button>
                        </form>
                        <form action={{ url("/employees/fire") }} method="POST">
                            @csrf
                            <input type="hidden" name="member_id" value="{{$member->member_id}}">
                            <button type="submit" class="btn btn-primary">Zwolnij</button>
                        </form>
                    @endif
                    

                @endif

                <a href="/members/edit/{{$member->member_id}}">
                    <button type="submit" class="btn btn-primary">Edytuj</button></a>
                    {{-- pokaż stronę użytkownika --}}
                <a href="/members/show/{{$member->member_id}}">
                    <button type="submit" class="btn btn-primary">Pokaż</button></a>

            </div>
        @endforeach
        {{-- <div class="pagination">
            {{ $books->links() }}
        </div> --}}
    </main>
    @include('partials.footer')
</body>
</html>