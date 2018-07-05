<?php

return [
	'default' => 'public',

	'connections' => [
		'public' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],
	],

	'redis'      => [
		'cluster' => false,
		'default' => [
			'host'     => env('REDIS_HOST'),
			'port'     => env('REDIS_PORT', 6379),
			'password' => env('REDIS_PASS'),
			'database' => 0,
		],
		'scripts' => [
			'host'     => env('REDIS_SCRIPTS_HOST', env('REDIS_HOST')),
			'port'     => env('REDIS_SCRIPTS_PORT', env('REDIS_PORT')),
			'password' => env('REDIS_SCRIPTS_PASS', env('REDIS_PASS')),
			'database' => 0,
		],
	],
	'migrations' => 'migrations',
];