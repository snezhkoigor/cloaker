<?php

namespace App\Http\Controllers;

use App\Models\Cloaker;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LandingController extends Controller
{
	/**
	 * @param $campaign_id
	 * @return \Illuminate\View\View
	 */
    public function __invoke($campaign_id = null): View
    {
    	print
<<< FM
		<div id='xyz'>false</div>
		<script>
			sessionStorage.setItem("device_motion", false);

			window.addEventListener('devicemotion', function(e) {
				ax = e.accelerationIncludingGravity.x;
				ay = -e.accelerationIncludingGravity.y;
				az = -e.accelerationIncludingGravity.z;
				rotx = e.rotationRate.alpha ;
				roty = e.rotationRate.beta ;
				rotz = e.rotationRate.gamma ;

				document.getElementById('xyz').innerHTML = 'true';
			});
		</script>
FM;

        $campaign = DB::table('campaigns')
			->select([
				'campaigns.name',
				'campaigns.black_landing',
				'campaigns.white_landing',
				'campaigns.cloaking_server_id',
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
				->select([ 'dictionaries.platforms.name', 'dictionaries.platforms.rule', 'dictionaries.platforms.check_device_motion' ])
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
				DB::table('activity_log')
					->updateOrInsert([
							'description' => $cloaker->ip
						], [
							'log_name' => 'cloaking',
							'description' => $cloaker->ip,
							'subject_type' => 'App\Models\Cloaking\Server',
							'subject_id' => $campaign->cloaking_server_id,
							'causer_id' => null,
							'causer_type' => null,
							'properties' => json_encode([
								'attributes' => [
									'campaign_id' => (int)$campaign_id,
									'geo' => $cloaker->geo,
									'user_agent' => $cloaker->user_agent,
									'is_showed_black' => $cloaker->isShowBlackLanding($platforms, $countries),
								],
								'old' => []
							]),
							'created_at' => Carbon::now(),
							'updated_at' => Carbon::now()
						]
					);
			}

			return view('landing', (array)$campaign, []);
        }

        return view('welcome');
    }
}
