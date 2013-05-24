<?php

if ( !defined("CLASS_BLACKBOXDAO_PHP") ){
    define("CLASS_BLACKBOXDAO_PHP","YES");

    require_once(__INCLUDE_ROOT."lib/classes/class.MySQLAccess.php");
    
    class BlackBoxDAO extends MySQLAccess {
        var $host_name = "";
        var $user_name = "";
        var $password  = "";
        var $db_name   = "";

        function BlackBoxDAO($host="", $base="", $user="", $pass="") {
            $this->host_name = ( "" != $host ? $host : __DB_HOST );
            $this->db_name   = ( "" != $base ? $base : __DB_BASE );
            $this->user_name = ( "" != $user ? $user : __DB_USER );
            $this->password  = ( "" != $pass ? $pass : __DB_PASS );
        }
        
        /**
        * release system resources.
        */
        function destory() {
            $this->disconnect();
        }
    }
}

?>
