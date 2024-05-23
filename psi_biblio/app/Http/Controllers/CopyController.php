<?php

// app/Http/Controllers/CopyController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CopyController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'isbn' => 'required',
            'copy_id' => 'required|min:10|max:25',
            'title_id' => 'required',
        ],[
            'isbn.required' => 'ISBN jest wymagane',
            'copy_id.required' => 'Numer egzemplarza jest wymagany',
            'copy_id.min' => 'Numer egzemplarza musi mieć min 10 znaków',
            'copy_id.max' => 'Numer egzemplarza musi mieć 25 znaków',
            'title_id.required' => 'Tytuł jest wymagany',
        ]);
        //dump($request->all());
        $myApi_URL = config('app.url') . "/api/copies/new";
        //dump($myApi_URL);
        $res = Http::withbasicAuth('student', 'student')->post($myApi_URL, $request);
        //dump($res);
        $message = $res->json();
        //dump($message);
        if ($res->status() == 400) {
            return redirect()->route('books.show',['book' => $request['isbn']])->with('message', $message['error']);
        } else {
            return redirect()->route('books.show',['book' => $request['isbn']])->with('message', $message['message']);
        }
    }
}

