<?php
require_once 'garevingest-analyticspros.php';

class Tracking_Revenue_Ingest extends Tracking_Revenue_Abstract
{

    private static $_instance = null;

    private static $_tracker = null;

    private static $_transactionId = null;

    private $_lable = null;

    public function __construct ($params = array())
    {
        ob_start();
        parent::__construct();
        
        $this->_setParams($params);
        
        if (($sponsorType = $this->_getParam(Tracking_Uri::SPONSOR_TYPE)) != false) {
            $this->_sponsorType = $sponsorType;
        }
        
        if ($this->_debug == true) {
            $this->pre($_COOKIE);
            $this->pre($this->_params);
            $this->pre($this->_hostname);
            $this->pre($this->_GA_property);
            // $this->show_test();
        }
    }

    function show_test ()
    {
        $this->pre($this->get_transactionId());
        $this->pre(decbin($this->get_transactionId()));
        $crd = $this->_getParam('curRandStr');
        $len = strlen($crd);
        for ($i = 0; $i < $len; $i ++) {
            $arr[] = $crd[$i];
        }
        $int = '';
        foreach ($arr as $v) {
            $int .= ord($v) . '.';
        }
        $this->vd($int);
        $this->vd(chr($int));
    }

    public static function getInstance ($params = array())
    {
        if (self::$_instance == null) {
            self::$_instance = new self($params);
        }
        return self::$_instance;
    }

    public function getTracker ()
    {
        if (self::$_tracker == null) {
            if (! isset($_COOKIE['__utma']) || ! isset($_COOKIE['__utmz'])) {
                //Traffic filtering does not support cookies
                if (Tracking_Session::getInstance()->isLanding()) {
                    $this->logError('filter out malicious traffic.');
                    die();
                }
                self::$_tracker = new GoogleAnalyticsTracker($this->_GA_property);
            } else {
                self::$_tracker = GoogleAnalyticsTracker::readclient($this->_GA_property);
            }
        }
        return self::$_tracker;
    }

    /**
     * click_affiliation = (location.Pathname)-{pos}-{traffic source}-{channel id}
     * click_revenue = RPC number returned from G-STS call.
     * tax = 0
     * shipping = 0
     * user_city = null
     * user_region = null
     * user_country = null
     * ad_reference = {ad revenue partner} (e.g. google)
     * ad_channel = {channel id}
     * ad_category = Same as Event Category + Action (e.g. google-afc-adclick-text)
     *
     * a	Values assignments for Ecommerce parameters:
     * i	addTrans (add transaction):
     * 1	order ID		$transaction_id
     * 2	affiliation		$click_affiliation
     * 3	total $click_revenue
     * 4	tax $tax
     * 5	shipping		$shipping
     * 6	city			$user_city
     * 7	state or province	$user_region
     * 8	country $user_country
     * ii	addItem:
     * 1	order ID		$transaction_id
     * 2	SKU/code		$ad_reference
     * 3	product name	$ad_channel
     * 4	category		$ad_category
     * 5	unit price		$click_revenue
     * 6	quantity		1
     */
    public function GALogger ()
    {
        $ad_channel = $this->_getParam('channelTag');
        
        $revPartnerType = $this->_getParam('revPartnerType');
        $revPartner = $this->_getParam('revPartner');
        
        if (strtoupper($this->_sponsorType) == 'YAHOO') {
            $ad_category = 'yahoo-in';
            $ad_action = 'adclick-in';
        } else {
            $GA_event_param = explode('_', $this->_GAParams['GA_event_params'][strtolower($revPartner . '_' . $revPartnerType)]);
            $ad_category = $GA_event_param[0];
            $ad_action = $GA_event_param[1];
        }
        
        $this->_clickType = $this->_getClickType($ad_category);
        
        // "(location.Pathname)-{pos}-{source}-{channel} Original format
        // $this->_lable = sprintf('{%1$s}-{%2$s}-{%3$s}-{%4$s}', $this->_getParam('pathname'), $this->_getParam('clickArea'), $this->_sourceAbbr, $this->_getParam('channelTag'));
        
        /**
         * Events:
         * Category "Ad Click"
         * Action "<ad partner name>" (e.g."google-csa")
         * Label "<ad type> (<ad position>)" (e.g. “adclick-flash-r-t-1”)
         */
        $this->_lable = sprintf('{%1$s}-{%2$s}', $ad_action, $this->_getParam('clickArea'));
        
        // This get_ad_click_revenue() function would be used to call the G-STS via REST API; sending G-STS the parameters, and receiving back the RPC value.
        $click_revenue = $this->get_ad_click_revenue();
        $rpc_event = 1;
        if ($click_revenue == null) {
            $click_revenue = 0.011;
            $this->_lable .= '-{default-rpc}';
        }
        
        if ($this->_params['config_track_event'] == true) {
            //Chris asked to make a revise on Event category and action on Jan 29, 2013 
            self::getTracker()->trackEvent($ad_action, $ad_category, $this->_lable, $rpc_event);
            
            // DEBUG TOBE REMOVED AFTER VALIDATION
            // $this->logError(sprintf('%1$s >> %2$s >> %3$s >> %4$s',$ad_category, $ad_action, $this->_lable, $rpc_event));
        }
        
        if ($this->_params['config_track_ecomm'] == true) {
            $tax = 0;
            $shipping = 0;
            $transaction_id = self::get_transactionId();
            
            // OPTIONAL: resolve geo-location of user
            // These should be set to null if not used.
            $user_city = null;
            $user_region = null; // state/province/district/etc
            $user_country = null;
            
            // $ad_reference = $trafficParter . '_' . $revenueParter; Original format
            // $ad_reference = $this->_sourceAbbr . '_' . $revPartner;
            
            // $click_affiliation = (location.Pathname)-{pos}-{traffic source}-{channel id}
            $click_affiliation = $this->_lable;
            
            /**
             * Ecommerce: product sku
             * Change this to only contain: {location.Pathname}-{Ad Unit name} –{Ad Position}
             * Example: {/health/home-remedies-for-coughing/}-{google-afc}-{mid-1}
             */
            $ad_reference = sprintf('{%1$s}-{%2$s}-{%3$s}', $this->_getParam('pathname'), $ad_category, $this->_getParam('clickArea'));
            
            // Send a Google Analytics Transaction hit
            self::getTracker()->addTrans($transaction_id, $click_affiliation, $click_revenue, $tax, $shipping, $user_city, $user_region, $user_country);
            self::getTracker()->addItem($transaction_id, $ad_reference, $ad_channel, $ad_category, $click_revenue, 1);
            self::getTracker()->trackTrans(); // send
            
          // DEBUG TOBE REMOVED AFTER VALIDATION
          // $this->logError(sprintf('%1$s >> %2$s >> %3$s >> %4$s >> %5$s >> %6$s',$transaction_id, $click_affiliation, $click_revenue, $ad_reference, $ad_channel,$ad_category));
        }
        if ($this->_debug == true) {
            $this->pre(array(
                    $ad_action,
                    $ad_category,
                    $this->_lable,
                    $rpc_event
            ), 'EventLog:');
            
            $this->pre(array(
                    $transaction_id,
                    $click_affiliation,
                    $click_revenue,
                    $tax,
                    $shipping,
                    $user_city,
                    $user_region,
                    $user_country
            ), 'TransLog:');
            
            $this->pre(array(
                    $transaction_id,
                    $ad_reference,
                    $ad_channel,
                    $ad_category,
                    $click_revenue,
                    1
            ), 'ItemLog:');
            
            $this->pre(self::getTracker());
            $a = array();
            foreach (self::getTracker()->beacons as $key => $val) {
                $urlInfo = parse_url($val);
                $query = $urlInfo['query'];
                foreach (explode('&', $query) as $k => $v) {
                    $a[$key][$k] = $v;
                }
            }
            $this->pre($a);
        }
    }

