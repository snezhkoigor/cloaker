<?php
	
namespace App\Models;

class Cloaker
{
	public $referer = false;


	public $platform;


	public $country;


	public $ip;


	public $user_agent;


	/**
	 * Список платформ для инициализации текущего соединения
	 *
	 * @var array
	 */
	public static $o = [
		'Windows' => '(Windows)',
		'Android' => '(Android)',
		'IOs' => '(iPod)|(iPhone)|(iPad)',
		'MacOS' => '(Mac OS)|(Mac_PowerPC)|(PowerPC)|(Macintosh)',
		'UNIX' => '(UNIX)',
		'Ubuntu' => '(Ubuntu)',
		'ChromeOS' => '(ChromeOS)|(ChromiumOS)',
		'Linux' => '(Linux)|(X11)',
		'Symbian' => '(SymbianOS)',
		'Robot' => '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp)|(msnbot)|(Ask Jeeves\/Teoma)|(ia_archiver)'
	];


	/**
	 * Cloaker constructor.
	 */
	public function __construct()
	{
		$this->getReferer();
		$this->getPlatform();
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
				$this->country = $record->country->isoCode;
			}
		}
	}
	
	
	/**
	 * Получение платформы текущего соединения
	 */
	public function getPlatform(): void
	{
		if (isset($_SERVER['HTTP_USER_AGENT']))
		{
			foreach (self::$o as $s => $p)
			{
				if (empty($this->platform) && preg_match('/' . $p . '/i', $_SERVER['HTTP_USER_AGENT']))
				{
					$this->platform = $s;
				}
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
}