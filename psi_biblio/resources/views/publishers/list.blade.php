<!DOCTYPE html>
<html lang="en">
@include('partials.head')
<body>
    @include('partials.navi')
    <main id="content">
        @if (Auth::check() && (Auth::user()->hasRole('admin') || Auth::user()->hasRole('employee')))
        <a class="addLink" href="{{ url('/publishers/create') }}">Dodaj wydawnictwo</a>
        @endif
        @foreach($publishers as $publisher)
            <div class="list_element">
                <div class="publisher__name">
                    <h3>{{ $publisher->name }}</h3>
                </div>
                <div class="publisher__description">
                    <p>{{ $publisher->description }}</p>
                </div>
                @if (Auth::check() && (Auth::user()->hasRole('admin') || Auth::user()->hasRole('employee')))
                <div class="publisher__actions">
                    {{-- <a href="{{ route('publishers.show', $publisher->publisher_id) }}">Pokaż</a>  --}}
                    <a href="{{ route('publishers.edit', $publisher->publisher_id) }}">Edytuj</a>
                    {{-- <form action="{{ route('publishers.destroy', $publisher->publisher_id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Usuń</button>
                    </form> --}}
                </div>
                @endif
            </div>
        @endforeach
        {{-- <div class="pagination">
            {{ $publishers->links() }}
        </div> --}}
    </main>
    @include('partials.footer')
</body>
</html>