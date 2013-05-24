<?php
if ( !defined("CLASS_STATISTIC_PHP") ){
   define("CLASS_STATISTIC_PHP","YES");

         require_once(__INCLUDE_ROOT."/lib/functions/func.Debug.php");
         require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");

   class Statistic{
      var $ClassName = "Statistic";
      var $Key       = "Statistic_";
	  var $StatisticInfo = array();

      function Statistic(){

      }

      function update($source,$referrer,$keyword,$new_visitor,$category,$merchant,$coupon){
         if ( $new_visitor > 0 ){
		 	$sql = "UPDATE VSource SET Visitor=(Visitor+1),Uniq=(Uniq+".($new_visitor-1).") WHERE Dat=CURDATE() AND Source_=".$source;
			$back = DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
            if ( $back == 0 ){
				$sql = "INSERT INTO VSource (Source_,Dat,Visitor,Uniq) VALUES(".$source.",CURDATE(),1,".($new_visitor-1).")";
                DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
            }
			$sql = "UPDATE VReferrer SET Visitor=(Visitor+1),Uniq=(Uniq+".($new_visitor-1).") WHERE Dat=CURDATE() AND Referrer_=".$referrer;
			$back = DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
            if ( $back == 0 ){
				$sql = "INSERT INTO VReferrer (Referrer_,Dat,Visitor,Uniq) VALUES(".$referrer.",CURDATE(),1,".($new_visitor-1).")";
				DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
            }
/*
            $this->run_spec("UPDATE VKeyword SET Visitor=(Visitor+1),Uniq=(Uniq+".($new_visitor-1).") WHERE Dat=CURDATE() AND Keyword_=".$keyword);
            if ( $this->RowAffect == 0 ){
               $this->run_spec("INSERT INTO VKeyword (Keyword_,Dat,Visitor,Uniq) VALUES(".$keyword.",CURDATE(),1,".($new_visitor-1).")");
            }
            if ( ($referrer > 1) && ($keyword > 1) ){
               $this->run_spec("UPDATE RefKey SET Visitor=(Visitor+1) WHERE Dat=CURDATE() AND Keyword_=".$keyword." AND Referrer_=".$referrer);
               if ( $this->RowAffect == 0 ){
                  $this->run_spec("INSERT INTO RefKey (Referrer_,Keyword_,Dat,Visitor) VALUES(".$referrer.",".$keyword.",CURDATE(),1)");
               }
            }
*/
         }

/*
         if ( $category > 0 ){
            $this->run_spec("UPDATE SCategory SET Visitor=(Visitor+1) WHERE Dat=CURDATE() AND Source_=".$source." AND Referrer_=".$referrer." AND Keyword_=".$keyword." AND Category_=".$category);
            if ( $this->RowAffect == 0 ){
               $this->run_spec("INSERT INTO SCategory (Category_,Source_,Referrer_,Keyword_,Dat,Visitor) VALUES(".$category.",".$source.",".$referrer.",".$keyword.",CURDATE(),1)");
            }
         }
*/
         if ( $merchant > 0 ){
		 	$sql = "UPDATE SMerchant SET Visitor=(Visitor+1) WHERE Dat=CURDATE() AND Source_=".
			       $source." AND Referrer_=".$referrer." AND Keyword_=".$keyword." AND Merchant_=".$merchant;
			$back = DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
            if ( $back == 0 ){
				$sql = "INSERT INTO SMerchant (Merchant_,Source_,Referrer_,Keyword_,Dat,Visitor) VALUES(".
				       $merchant.",".$source.",".$referrer.",".$keyword.",CURDATE(),1)";
				DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
            }
         }
         if ( $coupon > 0 ){
		 	$sql = "UPDATE SCoupon SET Visitor=(Visitor+1) WHERE Dat=CURDATE() AND Source_=".
				   $source." AND Referrer_=".$referrer." AND Keyword_=".$keyword." AND Coupon_=".$coupon;
			$back = DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
            if ( $back == 0 ){
				$sql = "INSERT INTO SCoupon (Coupon_,Source_,Referrer_,Keyword_,Dat,Visitor) VALUES(".
					   $coupon.",".$source.",".$referrer.",".$keyword.",CURDATE(),1)";
                DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
            }
         }
      }
   }
}
?>