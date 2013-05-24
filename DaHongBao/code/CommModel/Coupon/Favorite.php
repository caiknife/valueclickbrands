<?php
/*
* package_name : file_name
* ------------------
* typecomment
*
* PHP versions 5
* 
* @Author   : Richie Zhang(rizhang@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: Favorite.php,v 1.1 2013/04/15 10:56:26 rock Exp $
*/
namespace CommModel\Coupon;

use Zend\Db\Adapter\Adapter;
use Custom\Db\TableGateway\TableGateway;

class Favorite extends TableGateway
{
    protected $table = "CouponFavorite";
    /*
     * 是否已经收藏该coupon
     */
    public function isFavoriteCoupon($uid, $couponid) {
        $where = array(
            'UID'      => $uid,
            'CouponID' => $couponid,
        );
        $select = $this->getSql()->select();
        $select->columns(array('CouponID'));
        $select->where($where);
        if ($limit !== null || $offset !== null) {
            $select->limit($limit);
            $select->offset($offset);
        }
        $resultSet = $this->selectWith($select);
        return $resultSet->toArray();
    }
    
    /*
     * 收录用户收藏的coupon/deals信息
     */
    public function insertFavoriteCoupon($couponInfo = array()) {
        $insertValues = array(
            'UID' => $couponInfo['UID'],
            'UserName' => $couponInfo['UserName'],
            'CouponID' => $couponInfo['CouponID'],
            'CouponName' => $couponInfo['CouponName'],
            'CouponUrl'  => $couponInfo['CouponUrl'],
            'CouponImageUrl' => $couponInfo['CouponImageUrl'],
            'CouponStartDate' => $couponInfo['CouponStartDate'],
            'CouponEndDate'=> $couponInfo['CouponEndDate'],
            'SiteID'   => $couponInfo['SiteID'],
            'CouponDetailUrl' => $couponInfo['CouponDetailUrl'],
            'OfferUrl' => $couponInfo['OfferUrl'],
            'MerchantID'   => $couponInfo['MerchantID'],
            'MerchantName' => $couponInfo['MerchantName'],
            'LogoFile'     => $couponInfo['LogoFile'],
            'InsertDateTime' => date('Y-m-d H:i:s'),
            'LastChangeDateTime' => date('Y-m-d H:i:s'),
        );
        $insert = $this->getSql()->insert();
        $insert->values($insertValues);
        return $this->insertWith($insert);
    }
}