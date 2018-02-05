<?php
/* Copyright (C) Traceclouds Systems, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Dr.NP <np@bsgroup.org>
 */

/**
 * @package pixie.api
 * @file utils.inc.php
 * @author Dr.NP <np@bsgroup.org>
 * @date 12/29/2017
 * @version 0.0.1
 *
 * Code utils functions
 */

class utils
{
/* {{{ [Utils::V] */
    static public function V($a, $key, $default = null)
    {
        if (!\is_array($a))
        {
            return $default;
        }

        if (isset($a[$key]))
        {
            return $a[$key];
        }

        return $default;
    }

/* }}} */

/* {{{ [Utils::E] - Data envelope */
    static public function E($data)
    {
        return;
    }

/* }}} */

/* {{{ [Utils::P] - Pagination */
    static public function P()
    {
        $_start = 0;
        $_n = \LordyPagination::DEFAULT_PERPAGE;

        $current = \intval(\filter_input(\INPUT_GET, \LordyPagination::TAG_PAGE));
        $perpage = \intval(\filter_input(\INPUT_GET, \LordyPagination::TAG_PERPAGE));
        $start = \intval(\filter_input(\INPUT_GET, \LordyPagination::TAG_START));
        $end = \intval(\filter_input(\INPUT_GET, \LordyPagination::TAG_END));
        $order = \strtolower(\filter_input(\INPUT_GET, \LordyPagination::TAG_ORDER));
        if ($current > 0)
        {
            if ($perpage <= 0)
            {
                $perpage = \LordyPagination::DEFAULT_PERPAGE;
            }

            $start = ($current - 1) * $perpage;
        }

        if ($end > $start)
        {
            $perpage = $end - $start;
        }

        if ($perpage <= 0)
        {
            $perpage = \LordyPagination::DEFAULT_PERPAGE;
        }

        return ['start' => $start, 'n' => $perpage, 'order' => ($order) ? $order : 'asc'];
    }

/* }}} */

/* {{{ [Utils::I] - Include query param  */
    static public function I()
    {
        $include = \filter_input(\INPUT_GET, \PixieInclude::TAG_INCLUDE);
        if (!$include)
        {
            return null;
        }

        $ids = \explode(',', $include);

        return (\is_array($ids) && count($ids) > 0) ? $ids : null;
    }

/* }}} */

/* {{{ [Utils::S] - Search query */
    static public function S()
    {
        $search = \filter_input(\INPUT_GET, \PixieSearch::TAG_SEARCH);

        return $search;
    }

/* }}} */

/* {{{ [Utils::T] - Type filter */
    static public function T()
    {
        $type = \filter_input(\INPUT_GET, \PixieType::TAG_TYPE);

        return $type;
    }

/* }}} */

/* {{{ [Utils::F] - Array key filter */
    static public function F(array $input, array $filter)
    {
        return \array_intersect_key($input, $filter);
    }

/* }}} */

/* {{{ [Utils::C] - Permission check */
    static public function C($permissions, $check)
    {
        $permissions = \intval($permissions);
        $check = \intval($check);
        if ($check < 0 || $check > 63)
        {
            $check = 0;
        }

        $mask = 1 << $check;

        return ($permissions & $mask) ? true : false;
    }

/* }}} */

/* {{{ [Utils::IP] - Get client IP */
    static public function IP($bare = false)
    {
        if ($bare)
        {
            return \filter_input(\INPUT_SERVER, 'REMOTE_ADDR');
        }

        $keys = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];

        $ip_addr = '0.0.0.0';
        foreach ($keys as $key)
        {
            $value = \filter_input(\INPUT_SERVER, $key);
            if ($value)
            {
                $ips = \explode(',', $value);
                foreach ($ips as $ip_addr)
                {
                    $ip_addr = \trim($ip_addr);
                    if (\filter_var($ip_addr,
                                    \FILTER_VALIDATE_IP,
                                    [\FILTER_FLAG_NO_PRIV_RANGE, \FILTER_FLAG_NO_RES_RANGE]))
                    {
                        break 2;
                    }
                }
            }
        }

        return $ip_addr;
    }

/* }}} */
}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: sw=4 ts=4 fdm=marker
 * vim<600: sw=4 ts=4
 */
