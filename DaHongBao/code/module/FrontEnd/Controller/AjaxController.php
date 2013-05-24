<?php
/*
* package_name : AjaxController.php
* ------------------
* typecomment
*
* PHP versions 5
* 
* @Author   : Richie Zhang(rizhang@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: AjaxController.php,v 1.5 2013/04/26 09:01:52 rizhang Exp $
*/
namespace FrontEnd\Controller;

use Custom\Mvc\Controller\FrontEndController;
use Zend\View\Model\ViewModel;
use CommModel\Coupon\Favorite;
use CommModel\Coupon\Coupon;
use CommModel\Coupon\CouponExtra;
use CommModel\Coupon\CouponCode;
use CommModel\Coupon\CouponCodeUser;
use CommModel\Coupon\UserCouponCode;
use CommModel\Subscription\Subscription;
use Custom\Util\PathManager;
use Custom\Util\TrackingFE;
use Custom\Util\Utilities;

class AjaxController extends FrontEndController 
{
    /*
     * ajax调用
     */
    public function indexAction()
    {
        $type = $this->params()->fromQuery('type');
        switch ($type) {
	        case "favorite":
	            $couponid = $this->params()->fromQuery('couponid');
	            $result = $this->favoriteCoupon($couponid);
	            break;
	        case "getcoupon":
	            $couponid = $this->params()->fromQuery('couponid');
	            $result = $this->getCouponCode($couponid);
	            break;
	        case "subcription":
	            $email = $this->params()->fromQuery('email');
	            $result = $this->subcription($email);
	            break;
        }
        echo json_encode($result); exit;
    }

    /*
     * 用户收藏coupon
     */
    public function favoriteCoupon($couponid = null)
    {
        // 参数验证
        if(!$couponid || !is_numeric($couponid)) {
            return array('status' => 'error', 'msg' => '参数出错');
        }

        // 用户验证
        $authKey = $_COOKIE['AuthKey'];
        if (empty($authKey) || strlen($authKey) != 32 || empty($_COOKIE['UID']) || empty($_COOKIE['UserName'])) {
            return array('status' => 'error', 'msg' => '您尚未登录');
        }
        $userOnlineTable = $this->_getTable('UserOnline');
        $userOlineInfo = $userOnlineTable->isUserOnline($authKey);
        if (empty($userOlineInfo) || $userOlineInfo['UID'] != $_COOKIE['UID']) {
            return array('status' => 'error', 'msg' => '您尚未登录，或登录异常');
        }
        $UID = $userOlineInfo['UID'];//authKey对应的真实ID

        // Coupon验证
        $couponTable = $this->_getTable('Coupon');
        $couponInfo = $couponTable->getCouponInfoByID($couponid, self::SITEID, 'Coupon', true);
        if (empty($couponInfo)) {
            return array('status' => 'error', 'msg' => '暂无相关数据');
        }

        // 收藏验证
        $favoriteTable = $this->_getTable('Favorite');
        $result = $favoriteTable->isFavoriteCoupon($UID, $couponid);
        if ($result) {
            return array('status' => 'error', 'msg' => '您已经收藏该优惠券');
        }

        // 收藏Coupon
        $couponInfo['UID'] = $UID;
        $couponInfo['UserName'] = $_COOKIE['UserName'];
        $couponInfo['CouponDetailUrl'] = PathManager::getDhbCouponDetailUrl($couponInfo['CouponID']);
        $couponInfo['OfferUrl'] = TrackingFE::registerDHBOfferLink($couponInfo);
        $favoriteTable->insertFavoriteCoupon($couponInfo);
        return array('status' => 'ok', 'msg' => '收藏成功');
    }

