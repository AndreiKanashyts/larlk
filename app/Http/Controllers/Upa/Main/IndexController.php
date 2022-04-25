<?php

namespace App\Http\Controllers\Upa\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        return view('upa.main.index');
    }
}
