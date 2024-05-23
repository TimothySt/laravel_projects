<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class LoanController extends Controller
{
    // new loan redirect
    public function newLoan(Request $request)
    {
        // pobranie danych użytkownika
        $myApi_URL = config('app.url') . "/api/members/find/" . $request->input('member_id');
        $res  = Http::withbasicAuth('student', 'student')->get($myApi_URL);
        if ($res->failed()) {
            $error = $res->json()['message'];
            // return view('employees.search', ['message' => $error]);
            // redirect back z message
            return redirect()->back()->with('error', $error);
        }
        $member = json_decode(json_encode($res->json()['data']), false);

        // pobranie listy wypożyczeń użytkownika
        $myApi_URL_2 = config('app.url') . "/api/loans/user/" . $request->input('member_id');
        $res2 = Http::withbasicAuth('student', 'student')->get($myApi_URL_2);
        $loans = json_decode(json_encode($res2->json()['data']), false);
        //dump($loans);
        
        //
        $message = $request->message;
        if ( $message == null)
        {
            return view('loans.new-loan',['member'=> $member, 'loans'=>$loans] );
        }
        return view('loans.new-loan',['member'=> $member, 'loans'=>$loans,'loan_message', $message] );
    }

    // store loan
    public function store(Request $request)
    {
        // walidacja

        // zapisanie wypożyczenia
        // api api/loans/store
        $myApi_URL = config('app.url') . "/api/loans/store";
        try{
            $res = Http::withbasicAuth('student', 'student')->post($myApi_URL, $request);
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }

        if ($res->failed()) {

            $error = $res->json()['message'];
            return redirect()->back()->with('error', $error);
        }
        else
        {
            $message = $res->json()['message'];
            return redirect()->back()->with('message', $message);
        }
    }

    // przekierowanie do strony zwrotów
    public function returnLoan(Request $request)
    {
        // przkierowanie /loans/return
        return view('loans/return');
    }
    // return loan
    public function return(Request $request)
    {
        // walidacja

        // zwrot wypożyczenia
        // api api/loans/return
        $myApi_URL = config('app.url') . "/api/loans/return/".$request->input('copy_id');
        try{
            $res = Http::withbasicAuth('student', 'student')->post($myApi_URL);
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }

        if ($res->failed()) {
            $message = $res->json()['message'];
            // return view('employees.search', ['message' => $error]);
            // redirect back z message
            // return redirect()->back()->with('error', $error);
            //dump
            // dump($message);
            return redirect()->back()->with(['loan_message', $message]);
        }
        else
        {

            $message = $res->json()['message'];
            // return view('employees.search', ['message' => $message]);
            // redirect back z message
            
            // dump($message);
            return redirect()->back()->with(['loan_message', $message]);
        }
        
    }

    // przekierowanie do strony przedłużenia
    public function extendLoan(Request $request)
    {
        // Route::prefix('loans')->group(function () {
        //     Route::post('/extend', [LoanController::class, 'extendDueDate']);
        // });
        dump($request);
        $myApi_URL = config('app.url') . "/api/loans/extend";
        dump($myApi_URL);
        $copy_id=$request->input('copy_id');
        dump($copy_id);
        //try{
            $res = Http::withbasicAuth('student', 'student')->post($myApi_URL, $copy_id);
            dump($res);
            return redirect()->back()->with(['loan_message', $res]);
        //}
        //catch(\Exception $e){
            //return redirect()->back()->with('error', $e->getMessage());
        //}
    }


}