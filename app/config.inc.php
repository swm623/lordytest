<?php
/* Copyright (C) Traceclouds Systems, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Dr.NP <np@bsgroup.org>
 */

/**
 * Configurations
 */

\define('IN_LORDY', true);
\define('APP_NAME', 'testlordy');
\define('DEFAULT_APP_ENV', 'dev');
\define('DEFAULT_CONTENT_TYPE', 'application/json');
\define('APP_ID', 'wxa3e33354967e4641');
$app_env = \getenv('LORDY_ENV');
if (!$app_env)
{
    $app_env = \DEFAULT_APP_ENV;
}

// Configuration values
$settings = [
    'db' => [
        'dsn' => 'mysql:dbname=lordy;host=localhost',
        'user' => 'root',
        'pass' => ''
    ],
    'logger' => [
        'path' => __DIR__ . '/../logs/app.log',
        'level' => 'DEBUG'
    ],
    'redis' => [
        'host' => 'localhost',
        'port' => 6379,
        'db' => 0,
        'auth' => null
    ],
    'lordy' => [
        // This is equal to "http://localhost:9200/"
        'host' => 'http://_internal.api.lordy.fabuge.com'    // Only host is required
    ],
    'accepted_content_type' => [
        'application/json',
        'application/x-msgpack',
        'application/x-php'
    ],
    'runtime' => [
        'enable_auth' => false,
        'enable_envelope' => true
    ],
    'host' => 'localhost',
];

// Sub-conf of enviroments
$settings_dev = [
    'db' => [
        'dsn' => 'mysql:dbname=lordy;host=localhost',
        'user' => 'root',
        'pass' => '123456'
    ],
    'redis' => [
        'host' => 'localhost',
        'port' => 6379,
        'db' => 1,
        'auth' => false
    ],
    'lordy' => [
        // This is equal to "http://localhost:9200/"

        'host' => 'http://_internal.api.lordy.fabuge.com'    // Only host is required

    ],
    'host' => 'dev.api.lordy.duisini.com',
    'ssl' => false,
];

$settings_online = [
    'db' => [
        'dsn' => 'mysql:dbname=lordy;host=10.66.166.72',
        'user' => 'root',
        'pass' => 'blueSkin817176##'
    ],
    'redis' => [
        'host' => 'localhost',
        'port' => 6379,
        'db' => 1,
        'auth' => false
    ],
    'lordy' => [
        // This is equal to "http://localhost:9200/"
        [
            'host' => 'http://_internal.api.lordy.fabuge.com'    // Only host is required
        ]
    ],
    'host' => 'api.lordy.duisini.com',
    'ssl' => true,
];

$sub_settings = 'settings_' . $app_env;
if (\is_array($$sub_settings))
{
    // Override
    $settings = \array_merge($settings, $$sub_settings);
    $settings['app_env'] = $app_env;
}

\define('SELF_URL_PERFIX', ($settings['ssl'] ? 'https://' : 'http://') . $settings['host']);

return [
    'settings' => $settings,
    'result' => [],
    'result_content_type' => \DEFAULT_CONTENT_TYPE,
    'result_code' => \LordyResponse::RESULT_OK,
    'result_message' => '',
    'result_links' => [],
    'http_code' => 200,
    'http_auth_type' => 'none',
    'http_auth_status' => \LordyAuth::AUTH_OK,
    'user_id' => \LordyUser::USER_NOT_LOGGED,
    'user_info' => []
];
