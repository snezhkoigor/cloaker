<?php
	
namespace App\Models;

use Detection\MobileDetect;

class Cloaker
{
	public $referer = false;


	public $platform;


	public $country;


	public $ip;


	public $user_agent;


	/**
	 * Cloaker constructor.
	 */
	public function __construct()
	{
		$this->getReferer();
		$this->getIp();
		$this->getCountry();
	}


	/**
	 * Получение страны текущего соединения по IP
	 */
	public function getCountry(): void
	{
		if ($this->ip)
		{
			$record = app()->geoip->getLocation($this->ip);
			if ($record)
			{
				$this->country = mb_strtolower($record->country->isoCode);
			}
		}
	}


	/**
	 * Получение реферера текущего соединения
	 */
	public function getReferer(): void
	{
		if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']))
		{
			$this->referer = true;
		}
	}
	
	
	/**
	 * Получение IP текущего соединения
	 */
	public function getIp(): void
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
		return preg_match('/(zgrab\/0\.x|python\-requests|python\-urllib|facebookexternalhit\/1\.1|proximic|facebookexternalhit\/1\.0|facebook|facebookexternalhit|facebot)/i', $_SERVER['HTTP_USER_AGENT']);
	}


	public function isIpInBlackList(): bool
	{
		return $this->ip && \in_array($this->ip, config('black_ips'));
	}


	public function isBadRequest(): bool
	{
		return $this->referer || empty($this->ip) || empty($this->country);
	}


	public function isUserPlatformGood(array $platforms = []): bool
	{
		$result = false;

		if (\count($platforms))
		{
			foreach ($platforms as $platform)
			{
				if (preg_match($platform->rule, $_SERVER['HTTP_USER_AGENT']))
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
			if (\in_array($this->country, $countries))
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
		if ($this->isPlatformRobot())
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