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
			$campaign = DB::table('campaigns')
				->select([
					'campaigns.white_landing',
					'campaigns.black_landing',
					DB::raw('offers.link as offer_link'),
					DB::raw('offers.id as offer_id')
				])
				->join('offers', 'campaigns.offer_id', '=', 'offers.id')
				->whereNotNull('cloaking_server_id')
				->where('campaigns.id', (int)$campaign_id)
				->where('campaigns.active', true)
				->where('offers.active', true)
				->first();

			if ($campaign)
			{
				$cloaker = new Cloaker();
				
				var_dump($cloaker);

				return view('landing', []);
			}
        }

        return view('welcome');
    }

    //
}
