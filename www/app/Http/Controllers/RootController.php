<?php

namespace App\Http\Controllers;

use App\Models\Cloaker;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RootController extends Controller
{
	/**
	 * @param $campaign_id
	 * @return \Illuminate\View\View
	 */
    public function index($campaign_id = null): View
    {
    	$campaign = DB::table('campaigns')
			->select([
				'campaigns.name',
				'campaigns.black_landing',
				'campaigns.white_landing',
				DB::raw('NULL as landing_html'),
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

			$platforms = DB::table('dictionaries.platforms')
				->select([ 'dictionaries.platforms.name', 'dictionaries.platforms.rule' ])
				->join('offers_has_platforms', 'offers_has_platforms.platform_id', '=', 'dictionaries.platforms.id')
				->where('offers_has_platforms.offer_id', $campaign->offer_id)
				->get()
				->toArray();

			$countries = DB::table('dictionaries.countries')
				->join('offers_has_countries', 'offers_has_countries.country_id', '=', 'dictionaries.countries.id')
				->where('offers_has_countries.offer_id', $campaign->offer_id)
				->pluck('dictionaries.countries.iso_3166_2')
				->toArray();

			$campaign->landing_html = $cloaker->isShowBlackLanding($platforms, $countries) ?  $campaign->black_landing :  $campaign->white_landing;
			$campaign->landing_html = str_replace('{offer_link}', $campaign->offer_link, $campaign->landing_html);

			if ($cloaker->ip)
			{
				DB::table('cloaking.logs')
					->updateOrInsert([
							'ip' => $cloaker->ip
						], [
							'campaign_id' => (int)$campaign_id,
							'platform' => json_encode((array)$cloaker->platform, JSON_FORCE_OBJECT),
							'geo' => json_encode($cloaker->geo),
							'user_agent' => $cloaker->user_agent,
							'is_showed_black' => $cloaker->isShowBlackLanding($platforms, $countries),
							'datetime' => Carbon::now()->format('Y-m-d H:i:s')
						]
					);
			}

			return view('landing', (array)$campaign, []);
        }

        return view('welcome');
    }
}
