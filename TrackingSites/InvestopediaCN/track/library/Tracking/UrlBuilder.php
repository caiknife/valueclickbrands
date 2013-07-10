<?php
/**
 * Tracking_UrlBuilder
 *
 * Build Redirect URL with different type
 *
 * @copyright  2007 MeziMedia
 * @author     ken_zhang <kenchou77@gmail.com>
 * @version    CVS: $Id: UrlBuilder.php,v 1.1 2013/07/10 01:34:45 jjiang Exp $
*/

/**
 * Build redirect URL with given array
 *
 * example:
 * 1. use constructor
 *   params = array(....);
 *   $redirUrl = new Tracking_UrlBuilder($params);  //$redirUrl is object
 *   echo $redirUrl;                //echo call magic method __toString()
 *   $url = (string)$redirUrl;      //under php 5.1 Magic function __toString() doesn't work as expected
 *                                  //need php 5.2+
 *   $url = $redirUrl->toString();  //so we have to explicit call object to string
 *
 * 2. use static method
 *   $params = array(...);
 *   $redirUrl = Tracking_UrlBuilder::build($params);   //$redirUrl is string
 *   echo $redirUrl;
 *   $url = (string)$redirUrl;
 *
 * @copyright  2007 MeziMedia
 * @package    Tracking
 */
class Tracking_UrlBuilder
{
    const ASYNC_SCRIPT       = 'async.php';
    const ASYNC_SCRIPT_ERROR = 'response error';
    const PARAM_BUILD_TYPE   = 'buildtype';

    const ASYNC_TYPE_VIDEO      = 100;
    const ASYNC_TYPE_TENTOE     = 101;
    const ASYNC_TYPE_BANNER     = 102;
    const ASYNC_TYPE_GUIDESTER  = 105;

    /**
     * @desc value click banner
     *
     */
    const ASYNC_TYPE_VC_BANNER = 103;

    const REDIRECT_SCRIPT       = 'redir.php';
    const REDIRECT_SCRIPT_ERROR = '/errorpage.php?type=LostRedirectArray';

    const REDIRECT_TYPE_AFFILIATE       = 1;
    const REDIRECT_TYPE_SPONSOR         = 2;
    const REDITECT_TYPE_PRODUCT_REVIEW  = 3;
    const REDIRECT_TYPE_MERCHANT_REVIEW = 4;

    const URL_SEPARATOR_AMP = '&';
    const URL_SEPARATOR_AMP_HTML_ENTITY = '&amp;';

    const URL_PARAM_BUILD_TYPE       = 'bt';  // redirect type
    const URL_PARAM_CHANNEL_ID       = 'ch';   // channel id
    const URL_PARAM_CATEGORY_ID      = 'cd';   // category id
    const URL_PARAM_PRODUCT_ID       = 'pi';   // product id
    const URL_PARAM_MERCHANT_ID      = 'mi';   // merchant id
    const URL_PARAM_CLICK_AREA       = 'ca';   // click area
    const URL_PARAM_DESTINED_SITE    = 'ds';   // destined site
    const URL_PARAM_DESTINED_URL     = 'du';   // destined URL
    const URL_PARAM_RANK             = 'ra';   // rank
    const URL_PARAM_CHANNEL_TAG      = 'ct';   // channel tag
    const URL_PARAM_KEYWORD          = 'kw';   // keyword
    const URL_PARAM_RATING_ID        = 'ri';   // rating id
    const URL_PARAM_REVIEW_ID        = 'ri';  // review id
    const URL_PARAM_OFFER_ID         = 'oi';  // category id
    const URL_PARAM_MERCHANT_COUNT   = 'mc';  // merchant count
    const URL_PARAM_DISPLAY_POSITION = 'dp';  // display position
    const URL_PARAM_BID_POSITION     = 'bp';  // bid position
    const URL_PARAM_PRICE_RANK       = 'pr';  // price rank
    const URL_PARAM_RATE_RANK        = 'rr';  // rate rank
    const URL_PARAM_SORT_BY          = 'sb';  // sort by
    const URL_PARAM_CPC              = 'cc';  // cpc
    const URL_PARAM_SESSION_ID       = 'se';  // session id
    const URL_PARAM_SITE_ID          = 'si';  // site id
    const URL_PARAM_CURRENT_REQUEST  = 'cr';  // current request id
    const URL_PARAM_IS_MATCHED       = 'im';  // is channel tag matched ?
    const URL_PARAM_EXPIRED_TIME     = 'et';  // expired time of channel tag
    const URL_PARAM_REQUEST_COUNTRY  = 'rc';  // request country of channel tag
    const URL_PARAM_BEACON_ID        = 'bi';  // the beacon of outging/something
    const URL_PARAM_ADVERTISER_HOST  = 'ah';  // sponsor display url
    const URL_PARAM_SPONSOR_TYPE     = 'st';  // sponsor type
    const URL_PARAM_TRACKING_PARAM   = 'tp';  //tracking params

