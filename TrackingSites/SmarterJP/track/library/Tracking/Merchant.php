<?php
/**
 * Mezimedia Tracking
 *
 * @category   Tracking
 * @package    Tracking_Merchant
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Merchant.php,v 1.1 2013/06/27 07:54:18 jjiang Exp $
 */

/**
 * Tracking_Merchant
 *
 * @category   Tracking
 * @package    Tracking_Merchant
 */
class Tracking_Merchant
{
    const DSN_FRONT_END             = __PEAR_DSN_FRONTEND;
    const DSN_ETL                   = __ETL;
    const DSN_ACCOUNT               = __ACCOUNTDB;

    const DB_RECONNECTION_INTERVAL  = 100000;
    const DB_RECONNECTION_TIMES     = 20;

    /**
     * @var resource
     */
    private $_dbFrontEnd = NULL;

    /**
     * @var resource
     */
    private $_dbEtl = NULL;

    /**
     * @var resource
     */
    private $_dbAccount = NULL;

    /**
     * channel table prefix
     *
     * @var array
     */
    private $_channels = array();

    /**
     * current request
     *
     * @var Tracking_Request_Outgoing
     */
    protected $_request     = NULL;

    /**
     * get merchant bid product table for one channel
     *
     * @param integer $channelId
     * @return string
     */
    private function _getBidProductTable($channelId)
    {
        $tablePrefix = isset($this->_channels[$channelId])
                     ? $this->_channels[$channelId]
                     : 'C' . $channelId;

        return $tablePrefix . 'MerchantBidProduct';
    }

    /**
     * get tracking url
     *
     * @param string $ruleText
     * @param string $url
     * @param integer $merchantId
     * @return string
     */
    private function _getTrackingURL($ruleText, $url, $merchantId)
    {
        if($ruleText == "") return $url;

        $result = $ruleText;
        $result = str_replace("{URL}", $url, $result);
        $result = str_replace("{URL:Encoded}", urlencode($url), $result);
        $result = str_replace("{URL:Encoded:Encoded}", urlencode(urlencode($url)), $result);
        $result = str_replace("{MerchantID}", $merchantId, $result);

        return($result);
    }

    /**
     * Constructor
     */
	public function __construct()
	{
        $this->_dbFrontEnd  = $this->_getConnection(self::DSN_FRONT_END);
        $this->_dbEtl       = $this->_getConnection(self::DSN_ETL);
        $this->_dbAccount   = $this->_getConnection(self::DSN_ACCOUNT);
        $this->_request     = new Tracking_Request_Outgoing();
	}

    /**
     * log error
     *
     * @param string $remark
     */
    protected function _logError($remark) {
        $log = array(
            'remark' => $remark,
            'requesturi' => $this->_request->getRequestUri(),
            'referer'    => $this->_request->getHttpReferer(),
        );

        Tracking_Logger::getInstance()->Error($log);
    }

    /**
     * get offer information
     *
     * @param integer $channelId
     * @param integer $offerId
     * @return array | null
     */
	public function fetchOffer($channelId, $offerId)
	{
        if (empty($channelId) || empty($offerId)) {
            $this->_logError("OfferId Must be Int, Error OfferId: $offerId ");

            return null;
        }

    	$row = array();

        $mbpTable = $this->_getBidProductTable($channelId);
        $sql = "SELECT `ProductID`, `MerchantID`, `URL`, `TrackingURL`, '' AS `DataSource`, `Position`, `r_CPC`, `r_ExtraCPC`, `r_BusinessType`
                FROM {$mbpTable}
                WHERE OfferID = {$offerId}";
        if ((boolean)$result = mysql_query($sql, $this->_dbFrontEnd)) {
            $row = mysql_fetch_assoc($result);
            mysql_free_result($result);

            if (!empty($row['TrackingURL'])) {
                $row['URL'] = $row['TrackingURL'];
                unset($row['TrackingURL']);
            } else if (!empty($row['MerchantID'])) {
                $sql = "select RuleText from MerTrackingURL_Rules where MerchantID = {$row['MerchantID']} and Status > 0";
                if ((boolean)$result = mysql_query($sql, $this->_dbEtl)) {
                    if((boolean)$row2 = mysql_fetch_assoc($result)) {
                        $row['URL'] = $this->_getTrackingURL($row2['RuleText'], $row['URL'], $row['MerchantID']);
                    }
                    mysql_free_result($result);
                }
            }
        } else {
            $errMsg = __METHOD__ . " SQL SELECT Error: $sql" . PHP_EOL;
        }

        if (!empty($errMsg)) {
            $this->_logError($errMsg);
        }

        return $row;
	}

