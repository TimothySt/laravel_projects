<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MembersController extends Controller
{
    public function index()
    {
        // Pobierz wszystkich członków
        // $members = Member::all();
        // razem z rolą
        $members = Member::with('role')->get();
        // razem z rolą
        // $members = Member::with('role')->get();

        return response()->json(['data' => $members], 200);
    }

    public function show($id)
    {
        // Pobierz konkretnego członka
        $member = Member::find($id);

        if (!$member) {
            return response()->json(['message' => 'Użytkownik nie znaleziony.'], 404);
        }

        return response()->json(['data' => $member], 200);
    }

    public function store(Request $request) // TODO
    {
        // // walidacja według zasad z modelu Member
        // $validator = Validator::make($request->all(), Member::rules()); // TODO naprawić zasady walidacji w modelu

        // // sprawdzenie czy dane są poprawne
        // if ($validator->fails()) {
        //     throw new ValidationException($validator);
        // }

        // Walidacja danych z formularza lub JSONa
        try {
            $validatedData = $request->validate([
                // 'role_id' => 'required',
                'first_name' => ['required', 'string', 'max:255'],// TODO - formatowanie
                'last_name' => ['required', 'string','max:255'],// TODO - formatowanie
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Member::class],
                'tel' => ['required', 'string', 'max:255','regex:/^(\+\d{1,15}|\d{1,15})$/', 'unique:'.Member::class],
                'address' => ['required', 'string', 'max:255'],// TODO - formatowanie
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
        } catch (ValidationException $e) {
            // Przechwyć wyjątek i dostosuj odpowiedź JSON do Twoich potrzeb
            $errors = $e->errors()->toArray();
    
            return response()->json(['message' => 'Dane są niepoprawne.', 'errors' => $errors], 400);
        }


        // // sprawdzenie czy istnieje Użytkownik o podanym emailu lub numerze telefonu
        // $memberMail = Member::where('email', $validatedData['email']);
        // //  zwrot błędu jeśli istnieje
        // if ($memberMail) {
        //     return response()->json(['message' => 'Użytkownik o podanym adresie email już istnieje.'], 400);
        // }
        // $memberTel = Member::where('tel', $validatedData['tel']);
        // if ($memberTel) {
        //     return response()->json(['message' => 'Użytkownik o podanym numerze telefonu już istnieje.'], 400);
        // }

        // sprawdzenie czy dane są poprawne
        if (!$validatedData) {
            return response()->json(['message' => 'Dane są niepoprawne.'], 400);
        }

        // Stworzenie nowego członka
        $member = Member::create([
            // 'role_id' => $validatedData['role_id'],
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'tel' => $validatedData['tel'],
            'address' => $validatedData['address'],
            'password' => Hash::make($validatedData['password']),
        ]);
        // sprawdzenie czy Użytkownik został stworzony
        if (!$member) {
            return response()->json(['message' => 'Użytkownik nie został stworzony.'], 500);
        }

        event(new Registered($member)); // Wysłanie maila z linkiem aktywacyjnym


        return response()->json(['message' => 'Użytkownik został pomyślnie dodany.'], 201);
    }

    public function update(Request $request, $id) // TODO - edytować może tylko admin, pracownik konta użytkowników i użytkownik własne dane
    {
        // // Walidacja danych z formularza lub JSONa
        // $validatedData = $request->validate([
        //     // 'role_id' => 'required',
        //     'first_name' => 'required|string',
        //     'last_name' => 'required|string',
        //     'email' => 'required|email|unique:members,email,' . $id,
        //     'tel' => 'required|string|unique:members,tel,' . $id,
        //     'address' => 'nullable|string',
        // ]);

        try
        {
            $validatedData = $request->validate([
                // 'role_id' => 'required',
                'first_name' => ['required', 'string', 'max:255'],// TODO - formatowanie
                'last_name' => ['required', 'string','max:255'],// TODO - formatowanie
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Member::class].$id,
                'tel' => ['required', 'string', 'max:255','regex:/^(\+\d{1,15}|\d{1,15})$/', 'unique:'.Member::class].$id,
                'address' => ['required', 'string', 'max:255'],// TODO - formatowanie
            ]);
        }
        catch (ValidationException $e)
        {
            // Przechwyć wyjątek i dostosuj odpowiedź JSON do Twoich potrzeb
            $errors = $e->errors()->toArray();
        
            return response()->json(['message' => 'Dane są niepoprawne.', 'errors' => $errors], 400);
        }

        // Aktualizacja danych członka
        $member = Member::find($id);

        if (!$member) {
            return response()->json(['message' => 'Użytkownik nie znaleziony.'], 404);
        }

        $member->update([
            // 'role_id' => $validatedData['role_id'],
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'tel' => $validatedData['tel'],
            'address' => $validatedData['address'],
        ]);

        return response()->json(['message' => 'Dane członka zostały pomyślnie zaktualizowane.'], 200);
    }

    public function destroy($id) // TODO - usunąć może tylko admin
    {
        // Usunięcie członka
        $member = Member::find($id);

        if (!$member) {
            return response()->json(['message' => 'Użytkownik nie znaleziony.'], 404);
        }

        $member->delete();

        return response()->json(['message' => 'Użytkownik został pomyślnie usunięty.'], 200);
    }

    public function employ($member_id)
    {
        // Zatrudnienie członka

        $member = Member::find($member_id);

        if (!$member) {
            return response()->json(['message' => 'Użytkown nie znaleziony.'], 404);
        }

        // sprawdznie czy ma role 'user'
        if ($member->role->name != 'user') {
            return response()->json(['message' => 'Użytkownik nie jest użytkownikiem.'], 400);
        }

        // znajdź role by name
        $employeeRole = Role::findByName('employee');
        // znajdź id dla 'employee'
        $role_id = $employeeRole->role_id;

        // zmień role_id na znalezione
        $member->update([
            'role_id' => $role_id,
        ]);
        return response()->json(['message' => 'Użytkownik został pomyślnie zatrudniony.','role_id' => $role_id], 200);
    }

    public function discharge($member_id)
    {
        // Zwolnienie członka
        $member = Member::find($member_id);

        if (!$member) {
            return response()->json(['message' => 'Użytkownik nie znaleziony.'], 404);
        }

        // czy ma rolę employee
        if ($member->role->name != 'employee') {
            return response()->json(['message' => 'Użytkownik nie jest pracownikiem.'], 400);
        }

        // znajdź id dla 'user'
        $userRole = Role::findByName('user');
        $role_id = $userRole->role_id;

        // zmień role_id na znalezione
        $member->update([
            'role_id' => $role_id,
        ]);
        return response()->json(['message' => 'Użytkownik został pomyślnie zwolniony.'], 200);
    }

    public function search(Request $request)
    {
        // Pobierz zapytanie wyszukiwania z parametru żądania
        $searchTerm = $request->input('query');
        
        $members = Member::with('role')->search($searchTerm)->get();
        

        // Sprawdź, czy znaleziono wyniki
        if ($members->isEmpty()) {
            return response()->json(['message' => 'Brak wyników wyszukiwania.', 'data' => $members], 404);
        }

        // Zwróć znalezione konta
        return response()->json(['data' => $members], 200);
    }

    public function findWorkers()// znajdź wszystkie konta gdzie role_id odpowiada 'employee' or 'admin'
    {
        // znajdź id dla admina
        $adminRole = Role::findByName('admin');
        $adminRoleId = $adminRole->role_id;
        // znajdź id employee
        $employeeRole = Role::findByName('employee');
        $employeeRoleId = $employeeRole->role_id;
        
        $members = Member::with('role')->where('role_id', $adminRoleId)->orWhere('role_id', $employeeRoleId)->get();
        // razem z rolą
        // $members = Member::with('role')->get();

        // sort byr role name
        $members = $members->sortBy('role.name');

        return response()->json(['data' => $members], 200);

    }
}