    private $_paramMap = array(
        //long => shot
        'buildtype'        => self::URL_PARAM_BUILD_TYPE,   // redirect type
        'chid'             => self::URL_PARAM_CHANNEL_ID,         // channel id
        'cateid'           => self::URL_PARAM_CATEGORY_ID,        // category id
        'prodid'           => self::URL_PARAM_PRODUCT_ID,         // product id
        'merid'            => self::URL_PARAM_MERCHANT_ID,        // merchant id
        'clickarea'        => self::URL_PARAM_CLICK_AREA,         // click area
        'destsite'         => self::URL_PARAM_DESTINED_SITE,      // destined site
        'desturl'          => self::URL_PARAM_DESTINED_URL,       // destined URL
        'rank'             => self::URL_PARAM_RANK,               // rank
        'channeltag'       => self::URL_PARAM_CHANNEL_TAG,        // channel tag
        'keyword'          => self::URL_PARAM_KEYWORD,            // keyword
        'ratingid'         => self::URL_PARAM_RATING_ID,          // rating id
        'reviewid'         => self::URL_PARAM_REVIEW_ID,          // review id
        'offerid'          => self::URL_PARAM_OFFER_ID,           // category id
        'mercount'         => self::URL_PARAM_MERCHANT_COUNT,     // merchant count
        'displaypos'       => self::URL_PARAM_DISPLAY_POSITION,   // display position
        'bidpos'           => self::URL_PARAM_BID_POSITION,       // bid position
        'pricerank'        => self::URL_PARAM_PRICE_RANK,         // price rank
        'raterank'         => self::URL_PARAM_RATE_RANK,          // rate rank
        'sortby'           => self::URL_PARAM_SORT_BY,            // sort by
        'cpc'              => self::URL_PARAM_CPC,                // cpc
        'advertiserhost'   => self::URL_PARAM_ADVERTISER_HOST,    // advertiserhost
        'sponsortype'      => self::URL_PARAM_SPONSOR_TYPE,      // advertiserhost
        'trackingparams'   => self::URL_PARAM_TRACKING_PARAM,     //tracking params
    );

    private $_url;

    /**
     * @param array  $param
     * @param string $separator
     *
     */
    public function __construct($params, $separator = self::URL_SEPARATOR_AMP)
    {
        //$this->_url = self::build($params, $separator);
        $this->_url = $this->buildUrl($params, $separator);
    }


