<?php 
return array(
	'connDb' => array (
        'driver'    => $_ENV["BOOKMARKS_DBDRIVER"],
        'host'      => $_ENV["BOOKMARKS_DBHOST"],
        'port'      => $_ENV["BOOKMARKS_DBPORT"],
        'dbname'    => $_ENV["BOOKMARKS_DBNAME"],
        'user'      => $_ENV["BOOKMARKS_DBUSER"],
        'password'  => $_ENV["BOOKMARKS_DBPWD"]
		),

	'connRedis' => array(
		'scheme'		=> $_ENV["BOOKMARKS_REDISSCHEME"],
		'host'			=> $_ENV["BOOKMARKS_REDISHOST"],
		'port'			=> $_ENV["BOOKMARKS_REDISPORT"],
		'password'		=> $_ENV["BOOKMARKS_REDISPWD"]
		),

	'security' => array(
		'hash_sufix'	=> $_ENV["SECURITY_HASH_SUFIX"],
		'crypt_method'	=> $_ENV["CRYPT_METHOD"],
		'crypt_cost'	=> $_ENV["CRYPT_COST"],
		'crypt_salt'	=> $_ENV["CRYPT_SALT"],
		'token_timeout'	=> $_ENV["TOKEN_TIMEOUT"]
		)
	);