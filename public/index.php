<?php
/* Copyright (C) Traceclouds Systems, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Dr.NP <np@bsgroup.org>
 */

/**
 * @package lordy.api
 * @file index.php
 * @author Dr.NP <np@bsgroup.org>
 * @date 01/14/2017
 * @version 0.0.1
 *
 * Slim portal index
 */

if (PHP_SAPI == 'cli-server')
{
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file))
    {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/constants.inc.php';
require __DIR__ . '/../app/utils.inc.php';

// Framework initialize
$config = require __DIR__ . '/../app/config.inc.php';
$app = new \Slim\App($config);
$container = $app->getContainer();


// Application runtime
require __DIR__ . '/../app/dependencies.inc.php';
require __DIR__ . '/../app/middlewares.inc.php';
require __DIR__ . '/../app/handlers.inc.php';
require __DIR__ . '/../app/routes.inc.php';

// Startup
$app->run();

return true;

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: sw=4 ts=4 fdm=marker
 * vim<600: sw=4 ts=4
 */
