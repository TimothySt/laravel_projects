<nav>
    <div class="logo">
        <a href="{{ config('app.url') }}">
            <img src="{{ asset('img/book_icon.png') }}" alt="Logo">
        </a>
    </div>
    <div class="left-nav">
        @if (Auth::check() && (Auth::user()->hasRole('admin') || Auth::user()->hasRole('employee')))
        <div class="dropdown">
            <button class="dropbtn">Książki<br />
                <img class="arrow" src="{{ asset('img/arrow_icon.png') }}" alt="Logo">
            </button>
            <div class="dropdown-content">
                <a href="{{ url('/books/list') }}">Lista książek</a>
                
            </div>
        </div>
        @endif

        @if (Auth::check() && (Auth::user()->hasRole('admin') || Auth::user()->hasRole('employee')))
        <div class="dropdown">
            <button class="dropbtn" >Autorzy<br />
                <img class="arrow" src="{{ asset('img/arrow_icon.png') }}" alt="Logo">
            </button>
            <div class="dropdown-content">
                <a href="{{ url('/authors/list') }}">Lista autorów</a>
                
            </div>
        </div>
        @endif

        @if (Auth::check() && (Auth::user()->hasRole('admin') || Auth::user()->hasRole('employee')))
        <div class="dropdown">
            <button class="dropbtn">Wydawnictwa<br />
                <img class="arrow" src="{{ asset('img/arrow_icon.png') }}" alt="Logo">
            </button>
            <div class="dropdown-content">
                <a href="{{ url('/publishers/list') }}">Lista wydawnictw</a>
                
            </div>
        </div>
        @endif

        {{-- if @role employee or admin --}}
        @if (Auth::check() && (Auth::user()->hasRole('admin') || Auth::user()->hasRole('employee')))
        <div  @role('admin') class="dropdown" >
            <button class="dropbtn">Pracownicy<br />
                <img class="arrow" src="{{ asset('img/arrow_icon.png') }}" alt="Logo">
            </button>
            <div class="dropdown-content">
                <a href="{{ url('/employees') }}">Szukaj konta</a>
                @if (Auth::check() && (Auth::user()->hasRole('admin'))){{-- /workers --}}
                    <a href="{{ url('/workers') }}">Pracownicy</a>
                @endif
                <a href="{{ url('/loan/returnes') }}">Zwroty wypożyczeń</a>
                
                
            </div>
        </div @endrole>
        @endif

        {{-- if @roleadmin --}}
        {{-- @if (Auth::check() && (Auth::user()->hasRole('admin')))
        <div  @role('admin') class="dropdown" >
            <button class="dropbtn">Administrator<br />
                <img class="arrow" src="{{ asset('img/arrow_icon.png') }}" alt="Logo">
            </button>
            <div class="dropdown-content">
                <a href="{{ url('/admin/users') }}">Lista pracowników</a>
                <a href="{{ url('/admin/users/add') }}">Dodaj pracownika</a>
            </div>
        </div @endrole>
        @endif --}}
        
        {{-- @endif --}}
        {{-- @if (Auth::check() && (Auth::user()->role == 'admin' || Auth::user()->role == 'worker')) --}}
        {{-- @endif --}}
    </div>

    <div class="right-nav">
        <div class="query">
            <form action="{{ url('/books/search') }}" method="GET">
                <input type="text" name="query" id="query" placeholder="Szukaj...">
                <button type="submit">Szukaj</button>
            </form>
        </div>

        <div class="login">
            @if (Auth::check())
                <a href="{{ url('./myprofile') }}">Profil</a>
                <a href="{{ url('./logout') }}">Wyloguj</a>
                {{-- <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Wyloguj</button>
                </form> --}}
            @else
                <a href="{{ url('./login') }}">Logowanie</a>
                <a href="{{ url('./register') }}">Rejestracja</a>
            @endif
        </div>
    </div>
</nav>
