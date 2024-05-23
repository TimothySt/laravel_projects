<?php
// app/Http/Controllers/AuthorsController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;
use Illuminate\Support\Facades\Http;

class AuthorsController extends Controller
{
    public function create()
    {
        return view('authors.create');
    }

    public function store(Request $request)
    {
        //dump($request->all());
        $myApi_URL=config('app.url')."/api/authors/new";
        //dump($myApi_URL);
        $respone=Http::withbasicAuth('student','student')->post($myApi_URL,$request);
        //dump($respone->status());
        if ($respone->status()==400) {
            return redirect('/authors/create')->with('error', 'Error: '.$respone->json()['error']);
            //dump($respone);
        }
        else {
            return redirect('/authors/list')->with('success', 'Autor dodany pomyslnie');
            //dump($respone->json());
        }
    }

public function list()
    {
        //api
        $myApi_URL=config('app.url')."/api/authors/";
        // dump($myApi_URL);
        $res=Http::withbasicAuth('student','student')->get($myApi_URL);
        // dump($res);
        $authors=$res->json()['authors'];
        // dump($authors);
        return view('authors.list', compact('authors'));
    }

    public function show($id)
    {
        //api
        $myApi_URL=config('app.url')."/api/authors/find/".$id."/books";
        //dump($myApi_URL);
        $res=Http::withbasicAuth('student','student')->get($myApi_URL);
        //dump($res);
        $author=json_decode(json_encode($res->json()['author']));
        //dump($author);
        $books=json_decode(json_encode($res->json()['books']));
        //dump($books);
        return view('authors.show', compact('author','books'));
    }

    public function edit($id)
    {
        //api
        $myApi_URL=config('app.url')."/api/authors/find/".$id;
        //dump($myApi_URL);
        $res=Http::withbasicAuth('student','student')->get($myApi_URL);
        //dump($res);
        $author=$res->json()['author'];
        //dump($author);
        return view('authors.edit', compact('author'));
    }

    public function update(Request $request, $id)
    {
        //dump($request->all());
        //api
        $myApi_URL=config('app.url')."/api/authors/edit/".$id;
        //dump($myApi_URL);
        $res=Http::withbasicAuth('student','student')->put($myApi_URL,$request);
        // dump($res);
        if ($res->status()==400) {
            return redirect('/authors/edit/'.$id)->with('error', 'Error: '.$res->json()['error']);
            //dump($res);
        }
        else {
            return redirect('/authors/list')->with('success', 'Autor zmieniony pomyslnie');
            //dump($res);
        }
    }

}
