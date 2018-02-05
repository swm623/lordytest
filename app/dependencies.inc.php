<?php
/* Copyright (C) Traceclouds Systems, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Dr.NP <np@bsgroup.org>
 */

/**
 * @package lordy.api
 * @file dependencies.inc.php
 * @author Dr.NP <np@bsgroup.org>
 * @date 01/06/2018
 * @version 0.0.1
 *
 * Slim dependencies. All goes here
 */

if (!\defined('IN_LORDY') || !$app)
{
    die('Inject denied');
}

/* {{{ [Database] - PDO / dbal */
$container['db'] = function($c) {
    // PDO instance
    try
    {
        $db = new \PDO(
            $c->get('settings')['db']['dsn'],
            $c->get('settings')['db']['user'],
            $c->get('settings')['db']['pass']);
    }
    catch(\PDOException $e)
    {
        die('PDO error : ' . $e->getMessage());
    }

    return $db;
};

/* }}} */

/* {{{ [Cache] - Redis */
$container['redis'] = function($c) {
    // Redis extension
    try
    {
        $redis = new Redis();
        $redis->connect(
            $c->get('settings')['redis']['host'],
            $c->get('settings')['redis']['port']);
        $redis->select($c->get('settings')['redis']['db']);
        if ($c->get('settings')['redis']['auth'])
        {
            $redis->auth($c->get('settings')['redis']['auth']);
        }
    }
    catch (\Exception $e)
    {
        die('Redis error : ' . $e->getMessage());
    }

    return $redis;
};

/* }}} */

/* {{{ [Logger] - Monolog */
$container['logger'] = function($c) {
    // Monolog
    try
    {
        $logger = new \Monolog\Logger(\APP_NAME);
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor);
        $logger->pushHandler(new \Monolog\Handler\RotatingFileHandler(
            $c->get('settings')['logger']['path'],
            0,
            $c->get('settings')['logger']['level']));
    }
    catch (\Exception $e)
    {
        die('Monolog error : ' . $e->getMessage());
    }

    return $logger;
};




/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: sw=4 ts=4 fdm=marker
 * vim<600: sw=4 ts=4
 */