    /*
     * 获取优惠券
     */
    public function getCouponCode($couponid = null)
    {
        // 参数验证
        if(!$couponid || !is_numeric($couponid)) {
            return array('status' => 'error', 'msg' => '参数出错');
        }

        // 用户验证
        $authKey = $_COOKIE['AuthKey'];
        if (empty($authKey) || strlen($authKey) != 32 || empty($_COOKIE['UID']) || empty($_COOKIE['UserName'])) {
            return array('status' => 'error', 'msg' => '您尚未登录');
        }
        $userOnlineTable = $this->_getTable('UserOnline');
        $userOlineInfo = $userOnlineTable->isUserOnline($authKey);
        if (empty($userOlineInfo) || $userOlineInfo['UID'] != $_COOKIE['UID']) {
            return array('status' => 'error', 'msg' => '您尚未登录，或登录异常');
        }
        $UID = $userOlineInfo['UID'];//authKey对应的真实ID

        // Coupon验证
        $couponTable = $this->_getTable('Coupon');
        $couponInfo = $couponTable->getCouponInfoByID($couponid, self::SITEID, 'Coupon', true, true);
        if (empty($couponInfo)) {
            return array('status' => 'error', 'msg' => '暂无相关数据');
        }
        if (time() > strtotime($couponInfo['CouponEndDate'])) {
            return array('status' => 'error', 'msg' => '该优惠券已过期');
        }

        // 获取验证
        $userCouponCodeTable = $this->_getTable('UserCouponCode');
        $result = $userCouponCodeTable->isUserCouponCode($UID, $couponid);
        if ($result) {
            return array('status' => 'error', 'msg' => '您已经领取过该优惠券');
        }

        // 判断还有没剩余的优惠券Code
        $leaveCnt = $couponInfo['LeaveCnt'];
        if ($leaveCnt == 0) {
            return array('status' => 'error', 'msg' => '该优惠券已被领取完');
        }

        //需要积分兑换，判断积分够不够
        $usePoints  = $couponInfo['UsePoints'];
        if ($usePoints > 0) {
            $userTable = $this->_getTable('User');
            $userInfo = $userTable->getUserRankPoints($UID);
            $rankPoints = $userInfo['RankPoints']; //我当前的积分数
            if ($rankPoints < $usePoints)  {
                return array('status' => 'error', 'msg' => '您的积分只有'.$rankPoints.'不满'.$usePoints);
            }
        }

        //--------------------  开始兑换优惠券   --------------------//
        //1.优惠券CouponID对应的领取数+1，剩下数-1
        $couponExtraTable = $this->_getTable('CouponExtra');
        $couponExtraTable->updateCouponAllCnt($couponid);
        //2.获取优惠券Code ( CouponCodeTotalCnt > 0 就是还能被使用, 0为使用完)
        $couponCodeTable = $this->_getTable('CouponCode');
        $couponCodeInfo  = $couponCodeTable->getCouponCodeInfoByID($couponid);
        //3.修改CouponCodeTotalCnt和LastChangeDateTime
        $couponCodeTable->updateCouponCodeInfo($couponCodeInfo['CouponCodeID']);
        //4.记录CouponCodeUser表
        $couponCodeUserTable = $this->_getTable('CouponCodeUser');
        $insert = array(
            'CouponCodeID' => $couponCodeInfo['CouponCodeID'],
            'CouponID'     => $couponCodeInfo['CouponID'],
            'UserID'       => $UID,
            'UserName'     => $_COOKIE['UserName'],
            'InsertDateTime'     => Utilities::getDateTime('Y-m-d H:i:s'),
            'LastChangeDateTime' => Utilities::getDateTime('Y-m-d H:i:s'),
        );
        $couponCodeUserTable->insertCouponCodeUser($insert);
        //5.记录数据到用户系统中
        $insert = array(
            'CouponCodeID' => $couponCodeInfo['CouponCodeID'],
            'UID'          => $UID,
            'UserName'     => $_COOKIE['UserName'],
            'CouponID'     => $couponCodeInfo['CouponID'],
            'CouponName'   => $couponInfo['CouponName'],
            'CouponCode'   => $couponCodeInfo['CouponCode'],
            'CouponUrl'    => $couponInfo['CouponUrl'],
            'CouponDetailUrl'  => PathManager::getDhbCouponDetailUrl($couponInfo['CouponID']),
            'CouponImageUrl'   => $couponInfo['CouponImageUrl'],
            'CouponPass'       => $couponCodeInfo['CouponPass'],
            'CouponStartDate'  => $couponInfo['CouponStartDate'],
            'CouponEndDate'    => $couponInfo['CouponEndDate'],
            'SiteID'           => self::SITEID,
            'MerchantID'       => $couponInfo['MerchantID'],
            'MerchantName'     => $couponInfo['MerchantName'],
            'LogoFile'         => $couponInfo['LogoFile'],
            'InsertDateTime'     => Utilities::getDateTime('Y-m-d H:i:s'),
            'LastChangeDateTime' => Utilities::getDateTime('Y-m-d H:i:s'),
        );
        $insert['OfferUrl'] = TrackingFE::registerDHBOfferLink($couponInfo);

        $userCouponCodeTable = $this->_getTable('UserCouponCode');
        $userCouponCodeTable->insertUserCouponCode($insert); //echo '<pre>';print_r($insert);exit;
        //兑换需要积分的，扣除用户积分
        if ($usePoints > 0) {
            //扣除用户表中的RankPoints
            $userTable = $this->_getTable('User');
            $userTable->reduceUserRankPoints($UID, $usePoints);

            //积分历史记录表
            $insert = array(
                'UID'  => $UID,
                'RuleName' => '积分兑换优惠券',
                'Point'    => '-'.$usePoints, //减去
                'Info'     => '系统扣除',
                'Desc'     => '用户使用'.$usePoints.'积分兑换了优惠券“'.$couponInfo['CouponName'].'[id:'.$couponInfo['CouponID'].']”,CouponCode：'.$couponCodeInfo['CouponCode'],
                'CreateTime' => Utilities::getDateTime('Y-m-d H:i:s'),
                'Type'       => 'System',
            );
            $userPointTable = $this->_getTable('UserPoint');
            $userPointTable->insetUserPoint($insert);
        }
        return array('status' => 'success', 'msg' => '兑换优惠券成功，请在个人会员中心查看');
    }

    public function subcription($email)
    {
        preg_match("/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/", $email, $match);
        if (empty($match)) {
            return $email."邮箱格式错误";
        }
        //判断是否已经订阅
        $subcriptionTable = $this->_getTable('Subscription');
        $result = $subcriptionTable->isExistEmail($email, self::SITEID);
        $uid = $_COOKIE['UID'] ? $_COOKIE['UID'] : 0;
        if (empty($result)) {
            $insertValues = array(
                'Email'  => $email,
                'UID'    => $uid,
                'SiteID' => self::SITEID,
                'CreatDateTime' => Utilities::getDateTime('Y-m-d H:i:s'),
                'LastChangeDateTime' => Utilities::getDateTime('Y-m-d H:i:s'),
            );
            $subcriptionTable->insert($insertValues);
            return $email."订阅成功";
        }else{
            return $email."已经被使用";
        }
    }
}
?>