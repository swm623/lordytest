<?php
/* Copyright (C) Traceclouds Systems, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Dr.NP <np@bsgroup.org>
 */

/**
 * @package lordy.api
 * @file middlewares.inc.php
 * @author Dr.NP <np@bsgroup.org>
 * @date 01/06/2018
 * @version 0.0.1
 *
 * Slim middlewares
 */

if (!\defined('IN_LORDY') || !$app)
{
    die('Inject denied');
}

/* {{{ [mw_Render] - Response envelope & renderer */
$mw_Render = function($request, $response, $next) use ($container)
{
    $logger = $container->get('logger');
    // === NEXT ===
    $response = $next($request, $response);
    // === NEXT ===

    if ($container->get('settings')['runtime']['enable_envelope'])
    {
        $container['result'] = [
            'code' => $container['result_code'],
            'message' => $container['result_message'],
            'links' => $container['result_links'],
            'timestamp' => \time(),
            'data' => $container['result']
        ];
    }

    // Render output
    $result = $container->get('result');
    $body = '';
    if (!$result)
    {
        $result = [];
    }

    $correct_ct = $container->get('result_content_type');
    switch ($correct_ct)
    {
        case 'application/json' :

            $body = \json_encode($result, \JSON_PRETTY_PRINT);
            $logger->debug("create result;".$body);
            break;
        case 'application/x-msgpack' :
        case 'application/x-php' :
        default :
            // We does not support MsgPack at this time
            $body = \serialize($result);
            break;
    }

    $response = $response->withHeader('Content-type', $correct_ct)->Write($body);

    return $response;
};

/* }}} */

/* {{{ [mw_Http] - Http response middleware */
$mw_Http = function($request, $response, $next) use ($container) {
    // === NEXT ===
    $response = $next($request, $response);
    // === NEXT ===

    if ($container['http_code'] != 200)
    {
        $response = $response->withStatus($container['http_code']);
    }

    return $response;
};

/* }}} */



/* {{{ [mw_ContentType] - Content type parse and build middleware */
$mw_ContentType = function($request, $response, $next) use ($container)
{
    $named = [];
    $accepted = $container->get('settings')['accepted_content_type'];
    $request_ct = \filter_input(\INPUT_GET, '_content_type');
    if ($request_ct)
    {
        $named[] = $request_ct;
    }
    else
    {
        $ct = $request->getContentType();
        if (\is_array($ct) && isset($ct[0]) && \is_string($ct[0]))
        {
            $cts = \explode(',', $ct[0]);
            foreach ($cts as $ct_entry)
            {
                $pos = \strpos($ct_entry, ';');
                if ($pos !== false)
                {
                    $ct_entry = \substr($ct_entry, 0, \strpos($ct_entry, ';'));
                }

                $ct_entry = \trim(\strtolower($ct_entry));
                if ($ct_entry != '*/*')
                {
                    $named[] = $ct_entry;
                }
            }
        }
    }

    $allowed = \array_intersect($accepted, $named);
    $correct_ct = \array_shift($allowed);
    if (!$correct_ct)
    {
        $correct_ct = \DEFAULT_CONTENT_TYPE;
    }

    $container['result_content_type'] = $correct_ct;

    // === NEXT ===
    $response = $next($request, $response);
    // === NEXT ===

    return $response;
};

/* }}} */



$app->add($mw_ContentType);
$app->add($mw_Http);
$app->add($mw_Render);

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: sw=4 ts=4 fdm=marker
 * vim<600: sw=4 ts=4
 */
