<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LandingController extends Controller
{
	/**
	 * @param $campaign_id
	 * @return \Illuminate\View\View
	 */
    public function index($campaign_id): View
    {
        if ($campaign_id)
        {
            return view('landing', []);
        }

        return view('welcome');
    }

    //
}
