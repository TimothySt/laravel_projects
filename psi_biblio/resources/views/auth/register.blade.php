<!DOCTYPE html>
<html lang="pl">
@include('partials.head')
<body>
    <nav>
        <div class="logo">
            <a href="{{ config('app.url') }}">
                <img src="{{ asset('img/book_icon.png') }}" alt="Logo">
            </a>
        </div>
    </nav>
    <main id="content">
    
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- First Name -->
        <div>
            <x-input-label for="first_name" :value="__('Imię')" />
            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <!-- Last Name -->
        <div class="mt-4">
            <x-input-label for="last_name" :value="__('Nazwisko')" />
            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Telephone -->
        {{-- <div class="mt-4">
            <x-input-label for="tel" :value="__('Telephone')" />
            <x-text-input id="tel" class="block mt-1 w-full" type="tel" name="tel" :value="old('tel')" required />
            <x-input-error :messages="$errors->get('tel')" class="mt-2" />
        </div> --}}
        <div class="mt-4">
            <x-input-label for="tel" :value="__('Telefon')" />
            <x-text-input id="tel" class="block mt-1 w-full" type="tel" name="tel" :value="old('tel')" required pattern="^(\+\d{1,15}|\d{1,15})$"  />
            <x-input-error :messages="$errors->get('tel')" class="mt-2" />
            <p class="text-xs text-gray-500 mt-1">Please enter a valid phone number starting with '+'.</p>
        </div>
        <!-- Address -->
        <div class="mt-4">
            <x-input-label for="address" :value="__('Adres')" />
            <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" required />
            <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>
        


        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Hasło')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Powtórz Hasło')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Jesteś już zarejestrowany?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Zarejestruj się') }}
            </x-primary-button>
        </div>
    </form>
</main>
@include('partials.footer')

</body>

</html>