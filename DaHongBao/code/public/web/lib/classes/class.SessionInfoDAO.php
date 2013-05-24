<?php
# class.SessionInfoDAO.php

# The instance of this class manages the database activities which affects
# SessionInfo table.
# DAO stands for Data Access Object.
#

if ( !defined("CLASS_SESSIONINFODAO_PHP") ){
    define("CLASS_SESSIONINFODAO_PHP","YES");

    #@ _FRAG_
         //require_once(__INCLUDE_ROOT."lib/classes/class.BlackBoxDAO.php");
         require_once(__INCLUDE_ROOT."lib/classes/class.UserSessionDAO.php");
         require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
		 require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
    
    #@ _CLASS_LINE_
    class SessionInfoDAO
    #@ _CLASS_LINE_
    {
        /**
        * Constructure.
        */
        function SessionInfoDAO() {
        }

        /**
        * The current implementation supports the following keys
        * 1. KEYWORD
        * 2. REFERRER
        * 3. STICKY (If a new session does not have Source information, previous
        *            source and sourcegroup cookie will be used. For this case,
        *            I set the STICKY flag to 1 indicating the user stick with
        *            the previous source/sourcegroup)
        */
        function addSessionInfo($ltuid, $sessionID, $keyValuePairs) {

            $dao = new UserSessionDAO();
            $pk  = $dao->getSessionPK($ltuid, $sessionID);
            $dao = null;
            
            $allValues = "";

            $size = count($keyValuePairs);

            while( list($key, $value) = each($keyValuePairs) ) {
                $allValues = $allValues."(NULL, $pk, '$key', '$value', '0')";
                if( $size > 1 ) {
                    $allValues = $allValues.",";
                    $size = $size - 1;
                }
            }
            
            $query = "INSERT INTO SessionInfo (SessionInfo_, UserSession_, SessionInfoType, InfoValueStr, isTaken) ";
            $query .= "VALUES ".$allValues;
            DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($query);
            
        }
    }
    #@ _FRAG_

    class SessionInfoTypeDAO 
    {
        function SessionInfoTypeDAO() {
        }

        function getTypePK($key) {
            $ret = '-1';
            $query = "SELECT SessionInfoType_ FROM SessionInfo WHERE Code = '".strtoupper($key)."'";
			$rs = DBQuery::instance()->getOne($query);
            return $rs;
        }
    }
}

?>