	/**
	 * modify charge cpc method
	 *
	 * @param array $params
	 * @return integer
	 */
	public function chargeCpcLog($params)
	{
        $chargId            = 0;
	    $clicks             = 1;
        $flag               = 0;
        $amount             = isset($params['revenue'])         ? (float)$params['revenue']             : 0;
        $merchantId         = isset($params['merchantid'])      ? (integer)$params['merchantid']        : 0;
        $channelId          = isset($params['channelid'])       ? (integer)$params['channelid']         : 0;
        $productId          = isset($params['productid'])       ? (integer)$params['productid']         : 0;
        $cpcLogo            = isset($params['cpcforlogo'])      ? (integer)$params['cpcforlogo']        : 0;
        $bidPosition        = isset($params['bidposition'])     ? (integer)$params['bidposition']       : 0;
        $displayPosition    = isset($params['displayposition']) ? (integer)$params['displayposition']   : 0;
        $type               = isset($params['type'])            ? addslashes($params['type'])           : 'CHARGE';

        $sourceSite         = (integer) Mezi_Config::getInstance()->tracking->site->id;
        $sessionId          = addslashes(Tracking_Session::getInstance()->getSessionId());
        $randStr            = addslashes(Tracking_Session::getInstance()->getPreRequestId());
        $curRandStr         = addslashes(Tracking_Session::getInstance()->getRequestId());

        $sql = "INSERT INTO TrackingChargeLog "
             . "(`ClickTime`, `SessionID`, `MerchantID`, `Amount`, `Clicks`, "
             . "`Type`, `Flag`, `SourceSite`, `BidPosition`, `DisplayPosition`, "
             . "`ChannelId`, `ProductId`, `CpcForLogo`, `RandStr`, `CurRandStr`) VALUES "
             . "(NOW(), '$sessionId', '$merchantId', '$amount', '$clicks', "
             . "'$type', '$flag', '$sourceSite', '$bidPosition', '$displayPosition', "
             . "'$channelId', '$productId', '$cpcLogo', '$randStr', '$curRandStr')";

	    if (!@mysql_query("SET SESSION AUTOCOMMIT = FALSE", $this->_dbAccount)) {
            throw new Exception("Cannot Change AutoCommit mode(FALSE)." . mysql_error($this->_dbAccount));
        }

        try {
            if (!@mysql_query('START TRANSACTION', $this->_dbAccount) ||
                !@mysql_query($sql, $this->_dbAccount) ||
                !($chargId = mysql_insert_id($this->_dbAccount)) ||
                !@mysql_query('COMMIT', $this->_dbAccount)
            ) {
            	throw new Tracking_Exception('Cannot add ChargeLog'. mysql_error($this->_dbAccount) . " [SQL]: $sql\n");
            }
        } catch (Tracking_Exception $exception) {
            @mysql_query('ROLLBACK', $this->_dbAccount);
            throw $exception;
        }

	    if (!@mysql_query("SET SESSION AUTOCOMMIT = TRUE", $this->_dbAccount)) {
            throw new Exception("Cannot Change AutoCommit mode(TRUE)." . mysql_error($this->_dbAccount));
        }

        return $chargId;
	}

    /**
     * get front end mysql connection
     *
     * @return resource|false
     */
    private function _getConnection($dsn = NULL)
    {
        //suppress error
        if (NULL == $dsn) {return FALSE;}

        $key = md5($dsn);
        if (!empty($this->_conn[$key])) {
            return $this->_conn[$key];
        }

        //parse dsn
        $parsed = parse_url($dsn);

        $port = !empty($parsed['port']) ? $parsed['port'] : 3306;
        $host = $parsed['host'] . ':' . $port;

        $username = $parsed['user'];
        $password = $parsed['pass'];
        $dbname   = substr($parsed['path'], 1);
        $count = 0;

        do {
            //force new link
            if (!$conn = @mysql_connect($host, $username, $password, true)) {
                $errMsg = "($count)DB Error: cannot connect to database. [Info]:" . mysql_error() . ' [DSN]:' . $dsn;
            }elseif (!@mysql_select_db($dbname, $conn)) {
                $errMsg = "($count)MySQL Error: cannot select db ($dbname). [Info]:" . mysql_error($conn) . ' [DSN]' . $dsn;
            }else {
                $this->_conn[$key] = $conn;
                return $conn;
            }

            usleep(self::DB_RECONNECTION_INTERVAL);
        } while ($count++ < self::DB_RECONNECTION_TIMES);

        throw new Tracking_Exception('Cannot connect to DB. Retry time out.' . $errMsg);
    }
}