<!DOCTYPE html>
<html lang="pl">
@include('partials.head')

<body>
    @include('partials.navi')
    <main id="content">
        {{-- $member->member_id --}}
        {{-- informacje o wybranym użytkowniku $member --}}
        {{-- $member->firstname, lastname, mail, tel --}}
        <div class="container">
            <div class="row">
                <div class="col">
                    <h1>Wypożycz książkę</h1>
                    <p>Imię: {{ $member->first_name }}</p>
                    <p>Nazwisko: {{ $member->last_name }}</p>
                    <p>Mail: {{ $member->email }}</p>
                    <p>Telefon: {{ $member->tel }}</p>
                </div>
            </div>
        </div>

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

        {{-- formularz wypożyczenia książki --}}
        <form action={{ url('/loan/store') }} method="POST">
            @csrf
            <div class="form-group">
                <label for="copy_id">ID książki</label>
                {{-- copy_id wymagane --}}
                <input type="text" class="form-control" name="copy_id" id="copy_id" placeholder="ID książki"
                    required min="10">
                <input type="hidden" class="form-control" name="member_id" id="member_id"
                    value="{{ $member->member_id }}">
            </div>
            <button type="submit" class="btn btn-primary">Wypożycz</button>
        </form>

        {{-- {
    "data": [
        {
            "loan_id": 1,
            "copy_id": "testeteast",
            "member_id": 1,
            "loan_date": "2024-01-20",
            "due_date": "2024-02-19",
            "loan_status_id": 2,
            "created_at": "2024-01-20T16:53:29.000000Z",
            "updated_at": "2024-01-20T16:53:29.000000Z",
            "copy": {
                "copy_id": "testeteast",
                "title_id": 1,
                "available": true,
                "created_at": "2024-01-20T12:45:35.000000Z",
                "updated_at": "2024-01-20T17:04:08.000000Z",
                "book": {
                    "title_id": 1,
                    "isbn": "21453543654",
                    "title": "Władca pierścieni",
                    "description": "saddsadfsafsaf",
                    "published_date": "2024-01-04",
                    "publisher_id": 10,
                    "pages": 12,
                    "language": "Polski",
                    "created_at": "2024-01-10T15:27:12.000000Z",
                    "updated_at": "2024-01-20T16:38:45.000000Z"
                }
            },
            "status": {
                "loan_status_id": 2,
                "status": "borrowed",
                "created_at": null,
                "updated_at": null
            }
        }, --}}

        {{-- Wyświetlanie wypożyczeń --}}
        <div class="container">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID wypożyczenia</th>
                        <th scope="col">Tytuł książki</th>
                        <th scope="col">Numer egzemplarza</th>
                        <th scope="col">Data wypożyczenia</th>
                        <th scope="col">Data zwrotu</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($loans as $loan)
                        <tr>
                            <th scope="row">{{ $loan->loan_id }}</th>
                            <td>{{ $loan->copy->book->title }}</td>
                            <td>{{ $loan->copy_id }}</td>
                            <td>{{ $loan->loan_date }}</td>
                            <td>{{ $loan->due_date }}</td>
                            @if ($loan->status->status == 'borrowed')
                                {{-- date --}}
                                @if ($loan->due_date < $today)
                                    <td class="status_overdue">Przetrzymana</td>
                                @else
                                    <td class="status_borrowed">Wypożyczona</td>
                                @endif
                            @else
                                <td class="status_returned">Zwrócona</td>
                            @endif
                        </tr>
                    @endforeach
            </table>
    </main>
    @include('partials.footer')
</body>

</html>
