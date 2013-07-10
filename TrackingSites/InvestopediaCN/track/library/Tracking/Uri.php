<?php

/**
 * Build redirect Uri with given $params
 *
 * @category   Tracking
 * @package    Tracking_Uri
 */
class Tracking_Uri
{
    /* Uri Parameters */
    const ADVERTISER_HOST       = 'ah';     // advertiser host
    const ADVERTISEMENT_GROUP   = 'ag';     // advertisement group
    const ASYNC_TYPE            = 'at';     // asynchronous request type
    const ASYNC_TYPE_GA         = 'ag';     // asynchronous request type
    const BID_POSITION          = 'bp';     // bid position
    const BUILD_TYPE            = 'bt';     // redirect type
    const CAMPAIGN_TYPE         = 'ct';     // campaign type
    const CATEGORY_ID           = 'ca';     // category id
    const CATEGORY_NAME         = 'cn';     // category name
    const CHANNEL_ID            = 'ch';     // channel id
    const CHANNEL_TAG           = 'ct';     // channel tag
    const CHECK_CODE            = 'cc';     // check code
    const CHECK_METHOD          = 'cm';     // check method
    const CLICK_AREA            = 'cp';     // click position
    const COUPON_BEACON         = 'cb';     // the beacon of coupon
    const COUPON_ID             = 'ci';     // coupon id
    const CUSTOMER_ID           = 'ci';     // customer id
    const CPC                   = 'cc';     // cpc
    const CURRENT_REQUEST       = 'cr';     // current request (curRandStr)
    const DESTINED_SITE         = 'ds';     // destined site
    const DESTINED_URL          = 'du';     // destined URL
    const DISPLAY_POSITION      = 'dp';     // display position
    const EXPIRED_TIME          = 'et';     // expired time
    const HASH_VALUE            = 'hv';     // hash value
    const IS_MATCHED            = 'im';     // channel tag is matched?
    const KEYWORD               = 'kw';     // keyword
    const KEYWORD_CLICKED       = 'kc';     // module click keyeord
    const KEYWORD_SUPPLEMENTARY = 'ks';     // 2nd keyword
    const MODULE_NAME           = 'mn';     // module name
    const MERCHANT_COUNT        = 'mc';     // merchant count
    const MERCHANT_ID           = 'mi';     // merchant id
    const MERCHANT_NAME         = 'mn';     // merchant name
    const OFFER_ID              = 'oi';     // offer id
    const OUTPUT_TYPE           = 'ot';     // output type ony for panda
    const OUTGOING_TYPE         = 'ot';     // outgoing type
    const PAYMENT_TYPE          = 'pt';     // payment type: CPA/CPC/CPM
    const PREVIOUS_REQUEST      = 'pr';     // previous request id (randstr)
    const PRICE_RANK            = 'pr';     // price rank
    const PRODUCT_ID            = 'pi';     // product id
    const PRODUCT_NAME          = 'pn';     // product name
    const RANK                  = 'ra';     // rank
    const RATE_RANK             = 'rr';     // rate rank
    const RATING_ID             = 'ri';     // rating id
    const REMARK                = 'rm';     // only for smcn
    const REQUEST_COUNTRY       = 'rc';     // country of requesting channel tag
    const REVIEW_ID             = 'rv';     // review id
    const SORT_BY               = 'sb';     // sort by
    const SESSION_ID            = 'se';     // session id
    const SITE_ID               = 'si';     // site id
    const SPONSOR_TYPE          = 'st';     // sponsor type
    const TICKET_ID             = 'ti';     // ticket id
    const TRACKING_PARAM        = 'tp';     // tracking params, reserved
    const TRAFFIC_TYPE          = 'tt';     // traffic type
    const UNIT_COST             = 'uc';     // advertisement unit cost
    const VISITOR_IP            = 'vi';     // vistor's ip

    /* Signature separator */
    const SIGNATURE_SEPARATOR   = "\t";     //

    /**
     * The need for encoding parameters
     *
     * @var array
     */
    public static $encodings = array(
        self::KEYWORD,      self::DESTINED_SITE,    self::DESTINED_URL,
        self::CHANNEL_TAG,  self::BUILD_TYPE,       self::KEYWORD_CLICKED,
        self::VISITOR_IP,   self::ADVERTISER_HOST,  self::MERCHANT_NAME,
        self::CATEGORY_NAME,self::PRODUCT_NAME,     self::KEYWORD_SUPPLEMENTARY,
        self::COUPON_BEACON,self::SPONSOR_TYPE,     self::ASYNC_TYPE,
        self::ASYNC_TYPE_GA,
    );

    /**
     * The need for obfuscating parameters
     *
     * @var array
     */
    public static $obfuscatings = array(
        self::CPC, self::UNIT_COST,
    );

    /**
     * encode a string
     * safe for url
     *
     * @param string $string
     * @return string
     */
    public static function encode($string)
    {
        return base64_encode($string);
    }

