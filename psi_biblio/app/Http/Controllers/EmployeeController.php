<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class EmployeeController extends Controller
{
    // strona główna pracowników
    public function index()
    {
        return view('employees.index');
    }
    //zwrócenie strony z listą użytkowników z wwyszukiwania
    public function search(Request $request)// for members
    {
        // dump($request->input('query'));
        $myApi_URL = config('app.url') . "/api/members/search?query=" . $request->input('query');
        // dump($myApi_URL);
        $res = Http::withbasicAuth('student', 'student')->get($myApi_URL);
        // dump($res);
        $members = json_decode(json_encode($res->json()['data']), false);
        // erory
        if ($res->failed()) {
            $error = $res->json()['message'];
            return view('employees.search', ['members' => $members,'error' => $error]);
        }
        // dump($members);
        return view('employees.search', ['members' => $members]);
    }
    public function workersList(Request $request)
    {
        $myApi_URL = config('app.url') . "/api/members/find/workers";
        $res = Http::withbasicAuth('student', 'student')->get($myApi_URL);
        // dump($res);
        $members = json_decode(json_encode($res->json()['data']), false);
        // erory
        if ($res->failed()) {
            $error = $res->json()['message'];
            return view('employees.search', ['members' => $members,'error' => $error]);
        }
        // dump($members);
        return view('employees.search', ['members' => $members]);
    }

    public function hire(Request $request)
    {
        // zatrudnienie /api/members/hire/{member_id}
        $myApi_URL = config('app.url') . "/api/members/hire/". $request->input('member_id');
        $res = Http::withbasicAuth('student', 'student')->post($myApi_URL);
        // dump($myApi_URL);
        // erory
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

    public function fire(Request $request)
    {
        // zatrudnienie /api/members/hire/{member_id}
        $myApi_URL = config('app.url') . "/api/members/fire/". $request->input('member_id');
        $res = Http::withbasicAuth('student', 'student')->post($myApi_URL);
        // dump($myApi_URL);
        // erory
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


}
