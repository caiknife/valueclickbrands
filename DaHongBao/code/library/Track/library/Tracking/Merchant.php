<?php
/**
 * Mezimedia Tracking
 *
 * @category   Tracking
 * @package    Tracking_Merchant
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Merchant.php,v 1.1 2013/04/15 10:56:35 rock Exp $
 */

/**
 * Tracking_Merchant
 *
 * @category   Tracking
 * @package    Tracking_Merchant
 */
class Tracking_Merchant
{
    const DSN_FRONT_END             = __FrontEnd;
    const DSN_ACCOUNT               = __ACCOUNTDB;
    const DSN_ETL                   = __ETL;

    const DB_RECONNECTION_INTERVAL  = 100000;
    const DB_RECONNECTION_TIMES     = 20;

    /**
     * @var resource
     */
    private $_dbEtl = NULL;

    /**
     * @var resource
     */
    private $_dbFrontEnd = NULL;

    /**
     * channel table prefix
     *
     * @var array
     */
    private $_channels = array(
            /* for SMCN*/
                1   => 'Book',
                2   => 'Computer',
                3   => 'Electronic',
                4   => 'Movie',
                5   => 'Music',
                6   => 'Photography',
                7   => 'Software',
                8   => 'Game',
                9   => 'Flower',
               10   => 'GameAC',

            /* for SMCN*/
            100553  => 'Computers',
            100673  => 'OfficeDevice',
            100642  => 'Electronics',
            100666  => 'Communication',
            100693  => 'Cosmetics',
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
        $this->_dbEtl       = $this->_getConnection(self::DSN_ETL);
        $this->_dbFrontEnd  = $this->_getConnection(self::DSN_FRONT_END);
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
     * get merchant business type
     *
     * @param integer $merchantId
     * return string CPC or CPA
     */
    public function getBusinessType($merchantId){
        $sql = "SELECT `BusinessType` FROM Merchant WHERE `MerchantID` = $merchantId";
        if ((boolean)$result = mysql_query($sql, $this->_dbFrontEnd)) {
            $result = mysql_fetch_assoc($result);
        } else {
            $errMsg = __METHOD__ . " SQL SELECT Error: $sql\n";
            Tracking_Debug::dump($errMsg);
        }
        return $result['BusinessType'];
    }

    /**
     * modify merchant balance
     *
     * @param integer   $merchantId
     * @param float     $revenue
     * @param string    $businessType
     * @return array    ('old'=>(integer), 'new'=>(integer), 'successful'=>(boolean))
     */
    public function modifyBalance($merchantId, $revenue, $businessType='CPC'){
        if (strtoupper($businessType) != 'CPC') {
        	return  array('old'=>0, 'new'=>0, 'successful' =>TRUE);
        }

        /* lock table */
        mysql_query("LOCK TABLES MerAccount WRITE", $this->_dbEtl);

        $successful = FALSE;

        /* read balance */
        $sql = "SELECT BidBalance, BidStatus FROM MerAccount WHERE MerchantID = $merchantId LIMIT 1";
        if ((boolean) $result = mysql_query($sql, $this->_dbEtl)) {
            $result = mysql_fetch_assoc($result);

            $oldBalance = $result['BidBalance'];

            if ($result['BidStatus']=='LIVE') {
            	$newBalance = $oldBalance - $revenue;
                mysql_query("UPDATE MerAccount SET BidBalance = $newBalance WHERE MerchantID = $merchantId", $this->_dbEtl);

                if($newBalance < _BID_MIN_BALANCE){
                    /* send balance notice */
                    $samKey = md5("tracking{$merchantId}charge");
                    file_get_contents(__SAM_DOMAIN_NAME . "tools/merCharge.php?merid={$merchantId}&key={$samKey}");
                }

                $successful = TRUE;
            }
            mysql_free_result($result);
        }

        /* release lock */
        mysql_query("UNLOCK TABLES", $this->_dbEtl);

        if(!$successful){
            $mailMessage = "hello administrator: \r\n A merchant charge failed, please check it. here is the detail information: \r\n Merchant id: $merchantId \r\n reason: the merchant not exists in MerAccount table or the merchant have no balance \r\n Current time:".getTimeStr();
            //send_mail(explode(',', T_ADMIN_MAIL), "a merchat charge failed", $mailMessage);
            $oTracking = new Tracking();
            $oTracking->raiseError($mailMessage);
        }

        $oldBalance = $oldBalance ? $oldBalance : 0;
        $newBalance = $newBalance ? $newBalance : 0;
        return  array('old'=>$oldBalance, 'new'=>$newBalance, 'successful' =>$successful);
    }

    /**
     * get rediretion information contains productID, merchantID, CPC, URL
     *
     * @param integer $channelId
     * @param integer $offerId
     * @return array
     */
	public function fetchOffer($channelId, $offerId)
	{
        $result = array();

        if ((integer)$offerId < 1) {
            $errMsg = "OfferId Must be an integer, Error OfferId: $offerId \n";
            Tracking_Debug::dump($errMsg);
            return $result;
        }

        if($channelId && $offerId) {
            $merchantBidProductTable = $this->_getBidProductTable($channelId);

            $sql = "SELECT `ProductID`, `MerchantID`, `URL`, `r_CPC`, `DataSource`, 'YES' AS `IsOnline`
                    FROM {$merchantBidProductTable}
                    WHERE OfferID = {$offerId}";
            if ((boolean)$result = mysql_query($sql, $this->_dbFrontEnd)) {
                $result = mysql_fetch_assoc($result);
            } else {
                $errMsg = __METHOD__ . " SQL SELECT Error: $sql\n";
                Tracking_Debug::dump($errMsg);
            }

            if(empty($result) || !trim($result['URL'])) {
                $sql = "SELECT `ProductID`, `MerchantID`, `URL`, 0 AS `r_CPC`, `DataSource`, `IsOnline`
                        FROM {$merchantBidProductTable}
                        WHERE OfferID = {$offerId}";
                if ((boolean)$result = mysql_query($sql, $this->_dbEtl)) {
                    $result = mysql_fetch_assoc($result);
                } else {
                    $errMsg = __METHOD__ . " SQL SELECT Error: $sql\n";
                    Tracking_Debug::dump($errMsg);
                }
            }
        }

        return $result;
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