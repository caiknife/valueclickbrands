<?php
/**
 * Mezimedia Tracking
 *
 * @category   Tracking
 * @package    Tracking_Constant
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Constant.php,v 1.1 2013/07/10 01:34:45 jjiang Exp $
 */

/**
 * tracking constant
 *
 * @category   Tracking
 * @package    Tracking_Constant
 */
final class Tracking_Constant
{
    /**
     * service value slot:
     * |-----------------------------------------------------------------------------------------------|
     * |  |  |  |  |  |  |  |  |  |  |  |  |  |  |  |  |  |  |  |  |  |  |  |  |  |  |  |  |  |  |  |  |
     * |31|30|29|28|27|26|25|24|23|22|21|20|19|18|17|16|15|14|13|12|11|10|09|08|07|06|05|04|03|02|01|00|
     * |               RESV.               | affiliate |  sponsor  |    mezi   |RESV.|query|RESV.|ca|rs|
     * |-----------------------------------------------------------------------------------------------|
     */

    /**
     * slot 1, query is real request by user ?
     */
    const IS_REAL_SEARCH_NO         = 0x00000000;
    const IS_REAL_SEARCH_YES        = 0x00000001;
    const IS_REAL_SEARCH            = 0x00000001;

    /**
     * slot 2, response result is cached ?
     */
    const IS_CACHED_NO              = 0x00000000;
    const IS_CACHED_YES             = 0x00000002;
    const IS_CACHED                 = 0x00000002;

    /**
     * slot 3-4, reserved.
     */

    /**
     * slot 5-6, page type
     */
    const QUERY_BY_UNKNOWN          = 0x00000000;
    const QUERY_BY_KEYWORD          = 0x00000010;
    const QUERY_BY_CATEGORY_ID      = 0x00000020;
    const QUERY_BY_PRODUCT_ID       = 0x00000030;
    const QUERY_BY                  = 0x00000030;

    /**
     * slot 7-8, reserved.
     */

    /**
     * slot 9-20 bit, service type
     */
    const SERVICE_UNKNOWN           = 0x00000000;

    /* slot 9-12 bit, mezi service type */
    const SERVICE_SMARTER           = 0x00000100;
    const SERVICE_COUPONMOUNTAIN    = 0x00000200;
    const SERVICE_MEZI              = 0x00000F00;

    /* slot 13-16 bit, sponsor service type */
    const SERVICE_OVERTURE          = 0x00001000;
    const SERVICE_GOOGLE            = 0x00002000;
    const SERVICE_ADKNOWLEDGE       = 0x00003000;
    const SERVICE_BAIDU             = 0x00004000;
    const SERVICE_MSN               = 0x00005000;
    const SERVICE_MIVA              = 0x00006000;
    const SERVICE_SPONSOR           = 0x0000F000;

    /* slot 17-20 bit, affiliate service type */
    const SERVICE_SHOPZILLA         = 0x00010000;
    const SERVICE_SHOPPING          = 0x00020000;
    const SERVICE_PRICERUNNER       = 0x00030000;
    const SERVICE_YAHOOSHOPPING     = 0x00040000;
    const SERVICE_AMAZON            = 0x00050000;
    const SERVICE_TAOKE             = 0x00060000;
    const SERVICE_DILINGLING        = 0x00070000;
    const SERVICE_BECOME            = 0x00080000;
    const SERVICE_LINKPRICE         = 0x00090000;
    const SERVICE_AFFILIATE         = 0x000F0000;

    const SERVICE_TYPE              = 0x000FFF00;

    /**
     * slot 21-32, reserved.
     */


    /**
     * traffic type
     */
    const TRAFFIC_NORMAL            =  0;
    const TRAFFIC_BAD_USERAGENT     = -1;
    const TRAFFIC_BAD_IP            = -2;
    const TRAFFIC_EMPTY_USERAGENT   = -3;
    const TRAFFIC_PRIVATE_IP        = -4;
    const TRAFFIC_GOOD_USERAGENT    = -11;
    const TRAFFIC_GOOD_IP           = -12;

    /** sponsor type */
    const SPONSOR_UNKNOWN           = 'UNKNOWN';
    const SPONSOR_GOOGLE            = 'GOOGLE';
    const SPONSOR_OVERTURE          = 'YAHOO';
    const SPONSOR_BAIDU             = 'BAIDU';
    const SPONSOR_BAIDU_PROMOTION   = 'BAIDU-PROMOTION';
    const SPONSOR_BAIDU_ACCURATE    = 'BAIDU-ACCURATE';
    const SPONSOR_BAIDU_INTELLIGENT = 'BAIDU-INTELLIGENT';
    const SPONSOR_ADKNOWLEDGE       = 'ADKNOWLEDGE';
}