    public function buildUrl($params, $separator = self::URL_SEPARATOR_AMP)
    {

        $shortParams = array();
        foreach ($params as $long => $value) {
            $short = isset($this->_paramMap[$long]) ? $this->_paramMap[$long] : $long;
            if (empty($short)) continue;

            if ($short == self::URL_PARAM_KEYWORD
                || $short == self::URL_PARAM_DESTINED_SITE
                || $short == self::URL_PARAM_DESTINED_URL
                || $short == self::URL_PARAM_CHANNEL_TAG
                || $short == self::URL_PARAM_SORT_BY
                || $short == self::URL_PARAM_ADVERTISER_HOST
                || $short == self::URL_PARAM_SPONSOR_TYPE
            ) {
                $shortParams[] = "$short=" . self::encode($value);
            } elseif ($short == self::URL_PARAM_CLICK_AREA && -1 == $value) {
                //skip click_area = -1
                continue;
            } elseif($short == self::URL_PARAM_CPC){
                $shortParams[] = "$short=" . self::numeric_encode($value);
            }else {
                $shortParams[] = "$short=$value";
            }
        }

        $query = '?' . implode($separator, $shortParams);

        //$str = http_build_str($shortParams, NULL, $separator = self::URL_SEPARATOR_AMP);


        $buildType = self::getValue($params, self::PARAM_BUILD_TYPE);
        if($buildType >= 100) {  //asynchornize
            $script = self::ASYNC_SCRIPT;
        }else{
            $script = self::REDIRECT_SCRIPT;
        }

        $url = $script . $query;

        return $url;
    }

    /**
     * build url
     * build redir and async URL
     *
     * @param array $params
     * params type:
     * =============================================
     * Affilate rdt=1&ch=&cd=&pi=&mi=&ca=&ds=&du=
     *      array(
     *          'rdtype'    => redirect type
     *          'chid'      => channel id
     *          'cateid'    => category id
     *          'prodid'    => product id
     *          'merid'     => merchant id
     *          'clickarea' => click area
     *          'destsite'  => destined site  (shopZilla, ebay, affiliate)
     *          'desturl'   => destined URL
     *      );
     * description:
     *          'rdt'  : redirect type
     *          'ch'   : channel id
     *          'cd'   : category id
     *          'pi'   : product id
     *          'mi'   : merchant id
     *          'ca'   : click area
     *          'ds'   : destined site
     *          'du'   : destined URL
     * =============================================
     * Sponsor: rdt=2&ch=&ra=&kw=&ct=&ur=
     *      array(
     *          'rdtype'     => redirect type
     *          'chid'       => channel id
     *          'rank'       => rank
     *          'channeltag' => 'channel tag',
     *          'keyword'    => keyword
     *          'desturl'    => destined URL
     *      );
     * description:
     *          'rdt'    => redirect type
     *          'ch'     => channel id
     *          'ra'     => rank
     *          'ct'     => channel tag
     *          'kw'     => keyword
     *          'du'     => destined URL
     * =============================================
     * Product View: rdt=3&ch=&pi=&ri=&du=
     *      array(
     *          'rdtype'   => redirect type
     *          'chid'     => channel id
     *          'prodid'   => product id
     *          'ratingid' => rating id
     *          'desturl'  => destined URL
     *          'rank'     => rank
     *      );
     * description:
     *          'rdt'    => redirect type
     *          'ch'     => channel id
     *          'pi'     => product id
     *          'ri'     => rating id
     *          'du'     => destined URL
     *          'ra'     => rank
     * =============================================
     * Merchant View: rdt=4&ch=&mi=&ri=&ur=
     *      array(
     *          'rdtype'   => redirect type
     *          'merid'    => merchant id
     *          'reviewid'    => review id
     *          'desturl'    => destined URL
     *          'rank'     => rank
     *      );
     * description:
     *          'rdt'   => redirect type
     *          'ch'    => channel id
     *          'mi'    => merchant id
     *          'ri'    => review id
     *          'du'    => destined URL
     *          'ra'     => rank
     * =============================================
     * Smarter:  rdt=0&ch=&oi=&mc=&dp=&bp=&pr=&rr=&sb=&ca=
     *      array(
     *          'rdtype'     => redirect type
     *          'chid'       => channel id
     *          'offerid'    => offer id
     *          'mercount'   => merchant count
     *          'displaypos' => display position
     *          'bidpos'     => bid position
     *          'pricerank'  => price rank
     *          'raterank'   => rate rank
     *          'clickarea'  => click area
     *          'sortby'     => sort by
     *      );
     * description:
     *          'rdt'   => redirect type
     *          'ch'    => channel id
     *          'oi'    => offer id
     *          'mc'    => merchant count
     *          'dp'    => display position
     *          'bp'    => bid position
     *          'pr'    => price rank
     *          'rr'    => rate rank
     *          'ca'    => click area
     *          'sb'    => sort by
     * =============================================
     * @param string $separator  '&' for redir, and '&amp;' for html hyperlink follow W3C standard
     *
     * @param array $params
     * @return string (url)
     */
	public static function build($params, $separator = self::URL_SEPARATOR_AMP)
	{
        if(empty($params) || !is_array($params)) {
            return self::REDIRECT_SCRIPT_ERROR;
        }

        $urlBuilder = new self($params, $separator);

        return $urlBuilder->toString();
	}

