<?php
/* Copyright (C) Traceclouds Systems, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Dr.NP <np@bsgroup.org>
 */

/**
 * @package lordy.api
 * @file handlers.inc.php
 * @author Dr.NP <np@bsgroup.org>
 * @date 01/06/2018
 * @version 0.0.1
 *
 * Slim exception handlers. All goes here
 */

if (!\defined('IN_LORDY') || !$app)
{
    die('Inject denied');
}

$container['notFoundHandler'] = function($container) {
    return function($request, $response) use ($container) {
        $container['result_message'] = 'Route not found';
        $container['result_code'] = \LordyError::ROUTE_NOT_FOUND;
        $container['result_links'] = [\SELF_URL_PERFIX . \LordyLinks::ROUTE_LIST];
        $container['http_code'] = 404;

        return $response;
    };
};

$container['notAllowedHandler'] = function($container) {
    return function($request, $response) use ($container) {
        $container['result_message'] = 'HTTP method not allowed';
        $container['result_code'] = \LordyError::METHOD_NOT_ALLOWED;
        $container['http_code'] = 405;

        return $response;
    };
};

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: sw=4 ts=4 fdm=marker
 * vim<600: sw=4 ts=4
 */
