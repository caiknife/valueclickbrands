<?php
/**
 * Mezimedia Tracking
 *
 * @category   Tracking
 * @package    Tracking_Constant
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Constant.php,v 1.1 2013/06/27 07:54:18 jjiang Exp $
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
     * |               RESV.               | affiliate |  sponsor  |    mezi   |query type |RESV.|ca|rs|
     * |-----------------------------------------------------------------------------------------------|
     */

    /**
     * slot 1, query is real request by user ?
     */
    const IS_REAL_SEARCH_NO         = 'NO';
    const IS_REAL_SEARCH_YES        = 'YES';

    /**
     * slot 2, response result is cached ?
     */
    const IS_CACHED_NO              = 'NO';
    const IS_CACHED_YES             = 'YES';

    /**
     * slot 3-4, reserved.
     */

    /**
     * slot 5-8, page type
     */
    const QUERY_BY_UNKNOWN          = 0;
    const QUERY_BY_KEYWORD          = 1;
    const QUERY_BY_CATEGORY_ID      = 2;
    const QUERY_BY_PRODUCT_ID       = 3;
    const QUERY_BY_MERCHANT_ID      = 4;
    const QUERY_BY_CPC				= 6;
    const QUERY_BY_RELATED_KEYWORD  = 7;

    /**
     * slot 9-20 bit, service type
     */
    const SERVICE_UNKNOWN           = 0x00000000;

    /* slot 13-16 bit, sponsor service type */
    const SERVICE_OVERTURE          = 1;
    const SERVICE_GOOGLE            = 2;
    const SERVICE_ADKNOWLEDGE       = 3;
    const SERVICE_BAIDU             = 4;
    const SERVICE_MSN               = 5;
    const SERVICE_MIVA              = 6;
    //const SERVICE_SPONSOR           = 0x0000F000;

    /* slot 17-20 bit, affiliate service type */
    const SERVICE_SHOPZILLA         = 100;
    const SERVICE_SHOPPING          = 101;
    const SERVICE_PRICERUNNER       = 102;
    const SERVICE_YAHOOSHOPPING     = 103;
    const SERVICE_AMAZON            = 104;
    const SERVICE_TAOKE             = 105;
    const SERVICE_DILINGLING        = 106;
    const SERVICE_BECOME            = 107;
    const SERVICE_LINKPRICE         = 108;
    const SERVICE_BANKRATE          = 109;    // bank rate api
    const SERVICE_YAHOOANSWER       = 110;
    const SERVICE_SMARTER           = 111;
    const SERVICE_COUPON            = 112;
    const SERVICE_GSTS              = 113;
    const SERVICE_YSTS              = 114;
    const SERVICE_XLISTING          = 115;
    const SERVICE_YAHOOAUCTION      = 116;
    const SERVICE_VCSHOPPING        = 117;
    const SERVICE_CHANNEL           = 118;
    const SERVICE_KEYWORD           = 119;
    //const SERVICE_TYPE              = 0x000FFF00;

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

    /**
     * sponsor type
     */
    const SPONSOR_UNKNOWN           = 'UNKNOWN';
    const SPONSOR_GOOGLE            = 'GOOGLE';
    const SPONSOR_GOOGLE_ASYNC      = 'GOOGLE-ASYNC';
    const SPONSOR_OVERTURE          = 'YAHOO';
    const SPONSOR_BAIDU             = 'BAIDU';
    const SPONSOR_BAIDU_PROMOTION   = 'BAIDU-PROMOTION';
    const SPONSOR_BAIDU_ACCURATE    = 'BAIDU-ACCURATE';
    const SPONSOR_BAIDU_INTELLIGENT = 'BAIDU-INTELLIGENT';
    const SPONSOR_ADKNOWLEDGE       = 'ADKNOWLEDGE';
    const SPONSOR_XLISTING          = 'XLISTING';
    const SPONSOR_XLISTING_ASYNC    = 'XLISTING-ASYNC';

    const PRODUCT_ONLINE            = 200;
    const PRODUCT_FORBIDDEN         = 300;
    const PRODUCT_OFFLINE           = 400;
}