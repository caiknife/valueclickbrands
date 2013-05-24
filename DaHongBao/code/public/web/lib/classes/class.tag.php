<?php

   class TAG {
      
	  //var $ListPagePos = 0;
	  	
      function TAG(){
         
      }
	  
	  function add($value) {
		  if($value=="") return;
		  $sql = "INSERT INTO Tag (id,tagname) VALUES ('','$value')";
		  $result = DBQuery::instance(__DAHONGBAO_Master)->executeQuery($sql);
		  return $result;
	  }
	  
	  function findcoupon($value){
		 $sql = "select Coupon.*,GROUP_CONCAT(Tag.tagname) as tagname from Coupon LEFT JOIN CouponTag ON (CouponTag.couponid = Coupon.Coupon_) LEFT join Tag ON (Tag.id = CouponTag.tagid) WHERE (Coupon.Descript LIKE '%$value%' OR Coupon.Coupon_='$value') AND Coupon.isActive=1 GROUP BY Coupon.Coupon_ ORDER BY Coupon.Coupon_ DESC";
		 //echo $sql;
		 $result = DBQuery::instance()->executeQuery($sql);
		 return $result;

	  }

	  function findtop100(){
		$sql = "select Coupon.*,GROUP_CONCAT(Tag.tagname) as tagname from Coupon LEFT JOIN CouponTag ON (CouponTag.couponid = Coupon.Coupon_)  LEFT join Tag ON (Tag.id = CouponTag.tagid) WHERE tagname is null AND Coupon.isActive=1 GROUP BY Coupon.Coupon_ ORDER BY Coupon.Coupon_ DESC limit 50";
		 //echo $sql;
		 $result = DBQuery::instance()->executeQuery($sql);
		 return $result;
	  }

	  function addone($id,$value){
		  $sql = "DELETE FROM CouponTag WHERE couponid = '$id'";
		  $result = DBQuery::instance(__DAHONGBAO_Master)->executeQuery($sql);
		  $value = explode(',',$value);
		  foreach($value as $key=>$go){
			 $sql = "select id from Tag where tagname='$go'";
			 $result = DBQuery::instance()->getOne($sql);
			 if($result>0){
				 $sql = "INSERT INTO CouponTag (id,tagid,couponid) VALUES ('','$result','$id')";
				 $result = DBQuery::instance(__DAHONGBAO_Master)->executeQuery($sql);
			 }else{
				 if($go=="") return;
				$sql = "INSERT INTO Tag (id,tagname) VALUES ('','$go')";
				$result = DBQuery::instance(__DAHONGBAO_Master)->executeQuery($sql);
				$tid = DBQuery::instance(__DAHONGBAO_Master)->getInsertID();

				$sql = "INSERT INTO CouponTag (id,tagid,couponid) VALUES ('','$tid','$id')";
				$result = DBQuery::instance(__DAHONGBAO_Master)->executeQuery($sql);
			 }
			 //echo $result;
		  }
		 

	  }


	  function addall($id,$tagname){

			$sql = "select id from Tag where tagname='$tagname'";
			$tagid = DBQuery::instance()->getOne($sql);  //check tag exist;
			 if($tagid>0){   //has tag
				  $id = explode(',',$id);
				  foreach($id as $key=>$value){
						$sql = "SELECT id FROM CouponTag WHERE tagid='$tagid' AND couponid = '$value'";
						$has = DBQuery::instance()->getOne($sql);
						if($has>0){
						}else{
							 $sql = "INSERT INTO CouponTag (id,tagid,couponid) VALUES ('','$tagid','$value')";
							 $result = DBQuery::instance(__DAHONGBAO_Master)->executeQuery($sql);
						}
				  }
			 }else{
				 if($tagname=="") return;
				 $sql = "INSERT INTO Tag (id,tagname) VALUES ('','$tagname')";
				$result = DBQuery::instance(__DAHONGBAO_Master)->executeQuery($sql);
				$tid = DBQuery::instance(__DAHONGBAO_Master)->getInsertID();
				 $id = explode(',',$id);
				  foreach($id as $key=>$value){
					  if($value==""){

					  }else{
						 $sql = "INSERT INTO CouponTag (id,tagid,couponid) VALUES ('','$tid','$value')";
						 $result = DBQuery::instance(__DAHONGBAO_Master)->executeQuery($sql);
					  }
				  }
			 }
	  }


    
   }
?>
