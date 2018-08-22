<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class XyzController extends Controller
{
	/**
	 * @return \Illuminate\View\View
	 */
    public function __invoke(): View
    {
        return view('welcome');
    }
}
