<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;


class BookController extends Controller
{
    public function book()
    {
        $data = "Data All Book";
        return response()->json($data, 200);
    }

    public function bookAuth()
    {
        $data = "Welcome " . Auth::user()->name;
        return response()->json($data, 200);
    }
}