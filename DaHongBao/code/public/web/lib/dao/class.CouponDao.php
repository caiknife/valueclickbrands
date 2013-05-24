<?PHP
	
class CouponDao {
	function __construct() {
		
	}
	
	function getCouponDetail($id){
		$sql = "SELECT Coupon.*,
					Category.NameURL AS cnameurl,
						Category.Category_,
				p.digest,p.tid,p.fid,p.replies, m.Name MerchantName,m.name1, m.NameURL MerchantNameURL, m.URL MerchantURL,m.isShow Mshow,p.author,p.authorid ";
					
			$sql .= "FROM Coupon inner join pw_threads p on ( p.dhbid = Coupon.Coupon_) 
					 left join Merchant m on (Coupon.Merchant_= m.Merchant_) 
					 left join CoupCat on (CoupCat.Coupon_=Coupon.Coupon_) 
					 left join Category on (Category.Category_=CoupCat.Category_) 
					 LEFT join CouponTag ON(CouponTag.couponid = Coupon.Coupon_) 
					 LEFT join Tag ON (Tag.id = CouponTag.tagid) 
					 WHERE Coupon.Coupon_= '$id' AND Coupon.IsActive=1 GROUP BY Coupon.Coupon_";
        return DBQuery::instance()->getRow($sql);
	}



}