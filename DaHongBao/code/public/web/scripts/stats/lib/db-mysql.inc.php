<?php
class MysqlDB {

	private  $m_user 	= "";
    private $m_pswd	    = "";
    private $m_host	    = "";
    private $m_database = "";
    private $m_debug	= "0";
    private $m_plink	= "0";
    private $m_link     = "";
    private $m_res	    = "";
	private $m_port     = 3306;

    public function __construct($r_host='', $r_user='', $r_pswd='', $r_database='', $r_port='', $debug = 0) {
		$this->m_host 	  = ($r_host != '') ? $r_host : __TRACK_DB_HOST ;
    	$this->m_user 	  = ($r_user != '') ? $r_user : __TRACK_DB_USER ;
    	$this->m_pswd 	  = ($r_pswd != '') ? $r_pswd : __TRACK_DB_PASS ;
        $this->m_port     = ($r_port != '') ? $r_port : __TRACK_DB_PORT;
    	$this->m_database = ($r_database != '')? $r_database : __TRACK_DB_BASE;
    	$this->doConnect();
    }

    public function __destruct()
    {
        $this->doClose();
    }

    //����Debug״̬.
    public function setDebug($debug = 0){
    	if ($debug == 0 || $debug == 1){
    		$this->m_debug = $debug;
    	}
    }

    //�����>�l��
    public function setPlink($plink = 0){
  		if($plink == 1 || $plink == 0){
  			$this->m_plink = $plink;
  		}
    }

    //��?��
    public function errorProcess($errorMsg="", $sql=""){
    	echo $errorMsg . "<br>\n";
    	echo "Exec: => " . $sql . "<br>\n";
    	if($this->m_debug == 1){
    		exit;
    	}
    	return true;
    }

    //��ݿ������l��
    public function doConnect(){
     	if(!$this->m_link){
     		//������ݿ������l��Դ
     		if($this->m_plink == 1){
     			$this->m_link = @mysql_pconnect($this->m_host . ":" . $this->m_port, $this->m_user, $this->m_pswd);
     		}else{
     			$this->m_link = @mysql_connect($this->m_host . ":" . $this->m_port, $this->m_user, $this->m_pswd);
     		}

     		//���l��Դ
     		if (!$this->m_link){
				$this->errorProcess("Error code" . mysql_errno() . ", Error info: ". mysql_error());
				return false;
			}

			//��ݿ�ѡ��
			$this->selectDatabase();
     	}
     	return true;
     }

     //��ݿ�ѡ��
	public function selectDatabase($database=""){
		if ($database != ""){
			$this->m_database = $database;
		}

		if (!@mysql_select_db($this->m_database, $this->m_link)){
			$this->errorProcess("Error code" . mysql_errno($this->m_link) . ", Error info: ". mysql_error($this->m_link));
			return false;
		}
		return true;
	}

	//ִ��һ��SQL��ѯ
	public function doQuery($sql=""){
		if (!$this->m_link){
			$this->doConnect();
		}
		$this->m_res = @mysql_query($sql, $this->m_link);
		if (!$this->m_res){
			$this->errorProcess("Error code" . mysql_errno($this->m_link) . ", Error info: ". mysql_error($this->m_link), $sql);
			return false;
		}
		return true;
	}

	//���ؽ���е�һ��,�Զ������ʽ
	public function doFetch(){
		if($this->m_res){
			return mysql_fetch_object($this->m_res);
		}else{
			return false;
		}
	}

	//�����������������ʽ.
	public function doFetchAll(){
		if($this->m_res){
			while($arrItem = mysql_fetch_assoc($this->m_res)){
				$arr[] = $arrItem;
			}
			$this->doFreeResult();
			return $arr;
		}else{
			return false;
		}
	}

	//����SELECT��ѯӰ�������
	public function getSelectNum(){
		if($this->m_res){
			return @mysql_num_rows($this->m_res);
		}
		return 0;
	}

	//����UPDATE, DELETE, INSERT,Ӱ�������
	public function getAffectNum(){
		if($this->m_res){
			return @mysql_affected_rows($this->m_res);
		}
		return 0;
	}

	//�������һ��INSERT�������ID.
	public function getLastInsertID(){
        if($this->m_link){
            return @mysql_insert_id($this->m_link);
        }
        return 0;
    }

    //�Ͽ���ݿ�������l
	public function doClose(){
		return @mysql_close($this->m_link);
	}

	public function doFreeResult(){
		return @mysql_free_result($this->m_res);
	}
}
?>