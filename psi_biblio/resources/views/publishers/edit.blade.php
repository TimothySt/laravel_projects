<!DOCTYPE html>
<html lang="pl">
@include('partials.head')
<body>
    @include('partials.navi')

    <main id="content">
        <h2>Edytuj wydawnictwo</h2>
        <form action="{{ route('publishers.update', $publisher->publisher_id) }}" method="post">
            @csrf
            @method('PUT')
            <input type="hidden" name="publisher_id" value="{{ $publisher->publisher_id }}" />
            <p>
                <label for="name_new">Nazwa wydawnictwa</label>
                <input type="text" name="name" id="name_new" value="{{ old('name', $publisher->name) }}" required />
            </p>
            <p>
                <label for="description_new">Opis</label>
                <textarea name="description" id="description_new" rows="3">{{ old('description', $publisher->description) }}</textarea>
            </p>
            <p>
                <button type="submit" class="btn btn-primary mb-2">Zaktualizuj wydawnictwo</button>
            </p>
        </form>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </main>

    @include('partials.footer')
</body>
</html>