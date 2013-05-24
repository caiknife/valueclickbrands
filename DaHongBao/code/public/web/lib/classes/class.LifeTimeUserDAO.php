<?php
# class.LifeTimeUserDAO.php
# The instance of this class handles the activities which affect
# table LifeTimeUser and LifeTimeUserSeq.
#

    require_once(__INCLUDE_ROOT."etc/const.inc.php");
    require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
	require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");

if( !defined("CLASS_LIFETIMEUSERDAO_PHP") ) {
  define("CLASS_LIFETIMEUSERDAO_PHP", "YES");    

   // require_once(__INCLUDE_ROOT."lib/classes/class.BlackBoxDAO.php");
  
  class LifeTimeUserDAO{
//      function LifeTimeUserDAO($host = "", $db = "", $user = "", $passwd = "") {
//          BlackBoxDAO::BlackBoxDAO($host, $db, $user, $passwd);
//      }
	    function LifeTimeUserDAO() {
        }
      /**
       * Adding User to LifeTimeUser table, and update LifeTimeUser_SEQ table.
       */
      function addUser() {
          $userSeq = new LifeTimeUserSeqDAO();
          $nextSeq = $userSeq->getNextLTUID();
          //$userSeq->destory();
          $userSeq = null;
          $query = "INSERT INTO LifeTimeUser VALUES (NULL,'$nextSeq',NOW(),'0')";
          DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($query);
          
          return $nextSeq;
      }
    }


  //
  // This inner class is used to get a new unique life time user ID.
  // As we have more than one database, the LifeTimeUserSeq table
  // has a ServerCode to indicate which server handles the user account.
  // This unique id will be formatted as "<id>_<servercode>". The formatted
  // id will be used for cookie LUID.
  //
  class LifeTimeUserSeqDAO extends BlackBoxDAO
  {
     function LifeTimeUserSeqDAO() {
     }

     function getNextLTUID() {
       $query_lock    = "LOCK TABLE LifeTimeUser_SEQ WRITE";
       $query_getData = "SELECT LifeTimeUser_SEQ_, ServerCode FROM LifeTimeUser_SEQ";
       $query_update  = "UPDATE LifeTimeUser_SEQ SET LifeTimeUser_SEQ_ = LifeTimeUser_SEQ_ + 1";
       $query_unlock  = "UNLOCK TABLE";

       // LOCK TAble to avoid multiple access.
       DBQuery::instance()->executeUpdate($query_lock);

       // Get the next SEQ ID
       $row = DBQuery::instance()->getRow($query_getData);
       
       $nextVal = $row["LifeTimeUser_SEQ_"];
       $code    = $row["ServerCode"];

       // Update the SEQ for the next User.
       DBQuery::instance()->executeUpdate($query_update);

       // Unlock Table
       DBQuery::instance()->executeUpdate($query_unlock);

       return $nextVal.'_'.$code;
     }

        // * This function is used to retrieve the server code. Each server has it's own
        // * server code. The code is stored in the LifeTimeUser_SEQ table. When we install
        // * new system, make sure the valid server code is assigned to a server.
        //  @return Server Code stored in LifeTimeUser_SEQ or FALSE if the code could not be
        // retrieved for some reason.
        function getServerCode()
        {
       $query = "SELECT ServerCode FROM LifeTimeUser_SEQ";
       
            $ok = DBQuery::instance()->getOne($query);
			if(strlen($row) > 0) {
				return $row;
			}
			return false;
     }
   }
}
?>
