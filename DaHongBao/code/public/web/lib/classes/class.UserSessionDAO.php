<?php
# class.UserSessionDAO.php

# The instance of this class manages the database activities which affects
# UserSessionDAO.
# DAO stands for Data Access Object.
#
         require_once(__INCLUDE_ROOT."etc/const.inc.php");
         require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
         require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");


if ( !defined("CLASS_USERSESSIONDAO_PHP") ){
    define("CLASS_USERSESSIONDAO_PHP","YES");

    #@ _FRAG_
        // require_once(__INCLUDE_ROOT."lib/classes/class.BlackBoxDAO.php");
    
    #@ _CLASS_LINE_
    class UserSessionDAO
    #@ _CLASS_LINE_
    {
        /**
        * Constructure.
        * Expect caller to pass valid server number
        */
        function UserSessionDAO() {
        }

        /**
        * Add new account to LifeTimeUser table.
        * The LTUID (Life Time User ID) should be returned to caller.
        */
        function addSession($ltuid, $sourceGroup, $source) {
            
            // I think timestamp is good enough. We expect the session will expire
            // when user closes the browser. As long as the LTUID is unquie, we are
            // OK.
            $nextSeq = time();
            $query  = "INSERT IGNORE INTO UserSession (UserSession_, SessionID, LTUID, SourceGroup, Source, VisitTime, isTaken)";
            $query .= " VALUES (NULL,$nextSeq,'$ltuid','$sourceGroup','$source',NOW(),'0')";

            DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($query);

            return $nextSeq;
        }

        /**
         * Get UserSession's PK
         */
        function getSessionPK($ltuid, $sessionID) {
            $rst = -1;
            $query = "SELECT UserSession_ FROM UserSession WHERE LTUID = '$ltuid' AND SessionID = $sessionID";
			$rs = DBQuery::instance()->getOne($query);
			return $rs;
        }
    }
    #@ _FRAG_
}
?>
