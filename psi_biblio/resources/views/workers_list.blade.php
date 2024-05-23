<!DOCTYPE html>
<html lang="en">
@include('partials.head')
<body>
    @include('partials.navi')
    <main>
        <div class="search">
            <form action="{{ route('workers_list') }}" method="GET">
                <input type="text" name="search" placeholder="Imie Nazwisko" value="{{ request()->query('search') }}">
                <button type="submit">Szukaj</button>
            </form>
        </div>
        <div class="add">
            <a href="{{ route('worker_add') }}">Dodaj pracownika</a>
        </div>
        <!-- // W kontrolerze do tego niżej
$workers = Worker::paginate(10); -->
        <div class="list">
            <div class="list__header">
                <div class="list__header__item">Imie</div>
                <div class="list__header__item">Nazwisko</div>
                <div class="list__header__item">Email</div>
                <div class="list__header__item">Telefon</div>
                <div class="list__header__item">Adres</div>
                <div class="list__header__item">Stanowisko</div>
                {{-- Administrator lub pracownik --}}
                <div class="list__header__item">Akcje</div>
            </div>
            @foreach($workers as $worker)
            <div class="list_element">
                <div class="list_element__item">{{ $worker->first_name }}</div>
                <div class="list_element__item">{{ $worker->last_name }}</div>
                <div class="list_element__item">{{ $worker->email }}</div>
                <div class="list_element__item">{{ $worker->tel }}</div>
                <div class="list_element__item">{{ $worker->address }}</div>
                <div class="list_element__item">{{ $worker->role }}</div>
                <div class="list_element__item">
                    <a href="{{ route('worker_edit', $worker->id) }}">Edytuj</a>
                    <a href="{{ route('worker_delete', $worker->id) }}">Usuń</a>
                </div>
            </div>
            @endforeach
            <div class="pagination">
                {{ $workers->links() }}
            </div>
        </div>
    </main>
    @include('partials.footer')
</body>
</html>