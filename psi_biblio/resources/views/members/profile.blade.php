<!DOCTYPE html>
<html lang="pl">
@include('partials.head')

<body>
    @include('partials.navi')

    <main id="content">
        {{-- dane uzytkownika --}}
        <p>Imię: {{ Auth::user()->first_name }}</p>
                <p>Nazwisko: {{ Auth::user()->last_name }}</p>
                <p>Email: {{ Auth::user()->email }}</p>
        {{-- wypozyczone ksiazki --}}
        <div class="wyp">
            <h2>Wypożyczone książki</h2>
            {{-- nie uzywaj tabeli --}}
            <div class="list">
                <div class="item">
                    <div class="title">Tytuł</div>
                    {{-- data wypozyczenia --}}
                    <div class="date">Data wypożyczenia</div>
                    {{-- data zwrotu --}}
                    <div class="date">Data zwrotu</div>
                    {{-- egzemplarz --}}
                    <div class="copy">Egzemplarz</div>
                    {{-- status --}}
                    <div class="status">Status</div>
                    
                </div>
                {{-- for --}}
                @foreach ($loans as $loan)
                <div class="item">
                <div class="title">{{$loan->copy->book->title}}</div>
                <div class="date">{{$loan->loan_date}}</div>
                <div class="date">{{$loan->due_date}}</div>
                <div class="copy">{{$loan->copy->copy_id}}</div>
                {{-- if borrowed zwrócony --}}
                {{-- if returned wypozyczony --}}
                @if ($loan->status->status == "borrowed")
                {{-- date --}}
                    @if ($loan->due_date < $today)
                    <div class="status_overdue">Przetrzymana</div>
                    @else
                    <div class="status_borrowed">Wypożyczona</div>
                    @endif
                @else
                <div class="status_returned">Zwrócona</div>
                @endif
                
                {{-- przedłuż --}}
                @if ($loan->status->status == "borrowed")
                @if (\Carbon\Carbon::parse($loan->due_date)->diffInDays(\Carbon\Carbon::parse($loan->loan_date)) < 90)
                <form action="{{ route('loan.extend') }}" method="POST">
                    @csrf
                    @method('Post')
                    <input type="hidden" name="copy_id" value="{{$loan->copy->copy_id}}">
                    <button type="submit" class="btn btn-primary">Przedłuż</button>
                </form>
                @else
                <div class="status">Nie można przedłużyć</div>
                    @endif
                @endif   
                </div>     
                @endforeach
            </div>
        </div>

    </main>
    @include('partials.footer')

</body>

</html>
