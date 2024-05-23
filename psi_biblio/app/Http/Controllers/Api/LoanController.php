<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Copy;
use App\Models\Member;
use App\Models\LoanStatus;
use Carbon\Carbon;



class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loans = Loan::with('status')->get();
        return response()->json($loans);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Walidacja danych wejściowych copy_id exists
        

        $request->validate([
            'copy_id' => 'required|string|min:10|max:25',
            'member_id' => 'required|integer',
        ]);

        // // Sprawdź autoryzację (role)
        // $this->authorize('create', Loan::class);

        // Sprawdź czy istnieje egzemplarz
        $copy = Copy::find($request->copy_id);
        if (!$copy) {
            return response()->json(['message' => 'Egzemplarz nie znaleziony.'], 404);
        }

        // Sprawdź czy egzemplarz jest dostępny
        if (!$copy->available) {
            return response()->json(['message' => 'Egzemplarz niedostępny.'], 400);
        }

        // Sprawdź czy istnieje member
        $member = Member::find($request->member_id);
        if (!$member) {
            return response()->json(['message' => 'Użytkownik nie znaleziony.'], 404);
        }

        // Ustaw daty i status
        $loanDate = now(); // dzisiejsza data
        $dueDate = now()->addDays(30); // data zwrotu za 30 dni
        $loanStatus = LoanStatus::findByName('borrowed');
        $loanStatusId = $loanStatus->loan_status_id;

        // Utwórz nowe wypożyczenie
        $loan = Loan::create([
            'copy_id' => $request->copy_id,
            'member_id' => $request->member_id,
            'loan_date' => $loanDate,
            'due_date' => $dueDate,
            'loan_status_id' => $loanStatusId,
        ]);

        // ustawienie statusu dostępności ksiązki na false
        $copy->available = false;

        // zaktualizowanie istniejącego wpisu
        $copy->update();

        return response()->json(['loan'=>$loan,'message'=>'Wypożyczono książkę pomyślnie'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($copy_id)
    {
        $loan = Loan::with('status')->find($copy_id);

        if (!$loan) {
            return response()->json(['message' => 'Wypożyczenie nie znalezione.'], 404);
        }

        return response()->json($loan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Pobierz wypożyczenie
        $loan = Loan::find($id);

        if (!$loan) {
            return response()->json(['message' => 'Wypożyczenie nie znalezione.'], 404);
        }

        // Walidacja danych wejściowych
        $request->validate([
            'copy_id' => 'string',
            'member_id' => 'integer',
            'loan_date' => 'date',
            'due_date' => 'date',
            'loan_status_id' => 'integer',
        ]);

        // Zaktualizuj dane wypożyczenia
        $loan->update($request->all());

        return response()->json($loan, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $loan = Loan::find($id);

        if (!$loan) {
            return response()->json(['message' => 'Wypożyczenie nie znalezione.'], 404);
        }

        $loan->delete();

        return response()->json(['message' => 'Wypożyczenie usunięte pomyślnie.'], 200);
    }

    
    /**
     * Extend the due date for a specific loan.
     */
    public function extendDueDate(Request $request) // przez id copi
    {
        $copy_id = $request->copy_id;
        // znadź kopie
        $copy = Copy::find($copy_id);
        if(!$copy){
            return response()->json(['message' => 'Egzemplarz nie znaleziony.'], 404);
        }
        
        // znajdź ostatnie wypożyczenie tego egzemplarza
        $loan = Loan::where('copy_id', $copy_id)->latest()->first();
        if(!$loan){
            return response()->json(['message' => 'Wypożyczenie nie znalezione.','loan'=>$loan], 404);
        }

        // sprawdź czy już przedłużono, jeśli 'loan_date', 'due_date', mają więcej niż 89 dni pomiędzy
        // $loanDate = $loan->loan_date;
        // $dueDate = $loan->due_date;
        $loanDate = Carbon::parse($loan->loan_date);
        $dueDate = Carbon::parse($loan->due_date);
        $DateDiff = $loanDate->diffInDays($dueDate);
        if($DateDiff > 89){
            return response()->json(['message' => 'Wypożyczenie nie może zostać przedłużone. Przedłużono już wypożyczenie do 90 dni.','loan'=>$loan], 400);
        }
        
        $loanStatus = LoanStatus::findByName('borrowed');
        $loanStatusId = $loanStatus->loan_status_id;

        // Sprawdź, czy można przedłużyć
        if ($loan->loan_status_id != $loanStatusId) {
            return response()->json(['message' => 'Wypożyczenie nie może zostać przedłużone.','loan'=>$loan], 400);
        }

        try
        {
            // Przedłuż termin zwrotu o kolejne 30 dni
            // $loan->due_date = $loan->due_date->addDays(30);
            // $loan->due_date = Carbon::parse($loanDate)->addDays(30); // data zwrotu za 30 dni
            $loan->due_date = Carbon::parse($dueDate)->addDays(30)->startOfDay();

            $loan->save(); // save()
            return response()->json(['message' => 'Data oddania przedłużona pomyślnie.','loan'=>$loan], 200);
            
        }
        catch(\Exception $e)
        {
            return response()->json(['message' => 'Wypożyczenie nie może zostać przedłużone.','loan'=>$loan], 400);
        }
    }

    /**
     * Get loans for a specific user (member_id).
     */
    public function getUserLoans($memberId)
    {
        // // Sprawdź autoryzację (role)
        // $this->authorize('viewAny', Loan::class);

        // Pobierz wypożyczenia dla użytkownika
        // $loans = Loan::where('member_id', $memberId)->get();

        // Pobierz wypożyczenia dla użytkownika z dodatkowymi informacjami
        $loans = Loan::with(['status'])->where('member_id', $memberId)->get();

        // // Pobierz wypożyczenia dla użytkownika z dodatkowymi informacjami
        // $loans = Loan::with(['status', 'copy.title', 'copy.title.publisher'])
        // ->where('member_id', $memberId)
        // ->get();

        // foreach loan in loans znajdź copy with book
        foreach ($loans as $loan) {
            $copy = Copy::with(['book'])->where('copy_id', $loan->copy_id)->first();
            if ($copy) {
                $loan->copy = $copy;
            }
        }


        return response()->json(['data' => $loans], 200);
    }

    /**
     * Return a book (update loan status to 'returned').
     */
    public function returnBook($copy_id)// by BookCopy copy_id
    {
        // znadź egzemplarz
        $copy = Copy::find($copy_id);
        // znajdź ostatnie wypożyczenie tego egzemplarza
        $loan = Loan::where('copy_id', $copy_id)->latest()->first();

        // Sprawdź, czy można zwrócić
        $loanStatus = LoanStatus::findByName('borrowed');
        $loanStatusId = $loanStatus->loan_status_id;

        if ($loan->loan_status_id === $loanStatusId) {
            // Ustaw status na 'returned'
            $loan->loan_status_id = LoanStatus::where('status', 'returned')->value('loan_status_id');
            $loan->update();

            // ustawienie statusu dostępności ksiązki na true
            $copy->available = true;
            // zaktualizowanie istniejącego wpisu
            $copy->update();

            return response()->json(['message' => 'Książka zwrócona.'], 200);
        }
        
        return response()->json(['message' => 'Książki nie można zwrócić.'], 400);
    }

}
