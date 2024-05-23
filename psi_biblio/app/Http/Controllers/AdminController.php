<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function create()
    {
        $roles = Role::all();
        return view('admin.employee.create', compact('roles'));
    }

    // stworzenie pracownika - eskalowanie uprawnień do poziomu employee 
    // znajdź konto po member_id
    public function employeeCreate(Request $request)
    {
        // sprawdzenie czy istnieje konto użytkownika


    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'first_name' => ['required', 'string', 'max:255'],
    //         'last_name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:members'],
    //         'tel' => ['required', 'string', 'max:255', 'unique:members'],
    //         'address' => ['required', 'string', 'max:255'],
    //         'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
    //         'role_id' => ['required', 'exists:roles,id'],
    //     ]);

    //     Member::create([
    //         'first_name' => $request->first_name,
    //         'last_name' => $request->last_name,
    //         'email' => $request->email,
    //         'tel' => $request->tel,
    //         'address' => $request->address,
    //         'password' => Hash::make($request->password),
    //         'role_id' => $request->role_id,
    //     ]);

    //     return redirect()->route('admin.dashboard')->with('success', 'Pracownik został pomyślnie utworzony.');
    // }
}