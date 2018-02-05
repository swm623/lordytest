<?php
/* Copyright (C) Traceclouds Systems, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Dr.NP <np@bsgroup.org>
 */

/**
 * @package lordy.api
 * @file constants.inc.php
 * @author Dr.NP <np@bsgroup.org>
 * @date 01/07/2018
 * @version 0.0.1
 *
 * Runtime constants
 */

/* {{{ [LordyAuth] - Authenticate status */
class LordyAuth
{
    const AUTH_OK = 0;
    const AUTH_NEED = 1;
    const AUTH_INVALID = 2;
    const AUTH_PERMISSION = 3;
    const AUTH_NO_NEED = 255;
}

/* }}} */

/* {{{ [LordyResponse] */
class LordyResponse
{
    const RESULT_OK = 0;
    const RESULT_FATAL = 32767;
    const RESULT_UNKNOWN = 65535;
}

/* }}} */

/* {{{ [LordyUser] */
class LordyUser
{
    const USER_NOT_LOGGED = 0;
    const USER_ADMIN = 1;
}

/* }}} */

/* {{{ [LordyError] - Error codes */
class LordyError
{
    const OK = 0;
    const ROUTE_NOT_FOUND =1007;
    const METHOD_NOT_ALLOWED = 1008;
    const INVALID_INPUT = 1009;
    const RESOURCE_CREATE_FAILED = 1010;
    const RESOURCE_MODIFY_FAILED = 1011;
    const CONTENT_NOT_FOUND = 12001;
    const GID_DECRPT_FAIL = 23001;
}

/* }}} */

/* {{{ [LordyLinks] */
class LordyLinks
{
    const ROUTE_LIST = '/routes';
}

/* }}} */

/* {{{ [LordyPagination] */
class LordyPagination
{
    const TAG_PAGE = 'p';
    const TAG_PERPAGE = 'n';
    const TAG_START = 'start';
    const TAG_END = 'end';
    const TAG_ORDER = 'order';
    const DEFAULT_PERPAGE = 10;
}

/* }}} */

/* {{{ [LordyInclude] */
class LordyInclude
{
    const TAG_INCLUDE = 'include';
}

/* }}} */

/* {{{ [LordySearch] */
class LordySearch
{
    const TAG_SEARCH = 'search';
}

/* }}} */

/* {{{ [LordyType] */
class LordyType
{
    const TAG_TYPE = 'type';
}

/* }}} */

class LordyEventType
{
    const LAUNCH="launch";
    const SHOW="show";
    const HIDE="hide";
    const PAGE_SHOW="pageshow";
    const PAGE_HIDE="pagehide";
    const SHARE="share";
}

class LordyShareType {
    const GROUP=1;
    const USER=2;
}

class LordyEsRawIndex {
    const NAME="event_raw_index";
    const type="raw";
}

class LordyEsNodeIndex {
    const NAME="event_node_index";
    const type="node";
}
/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: sw=4 ts=4 fdm=marker
 * vim<600: sw=4 ts=4
 */