    public function get_user_city ()
    {
    }

    public function get_user_province ()
    {
    }

    public function get_user_country ()
    {
    }

    public function logEvent ($params = null)
    {
    }

    public function logTransaction ()
    {
    }

    public function get_ad_channel ()
    {
        return '';
    }

    public function get_ad_category ()
    {
    }

    public function get_ad_sponsor ()
    {
    }

    public static function get_transactionId ()
    {
        if (self::$_transactionId == null) {
            // $times = gettimeofday();
            // self::$_transactionId = $times['sec'] . $times['usec'] . substr(mt_rand(), 0, 6); // 22
            self::$_transactionId = GoogleAnalyticsTransaction::generate_transaction_id();
        }
        return self::$_transactionId;
    }

    /**
     * http://semtag101.beta.wl.mezimedia.com:8890/findgtag/?siteid=12&kw=adopt+a+grandparent&source=valueclick_&userip=xx&useragent=&sessionid=xxx&landingurl=&ref=&country=
     * http://semtag101.beta.wl.mezimedia.com:8890/setgtag/?siteid=12&kw=adopt+a+grandparent&source=valueclick_&userip=xx&useragent=&sessionid=xxx&landingurl=&ref=&country=
     *
     * @see Tracking_Revenue_Abstract::get_ad_click_revenue() .
     *      <g-sts version="1.0">
     *      <rpc>0.7</rpc>
     *      <message>
     *      "adopt a grandparent" set match to "Search" defeat, cause The match type is not Bid type.
     *      </message>
     *      <time>0.026</time>
     *      </g-sts>
     */
    public function get_ad_click_revenue ()
    {
        $flag = false;
        $rpc = 0.011; // default value
        for ($i = 0; $i < 3; $i ++) {
            $xml = $this->_requestChannelTag();
            if (is_string($xml)) {
                $rs = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
                if (is_object($rs)) {
                    $rpc = (string) $rs->{'rpc'};
                }
                if ($rpc != false) {
                    $flag = true;
                    break;
                } else {
                    $this->logError($this->_lable . "_{rpc value is:$rpc}");
                }
            }
        }
        if ($flag == false) {
            $this->logError($this->_lable . '_{catch the exception,request channel tag return null}');
        }
        if (isset($rs)) {
            $this->pre($rs, null, '#20C');
        }
        return $rpc;
    }

    public function addTransaction ()
    {
    }

    public function addItem ()
    {
    }
}
