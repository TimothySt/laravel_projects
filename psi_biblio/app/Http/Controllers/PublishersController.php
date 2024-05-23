<?php
// app/Http/Controllers/PublishersController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publisher;
use Illuminate\Support\Facades\Http;

class PublishersController extends Controller
{
    public function create()
    {
        return view('publishers.create');
    }

    public function store(Request $request)
    {
        // dump($request);
        // $request->validate([
        //     'name' => 'required|string|unique:publishers,name',
        //     'description' => 'nullable|string',
        // ]);
        $myApi_URL=config('app.url')."/api/publishers/new";
        $response = Http::withBasicAuth('student', 'student')->post($myApi_URL, $request);
        // dump($response);
        return redirect()->route('publishers.list');
        
    }

    //edit
    public function edit($id)
    {
        //publisher id
        $myApi_URL_1 = config('app.url') . "/api/publishers/find/" . $id;
        //dump($myApi_URL_2);
        $res1 = Http::withbasicAuth('student', 'student')->get($myApi_URL_1);
        //dump($res2);
        $publisher = json_decode(json_encode($res1->json()), false);
        //dump($publisher);
        return view('publishers.edit', compact('publisher'));

    }
    //update
    public function update(Request $request, $publisher_id)
    {
        // dump($request);
        // $request->validate([
        //     'name_new' => 'required|string|unique:publishers,name,' . $publisher_id . ',publisher_id',
        //     'description_new' => 'nullable|string',
        // ]);
        // dump($request);
        $myApi_URL=config('app.url')."/api/publishers/edit/".$publisher_id;
        // dump($myApi_URL);
        $response = Http::withBasicAuth('student', 'student')->put($myApi_URL, $request);
        // dump($response);
        //return redirect()->route('publishers.list')->with('success', 'Wydawca został zaktualizowany');
        if ($response->status()!==200) {
            return redirect('/publishers/edit/'.$publisher_id)->with('error', 'Error: '.$response->json()['error']);
            // dump($response);
        }
        else {
            return redirect('/publishers/list')->with('success', 'Autor zmieniony pomyslnie');
            // dump($response);
        }
    }

    //list
    public function list()
    {
        $publishers = Publisher::all();

        return view('publishers.list', compact('publishers'));
    }

}