    /**
     * decode a string
     * safe for url
     *
     * @param string $string
     * @return string
     */
    public static function decode($string)
    {
        return base64_decode($string);
    }

    /**
     * obfuscate numeric
     * 0123456789
     * ghijklmnop   g:103
     * qrstuvwxyz   q:113
     * @param numeric $numbers
     * @return string
     */
    public static function obfuscate($numbers)
      {
        if (!is_numeric($numbers)) return '';

        $result = '';
        foreach (str_split($numbers) as $number) {
            /* 103=ord('g'), 113=ord('q') */
            $key = rand(0, 1) ? 113 : 103;
            $result .= ('.' == $number) ? '-' : chr($number + $key) ;
        }
        return $result;
    }

    /**
     * unobfuscate numeric
     * 0123456789
     * ghijklmnop
     * qrstuvwxyz
     * @param string $string
     * @return numeric
     */
    public static function unobfuscate($string)
    {
        if (!is_string($string)) return 0;

        $result = '';
        foreach (str_split($string) as $number) {
            /* 103=ord('g'), 113=ord('q') */
            $key = ($number >= 'q') ? 113 : 103;
            $result .= ('-' == $number) ? '.' : (ord($number) - $key) ;
        }

        return ($result >= 0) ? $result : 0 ;
    }

    /**
     * sign on the parameters with key
     *
     * @param array $params
     * @param string $key
     * @return string
     */
    private static function _sign($params, $key)
    {
        $params = array_change_key_case($params, CASE_LOWER);
        unset($params[self::HASH_VALUE]);
        ksort($params);

        return md5(implode(self::SIGNATURE_SEPARATOR, $params) . $key);
    }

    /**
     * verify the parameters with key
     *
     * @param array $params
     * @param string $key
     * @return boolean
     */
    private static function _verify($params, $key)
    {
        $params = array_change_key_case($params, CASE_LOWER);

        if (!isset($params[self::HASH_VALUE])) {
            return FALSE;
        } else {
        	$hashValue = $params[self::HASH_VALUE];
        	unset($params[self::HASH_VALUE]);
        }

        ksort($params);

        return md5(implode(self::SIGNATURE_SEPARATOR, $params) . $key)==$hashValue;
    }

    /**
     * build the outgoing url
     *
     * @param array $params
     * @param string $key
     * @return string
     */
    public static function build($params, $key=NULL)
    {
        if (empty($params) || !is_array($params)) {
            return (string) Mezi_Config::getInstance()->tracking->script->error;
        }

        $result = array();
        foreach ($params as $param => $value) {
            /* skip empty $param or click_area = -1 */

            /* encode or obfuscate some parameter's value */
            if (in_array($param, self::$encodings)) {
                $result[$param] = self::encode($value);
            } elseif (in_array($param, self::$obfuscatings)) {
                $result[$param] = self::obfuscate($value);
            } else {
                $result[$param] = $value;
            }
        }

        /* make sure the BUILD_TYPE is top and the DESTINED_URL is last */
        if (isset($result[self::ADVERTISER_HOST])) {
            $advertiserHost = $result[self::ADVERTISER_HOST];
            unset($result[self::ADVERTISER_HOST]);
            $result[self::ADVERTISER_HOST] = $advertiserHost;
        }
        if (isset($result[self::DESTINED_URL])) {
            $destinedUrl = $result[self::DESTINED_URL];
            unset($result[self::DESTINED_URL]);
            $result[self::DESTINED_URL] = $destinedUrl;
        }

        if (!empty($key)) {
            $result[self::HASH_VALUE] = self::_sign($params,$key);
        }

        if (!empty($params[self::BUILD_TYPE])) {
            $script = Mezi_Config::getInstance()->tracking->script->redir;
        } else if (!empty($params[self::ASYNC_TYPE_GA])) {
            $script = Mezi_Config::getInstance()->tracking->script->ga;
        } else if (!empty($params[self::ASYNC_TYPE])) {
            $script = Mezi_Config::getInstance()->tracking->script->async;
        } else {
            $script = '';
        }

        return $script. '?' . http_build_query($result);
    }

    /**
     * extract query parameters from request uri
     *
     * @param string $requestUri
     * @param string $key
     * @return array | NULL
     */
    public static function extract($requestUri='', $key=NULL)
    {
        $result = NULL; $params = NULL;

        if (is_string($requestUri) && ($uriQuery = parse_url($requestUri, PHP_URL_QUERY))) {
            parse_str($uriQuery, $params);
            foreach ($params as $param => $value) {
                /* decode or unobfuscate some parameter's value */
                if (in_array($param, self::$encodings)) {
                    $result[$param] = self::decode($value);
                } elseif (in_array($param, self::$obfuscatings)) {
                    $result[$param] = self::unobfuscate($value);
                } else {
                    $result[$param] = $value;
                }
            }
        }

        if (!empty($key) && !self::_verify($result, $key)) {
        	throw new Tracking_Exception("WARNING! the URL was modified!");
        }

        return $result;
    }

}