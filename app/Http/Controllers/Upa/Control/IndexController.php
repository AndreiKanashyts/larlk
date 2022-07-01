<?php

namespace App\Http\Controllers\Upa\Control;

use App\Http\Controllers\Controller;
use Auth;
//use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $auth_operator = [
            'IdOperator' => Auth::user()->idoperator,
            'LoginOperator' => Auth::user()->email,
            'Role' => Auth::user()->role
        ];

        return view('upa.controls.index', [
            'auth_operator' => $auth_operator
        ]);
    }
}
