<!DOCTYPE html>
<html lang="en">
@include('partials.head')
<body>
    @include('partials.navi')
    <main id="content">
        @if (Auth::check() && (Auth::user()->hasRole('admin') || Auth::user()->hasRole('employee')))
        <a class="addLink" href="{{ url('/authors/create') }}">Dodaj autora</a>
        @endif
        @foreach($authors as $author)
            <div class="list_element">
                <div class="author__name">
                    {{-- as array --}}
                    <h3> {{ $author['name'] }} </h3>
                </div>
                <div class="author__description">
                    <p>{{ $author['description'] }}</p>
                </div>
                @if (Auth::check() && (Auth::user()->hasRole('admin') || Auth::user()->hasRole('employee')))
                 <div class="author__actions">
                    <a href="{{ route('authors.show', $author['author_id']) }}">Pokaż</a>
                    <a href="{{ route('authors.edit', $author['author_id']) }}">Edytuj</a> 
                    {{-- <form action="{{ route('authors.destroy', $author['author_id']) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Usuń</button>
                    </form>  --}}
                </div>
                @endif
            </div>
        @endforeach
        {{-- <div class="pagination">
            {{ $authors->links() }}
        </div> --}}
    </main>
    @include('partials.footer')
</body>
</html>