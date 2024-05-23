<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    //Show Profile
    public function showProfile(Request $request)
    {
        //dump($request);
        $id=Auth::user()->member_id;
        //dump($id);
        $myApi_URL_1 = config('app.url') . "/api/loans/user/" . $id;
        //dump($myApi_URL_1);
        $res1 = Http::withbasicAuth('student', 'student')->get($myApi_URL_1);
        //dump($res1);
        $loansRaw = json_decode(json_encode($res1->json()['data']), false);
        //dump($loansRaw);
        $today=date("Y-m-d");
        //dump($today);
        return view('members.profile', ['loans' => $loansRaw, 'today' => $today]);
    }

}
