<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RuntimeController extends Controller
{
    public function index() {
        return view('index');
    }
}
