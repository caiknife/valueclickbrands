<?php

/**
 * Tracking_Merchant
 *
 * @category   Tracking
 * @package    Tracking_Merchant
 */
class Tracking_Merchant
{
    const DSN_FRONT_END             = __FrontEnd;
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
    private $_channels = array(
                1   => 'Book',
                2   => 'Computer',
                3   => 'Electronic',
                4   => 'Movie',
                5   => 'Music',
                6   => 'Photography',
                7   => 'Software',
                8   => 'Game',
                9   => 'Flower',
                10  => 'GameAC',
            );

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
     * Constructor
     */
	public function __construct()
	{
        $this->_dbFrontEnd  = $this->_getConnection(self::DSN_FRONT_END);
        $this->_dbEtl       = $this->_getConnection(self::DSN_ETL);
        $this->_dbAccount   = $this->_getConnection(self::DSN_ACCOUNT);
	}

	/**
     * get destination url of offlined offer
     *
     * @param integer $channelId
     * @param integer $offerId
     * @return NULL|string
     */
	public function getOfflineOfferUrl($channelId, $offerId)
	{
        $destUrl = '';
        if ((int)$offerId < 1 || (int)$channelId < 1) {
            $errMsg = "Offer&ChannelId Must be Int, Error Offer&Channel Id: $offerId & $channelId \n";
            Tracking_Debug::dump($errMsg);
            return $destUrl;
        }

        if($channelId && $offerId){
            $sql = "SELECT URL FROM `OfferOfflineLog`
                    WHERE `OfferID` = $offerId AND ChannelID = $channelId LIMIT 1";

            if ((bool)$result = mysql_query($sql, $this->_dbFrontEnd)) {
                $row = mysql_fetch_assoc($result);
                mysql_free_result($result);
                $destUrl = $row['URL'];
            } else {
                $errMsg = __METHOD__ . " SQL SELECT Error: $sql\n";
                Tracking_Debug::dump($errMsg);
            }
        }
        return $destUrl;
	}

    /**
     * get rediretion information contains productID, merchantID, CPC, URL
     *
     * @param resource $FEConnection
     * @param integer $channelId
     * @param integer $offerId
     * @return array
     */
	public function fetchOffer($channelId, $offerId)
	{
        $row = array();
        if ((int)$offerId < 1) {
            $errMsg = "OfferId Must be Int, Error OfferId: $offerId \n";
            Tracking_Debug::dump($errMsg);
            return $row;
        }

        if($channelId && $offerId){
            $merchantBidProductTable = $this->_getBidProductTable($channelId);

            $sql = "SELECT `ProductID`, `MerchantID`, `URL`, `r_CPC`, `DataSource`, "
                 . "`DisplayLogo`, `SDCOfferID`, `r_BusinessType`, `Position`, r_ExtraCPC "
                 . "FROM {$merchantBidProductTable} "
                 . "WHERE OfferID = {$offerId}";

            if ((boolean)$result = mysql_query($sql, $this->_dbFrontEnd)) {
                $row = mysql_fetch_assoc($result);
                mysql_free_result($result);
            } else {
                $errMsg = __METHOD__ . " SQL SELECT Error: $sql\n";
                Tracking_Debug::dump($errMsg);
            }
        }
        return $row;
	}

	/**
	 * desc: modify charge cpc method, from stats to dba
	 *
	 * @param array $params
	 * @return boolean
	 */
	public function chargeCpcLog($params)
	{
        $amount     = isset($params['cpc'])        ? $params['cpc']        : 0;
        $merchantId = isset($params['merchantid']) ? $params['merchantid'] : 0;
        $sessionId  = isset($params['sessionid'])  ? $params['sessionid']  : '';
        $clicks     = 1;
        $type       = isset($params['type'])       ? $params['type']       : 'CHARGE';
        $flag       = 0;
        $sourceSite = (integer) Mezi_Config::getInstance()->tracking->site->id;

        $channelId  = isset($params['channelid'])  ? $params['channelid']  : 0;
        $productId  = isset($params['productid'])  ? $params['productid']  : 0;
        $cpcLogo    = isset($params['cpcforlogo']) ? $params['cpcforlogo'] : 0;

        $randStr    = Tracking_Session::getInstance()->getPreRequestId();
        $curRandStr = Tracking_Session::getInstance()->getRequestId();

        $bidPosition     = isset($params['bidposition'])     ? $params['bidposition']     : 0;
        $displayPosition = isset($params['displayposition']) ? $params['displayposition'] : 0;
        $extraCpc        = isset($params['extracpc'])        ? $params['extracpc']        : 0;

        $sql = "INSERT INTO TrackingChargeLog "
             . "(`ClickTime`, `SessionID`, `MerchantID`, `Amount`, `Clicks`, "
             . "`Type`, `Flag`, `SourceSite`, `BidPosition`, `DisplayPosition`, "
             . "`ChannelId`, `ProductId`, `CpcForLogo`, `RandStr`, `CurRandStr`, "
             . " `ExtraCPC`) VALUES "
             . "(NOW(), '$sessionId', '$merchantId', '$amount', '$clicks', "
             . "'$type', '$flag', '$sourceSite', '$bidPosition', '$displayPosition', "
             . "'$channelId', '$productId', '$cpcLogo', '$randStr', '$curRandStr', "
             . "'$extraCpc')";

	    if (!@mysql_query("SET SESSION AUTOCOMMIT = FALSE", $this->_dbAccount)) {
            throw new Exception("Cannot Change AutoCommit mode(FALSE)." . mysql_error($this->_dbAccount));
        }

        try {
            if (!@mysql_query('START TRANSACTION', $this->_dbAccount) ||
                !@mysql_query($sql, $this->_dbAccount) ||
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

        return TRUE;
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

        throw new Track_Exception('Cannot connect to DB. Retry time out.' . $errMsg);
    }
}