<?php

namespace App\Http\Controllers;

use App\Models\Cloaker;
use Illuminate\Support\Facades\DB;
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
        	$cloaker = new Cloaker();

        	$campaign = DB::table('campaigns')
				->select([
					DB::raw(($cloaker->isShowBlackLanding() ? 'campaigns.black_landing' : 'campaigns.white_landing' . ' as landing_html')),
					DB::raw('offers.link as offer_link'),
					DB::raw('offers.id as offer_id')
				])
				->join('offers', 'campaigns.offer_id', '=', 'offers.id')
				->whereNotNull('cloaking_server_id')
				->where('campaigns.id', (int)$campaign_id)
				->where('campaigns.active', true)
				->where('offers.active', true)
				->first();
var_dump($campaign);
			if ($campaign)
			{
				return view('landing', []);
			}
        }

        return view('welcome');
    }

    //
}
