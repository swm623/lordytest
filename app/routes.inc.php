<?php
/* Copyright (C) Traceclouds Systems, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Dr.NP <np@bsgroup.org>
 */

/**
 * @package lordy.api
 * @file routes.inc.php
 * @author Dr.NP <np@bsgroup.org>
 * @date 01/06/2018
 * @version 0.0.1
 *
 * Logic routes portal
 */

if (!\defined('IN_LORDY') || !$app)
{
    die('Inject denied');
}

$app->get('/', function($request, $response) {
    $this['result'] = 'Lordy.API';
    return $response;
})->setName('Root');

$app->get('/routes', function($request, $response) {
    $routes = $this->get('router')->getRoutes();
    $res = [];
    foreach ($routes as $route)
    {
        $res[] = [
            'name' => $route->getName(),
            'methods' => $route->getMethods(),
            'pattern' => $route->getPattern(),
            'arguments' => $route->getArguments()
        ];
    }
    $this['result'] = $res;
    return $response;
})->setName('GetRoutesList');

$app->get('/config', function($request, $response) {
    $this['result'] = $this->get('settings')->all();

    return $response;
})->setName('GetConfig');;

$app->get('/flushcache', function($request, $response) {
    $redis = $this->get('redis');
    $redis->flushall();
    $this['result_message'] = 'Cache flushed';

    return $response;
})->setName('Flushcache');;

require_once __DIR__ . '/logic/user.logic.php';

$app->get('/info/{token}',  \Pixie\Logic\User::class . ':info')->setName('UserInfo');
$app->get('/create/{uid}',  \Pixie\Logic\User::class . ':create')->setName('createInfo');
$app->get('/test',  \Pixie\Logic\User::class . ':testShare')->setName('test');


/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: sw=4 ts=4 fdm=marker
 * vim<600: sw=4 ts=4
 */