	/**
	 * test key in array and return default value if not exist key
	 * @return mixed
	 */
	public static function getValue( $haystack, $key, $defaultValue = NULL)
	{
	    return isset($haystack[$key]) ? $haystack[$key] : $defaultValue;
	}

    /**
     * encode a string
     * safe for url
     *
     * @param string $string
     * @return string
     */
    public static function encode($string)
    {
        return urlencode(base64_encode($string));
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
     * Desc numeric encode for hidden cpc value
     * 0123456789
     * ghijklmnop   g:103
     * qrstuvwxyz   q:113
     * @param numeric $num
     * @return string
     */
    public static function numeric_encode($num){
        if(!is_numeric($num)) return '';

        $arrTmp = array();
        $randStr = ord('g') ; // g:103 q:113
        $arrTmp = str_split($num);
        foreach($arrTmp as $key=>$val){
            $randStr = rand(0, 1) ? ord('q') : ord('g');
            $arrTmp[$key] = '.' == $val ? '-' : chr($val + $randStr) ;
        }
        return implode('', $arrTmp);
    }
    /**
     * Desc decode from numeric_encode reuslt
     * 0123456789
     * ghijklmnop
     * qrstuvwxyz
     * @param string $str
     * @return numeric
     */
    public static function numeric_decode($str){
        if(!is_string($str)) return 0;

        $result = 0;
        $arrTmp = array();
        $arrTmp = str_split($str);

        foreach($arrTmp as $key=>$val){
            $randStr = (ord($val) > ord('p')) ? ord('q') : ord('g');
            $arrTmp[$key] = ('-' == $val) ? '.' : (ord($val) - $randStr) ;
        }
        $result = implode('', $arrTmp);
        $result = ($result > 0) ? $result : 0 ;
        return $result;
    }
    /**
	 * get URL as string
	 * Object to string
	 * @return string
	 */
	public function toString()
	{
	    return $this->_url;
	}

	/**
	 * overload
	 * Object to string
	 * @return string
	 */
	public function __toString()
	{
	    return $this->toString();
	}
}

/* test case
// smarter: rdt=0&ch=&oi=&mc=&dp=&bp=&pr=&rr=&sb=&ca=
$params =
            array(
               'rdtype'     => 0,
               'chid'       => 1,
               'offerid'    => 2,
               'mercount'   => 3,
               'displaypos' => 4,
               'bidpos'     => 5,
               'pricerank'  => 6,
               'raterank'   => 7,
               'clickarea'  => 8,
               'sortby'     => 'default',
               );
// * Sponsor: rdt=2&ch=&ra=&kw=&ct=&ur=
 $params =
       array(
           'rdtype'     => 2,
           'chid'       => 1,
           'rank'       => 3,
           'channeltag' => 'channeltag',
           'keyword'    => 'keyword',
           'desturl'    => 'destined URL',
       );
//* Product View: rdt=3&ch=&pi=&ri=&du=
 $params =
        array(
          'rdtype'   => 3,
          'chid'     => 1,
          'prodid'   => 2,
          'ratingid' => 4,
          'desturl'  => 'destined URL',
      );
// Merchant review: Merchant View: rdt=4&ch=&mi=&ri=&ur=
$params =
            array(
              'rdtype'   => 4,
              'chid'     => 1,
              'merid'    => 2,
              'reviewid' => 3,
              'desturl'  => 'destined URL',
          );
$redirUrl = new Tracking_UrlBuilder($params);
echo $redirUrl;
*/
