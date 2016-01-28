<?php 

$conn = array (
        'driver'    => $_ENV["BOOKMARKS_DBDRIVER"],
        'host'      => $_ENV["BOOKMARKS_DBHOST"],
        'port'      => $_ENV["BOOKMARKS_DBPORT"],
        'dbname'    => $_ENV["BOOKMARKS_DBNAME"],
        'user'      => $_ENV["BOOKMARKS_DBUSER"],
        'password'  => $_ENV["BOOKMARKS_DBPWD"]
    );
