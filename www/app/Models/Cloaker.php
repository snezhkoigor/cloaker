<?php
	
namespace App\Models;

class Cloaker
{
	public $platform;


	public $geo;


	public $ip;


	public $user_agent;


	/**
	 * Cloaker constructor.
	 */
	public function __construct()
	{
		$this->setIp();
		$this->setGeo();
		$this->setPlatform();
		$this->setUserAgent();
	}


	/**
	 * Получение страны текущего соединения по IP
	 */
	public function setGeo(): void
	{
		if ($this->ip)
		{
			$record = app()->geoip->getLocation($this->ip);
			if ($record)
			{
				$this->geo = $record;
			}
		}
	}


	/**
	 * Получение платформы
	 */
	public function setPlatform(): void
	{
		$detect = new \Mobile_Detect();
		if ($detect)
		{
			$this->platform = $detect;
		}
	}


	/**
	 * Получение платформы
	 */
	public function setUserAgent(): void
	{
		$this->user_agent = $_SERVER['HTTP_USER_AGENT'];
	}


	/**
	 * Получение IP текущего соединения
	 */
	public function setIp(): void
	{
		foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'] as $key)
		{
			if (array_key_exists($key, $_SERVER) === true)
	        {
	        	foreach (explode(',', $_SERVER[$key]) as $ip)
	            {
	                $ip = trim($ip); // just to be safe
	                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false)
	                {
	                    $this->ip = $ip;
	                }
	            }
	        }
        }
	}


	public function isPlatformRobot(): bool
	{
		$detect = new \Mobile_Detect();
		return $detect->is('Bot') || preg_match('/(zgrab\/0\.x|python\-requests|python\-urllib|facebookexternalhit\/1\.1|proximic|facebookexternalhit\/1\.0|facebook|facebookexternalhit|facebot)/i', $this->user_agent);
	}


	public function isIpInBlackList(): bool
	{
		return $this->ip && \in_array($this->ip, config('black_ips'), false);
	}


	public function isBadRequest(): bool
	{
		return empty($this->ip) || empty($this->country);
	}


	public function isUserPlatformGood(array $platforms = []): bool
	{
		$result = false;

		if (\count($platforms))
		{
			foreach ($platforms as $platform)
			{
				if (preg_match($platform->rule, $this->user_agent))
				{
					$result = true;
					break;
				}
			}
		}
		else
		{
			$result = true;
		}

		return $result;
	}


	public function isUserCountryGood( array $countries = []): bool
	{
		$result = false;

		if (\count($countries))
		{
			if (\in_array(mb_strtolower($this->geo->country->isoCode), $countries))
			{
				$result = true;
			}
		}
		else
		{
			$result = true;
		}

		return $result;
	}


	public function isShowBlackLanding(array $platforms = [], array $countries = []): bool
	{
		$countries = array_map('mb_strtolower', $countries);

		if ($this->isBadRequest() || $this->isPlatformRobot())
		{
			return false;
		}
		if ($this->isIpInBlackList())
		{
			return false;
		}
		if ($this->isUserPlatformGood($platforms) === false)
		{
			return false;
		}
		if ($this->isUserCountryGood($countries) === false)
		{
			return false;
		}

		return true;
	}
}