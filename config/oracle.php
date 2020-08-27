<?php

return [
    'oracle' => [
        'driver'         => 'oracle',
        'tns'            => env('ORACLE_DB_TNS', 'oposse'),
        'host'           => env('ORACLE_DB_HOST', '192.168.1.40'),
        'port'           => env('ORACLE_DB_PORT', '1521'),
        'database'       => env('ORACLE_DB_DATABASE', ''),
        'username'       => env('ORACLE_DB_USERNAME', 'produccion'),
        'password'       => env('ORACLE_DB_PASSWORD', 'bitss79'),
        'charset'        => env('ORACLE_DB_CHARSET', 'AL32UTF8'),
        'prefix'         => env('ORACLE_DB_PREFIX', ''),
        'prefix_schema'  => env('ORACLE_DB_SCHEMA_PREFIX', ''),
        'edition'        => env('ORACLE_DB_EDITION', 'ora$base'),
        'server_version' => env('ORACLE_DB_SERVER_VERSION', '11g'),
    ],
];
