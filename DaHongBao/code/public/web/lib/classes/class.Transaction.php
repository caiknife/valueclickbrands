<?php
// class.Transaction.php
// The instance of this class handles the activites which affects
// table Transaction. It also knows how to append merchant ID to the
// affiliate URLs.
         require_once(__INCLUDE_ROOT . "etc/const.inc.php");
         require_once(__INCLUDE_ROOT . "lib/functions/func.Debug.php");
         require_once(__INCLUDE_ROOT . "lib/classes/class.BlackBoxDAO.php");
         require_once(__INCLUDE_ROOT . "lib/classes/class.LifeTimeUserDAO.php");
	     require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
if (!defined("CLASS_TRANSACTION_PHP")) {
    define("CLASS_TRANSACTION_PHP", "YES");

    class Transaction {
        var $ltuid = '';
        var $sessionID = '';
        var $merchantPK = '';
        var $couponPK = '';
        var $click = '1'; // User clicks the coupon or merchant icon. Click=0 for invisibel frame.
        var $affiliates = array('.linksynergy.com' => '&u1=',
            '.linkshare.com' => '&u1=',
            'clickserve.cc-dt.com' => '&mid=',
            '.bfast.com' => '&bfinfo=',
            '.qksrv.net' => '?SID=');

        /**
         *
         * @param  $ltuid = LifeTimeUser PK.
         * @param  $sessionID = UserSession ID
         * @param  $merchantPK = Merchant PK
         * @param  $couponPK = Coupon PK
         * @param  $click = Is Click from coupon icon or merchant icon
         */
        function Transaction($ltuid = "Unknown", $sessionID = 0, $merchantPK = 0, $couponPK = 0, $click = 1)
        {
            $this->ltuid = $ltuid;
            $this->sessionID = $sessionID;
            $this->merchantPK = $merchantPK;
            $this->couponPK = $couponPK;
            $this->click = $click;
        }

        /**
         *
         * @param  $dynamicURL The affiliate URL.
         * @return String Affiliate URL which might contain merchant ID.
         * Insert Transaction info to table Transaction.
         */
        function appendTransactionInfo($dynamicURL)
        {
            $dao = new TransactionDAO();
            $transID = $dao->addTransaction($this->ltuid,
                $this->sessionID, $this->merchantPK,
                $this->couponPK, $this->click);

            $dao = null;

            $appended = $this->addTransactionID($dynamicURL, $transID);

            return $appended;
        }

        /**
         * This is supposed to be a private function.
         */
        function addTransactionID($dynamicURL, $transacID)
        {
            $ret = $dynamicURL;

            while (list($aff, $tag) = each($this->affiliates)) {
                $pos = strpos($dynamicURL, $aff);

                if ($pos !== false) {
                    $ret = $ret . $tag . $transacID;
                    break;
                }
            }

            return $ret;
        }
    }

    /**
     * The instance of this class insert Transaction info into table Transaction.
     * It returns the Primary key of the newly created record to caller.
     */
    class TransactionDAO{
        var $DUMMY_KEY = 'CM_0';

        function TransactionDAO()
        {
        }
		
		function free_result()
        {
        }
        /**
         * Adding User to LifeTimeUser table, and update LifeTimeUser_SEQ table.
         *
         * @param  $ltuid = LifeTimeUser PK.
         * @param  $sessionID = UserSession ID
         * @param  $merchantPK = Merchant PK
         * @param  $couponPK = Coupon PK
         * @param  $click = Is Click from coupon icon or merchant icon
         */
        function addTransaction($ltuid = "unknown",
            $sessionID = 0,
            $merchantPK = 0,
            $couponPK = 0,
            $click = 1)
        {
            $query = "INSERT INTO Transaction ";
            $query .= "(Transaction_, LTUID, SessionID, Merchant_, Coupon_, isClick, Revenue, TransacTime, isTaken) ";
            $query .= "VALUES (NULL,'$ltuid', '$sessionID', $merchantPK, $couponPK, $click, '0', NOW(),'0')";
            $qID = "SELECT LAST_INSERT_ID()";

            $ok = DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($query);
            if ($ok == false) {
                return $this->DUMMY_KEY . '_' . $ltuid . '_' . $sessionID;
            }

            $ok = DBQuery::instance()->getOne($qID);
            if ($ok == false) {
                return $this->DUMMY_KEY . '_' . $ltuid . '_' . $sessionID;
            }
            $id = $ok;

            $userSeq = new LifeTimeUserSeqDAO();
            $sCode = $userSeq->getServerCode();
            $userSeq = null;

            if ($sCode == false) {
                $sCode = 0;
            }

            $uID = $id . '_' . $sCode;

            $tail = '';

            // At this moment, 1 indicates the click is done by user manually.
            // 0 indicates the click is the merchant hidden frame.
            // 2 indicates the click is universal (popdown) hidden frame.
            if ($click != 1) {
                $tail = ($merchantPK == 0) ? '_uuu' : '_fff';
            }

            return $uID . $tail;
        }
    }
}

?>
