<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Member; // własna klasa użytkownika
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // return view('auth.register');
        return view('auth.register'); // zmienić na widok rejestracji członka
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    // store dla members
    /*
    Table Members {
    member_id int [pk]
    role_id int
    first_name varchar
    last_name varchar
    email varchar [unique]
    tel varchar [unique]
    address varchar
    password varchar
    email_verified_at timestamp
    remember_token varchar
    }
    */
    public function storeUserMember(Request $request): RedirectResponse
    {
        $request -> validate([
            'first_name' => ['required', 'string', 'max:255'], // TODO - formatowanie
            'last_name' => ['required', 'string','max:255'], // TODO - formatowanie
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Member::class],
            'tel' => ['required', 'string', 'max:255','regex:/^(\+\d{1,15}|\d{1,15})$/', 'unique:'.Member::class],
            'address' => ['required', 'string', 'max:255'], // TODO - formatowanie
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        // // walidacja według zasad w modelu Member
        // $request->validate(Member::$rules);

        $member = Member::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'tel' => $request->tel,
            'address' => $request->address,
            'password' => Hash::make($request->password),
        ]);
        
        event(new Registered($member));

        Auth::login($member);

        return redirect(RouteServiceProvider::HOME);
    }
    

}
