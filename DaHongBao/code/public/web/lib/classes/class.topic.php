<?php

   class Topic {
      
	  var $ListPagePos = 0;
	  	
      function Topic(){
         
      }
	  
	  function loadInfo($id) {
	  	  if ( $id > 0 ){
			  $sql = "SELECT * FROM Merchant WHERE Merchant_= $id";
			  $this->MerchantInfo = DBQuery::instance()->getRow($sql);
		  }
	  }
	  
      function getTopic($id){
	  		$sql = "SELECT * FROM topic WHERE id='$id'";
			$rs = DBQuery::instance()->getRow($sql);
			return $rs;
      }

	  function getTopicContent($topicid){
			$sql = "SELECT * FROM topiccontent WHERE topicid='$topicid' ORDER BY sort ASC";
			$rs = DBQuery::instance()->executeQuery($sql);
			return $rs;

	  }

	  function getTopicContentList($topicid){
			$sql = "SELECT * FROM topiccontent WHERE id='$topicid'";
			$rs = DBQuery::instance()->getRow($sql);
			return $rs;

	  }

	  function getTopicRow($topicid){
			$sql = "SELECT * FROM topic WHERE id='$topicid'";
			$rs = DBQuery::instance()->getRow($sql);
			return $rs;

	  }

	  function getTopicContentDetail($idarray){
			$sql = "SELECT pw_tmsgs.content,pw_threads.fid,pw_threads.tid,pw_threads.subject FROM pw_threads LEFT JOIN pw_tmsgs ON (pw_tmsgs.tid = pw_threads.tid) WHERE pw_threads.tid IN ($idarray)";
			//echo $sql;


			$rs = DBQuery::instance()->executeQuery($sql);
			
			foreach($rs as $key=>$value){
				$a = array("17", "18", "19","20","21","22","23","24","25","26","27","28","29","30","31","32","51");
				$b = array("food", "travel", "gift","Cosmestics","video","homegarden","apparel","electronics","maketdetail","training","toys","books","cartoon","digital","Sports","auto","adult");
				$rs[$key]['NameURL'] = str_replace($a, $b, $value['fid']);
			 }
			return $rs;
	  }

	  function getTopicCouponDetail($idarray){
			$sql = "SELECT CoupCat.Category_,Coupon.*,Merchant.NameURL FROM Coupon left join Merchant ON (Merchant.Merchant_=Coupon.Merchant_) left join CoupCat on (CoupCat.Coupon_ = Coupon.Coupon_) WHERE Coupon.Coupon_ IN ($idarray)";
			$rs = DBQuery::instance()->executeQuery($sql);
			return $rs;
	  }

	  function getTopicArray(){
			$sql = "SELECT * FROM topic";
			$rs = DBQuery::instance()->executeQuery($sql);
			return $rs;
	  }

	  function kg($tid){
			$sql = "SELECT isactive FROM topic where id='$tid'";
			$rs = DBQuery::instance()->getOne($sql);
			if($rs==0){
				$r=1;

			}else{
				$r=0;
			}
			$sql = "UPDATE topic SET isactive ='$r' where id='$tid'";
			$rs = DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
			return $rs;
	  }

	  function getTopicDetail($id){
			$sql = "SELECT * FROM topic WHERE id='$id'";
			$rs = DBQuery::instance()->getRow($sql);
			return $rs;

	  }

	  function updatetopic($post,$id){
		 //$sql = "SELECT * FROM topic WHERE id='$id'";
			$c = $post;
			$rs = DBQuery::instance()->autoExecute('topic',$c,DB_AUTOQUERY_UPDATE,"id=".$id);
			return $rs;
		
	  }

	  function updatetopicdetail($post){
			$c = $post;
			$rs = DBQuery::instance()->autoExecute('topiccontent',$c,DB_AUTOQUERY_UPDATE,"id=".$c['id']);
			return $rs;
		
	  }

	  function topicDelete($id){
		$sql = "delete FROM topiccontent WHERE id='$id'";
			$rs = DBQuery::instance(__DAHONGBAO_Master)->executeQuery($sql);
			return $rs;
	  }

	  function addtopicdetail($post){
			$c = $post;
			$rs = DBQuery::instance()->autoExecute('topiccontent',$c);
			return $rs;
		
	  }

	  function addtopic($post){
		$c = $post;
			$rs = DBQuery::instance()->autoExecute('topic',$c);
			return $rs;

	  }
	  function getTopicDetailArray($id){
			$sql = "SELECT * FROM topiccontent WHERE topicid='$id'";
			$rs = DBQuery::instance()->executeQuery($sql);
			return $rs;

	  }

	  function getTopicDetailRow($id){
			$sql = "SELECT * FROM topiccontent WHERE id='$id'";
			$rs = DBQuery::instance()->executeQuery($sql);
			return $rs;

	  }

	  function getTopicByType(){
			$sql = "SELECT id,title,topictype,topicweight,topicdetail from topic WHERE topicweight>0";
			$rs = DBQuery::instance()->executeQuery($sql);
			return $rs;
	  }

	  function getTopicMoreByType($id){
			$sql = "SELECT id,title,topictype,topicweight,topicdetail from topic WHERE topictype=$id AND isactive=1 ORDER BY id DESC";
			$rs = DBQuery::instance()->executeQuery($sql);
			return $rs;
	  }


    
   }
?>
