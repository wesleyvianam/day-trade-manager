<?php

namespace App\Http\Controllers;

use App\Models\Operation;

class OperationController extends Controller
{
    public function index()
    {
        return view('operation.index');
    }
}
