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
* @Version  : CVS: $Id: AjaxController.php,v 1.3 2013/04/26 09:01:52 rizhang Exp $
*/
namespace US\Controller;

use Custom\Mvc\Controller\UsController;
use Zend\View\Model\ViewModel;
use CommModel\Coupon\Coupon;
use CommModel\Coupon\Favorite;
use CommModel\Subscription\Subscription;
use Custom\Util\PathManager;
use Custom\Util\TrackingFE;
use Custom\Util\Utilities;

class AjaxController extends UsController 
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

        $favoriteTable = $this->_getTable('Favorite');
        $result = $favoriteTable->isFavoriteCoupon($UID, $couponid);
        if ($result) {
            return array('status' => 'error', 'msg' => '您已经收藏该优惠券');
        }
        $couponTable = $this->_getTable('Coupon');
        $couponInfo = $couponTable->getCouponInfoByID($couponid, self::SITEID, 'Coupon', true, false, false, true);
        if (empty($couponInfo)) {
            return array('status' => 'error', 'msg' => '暂无相关数据');
        }
        $couponInfo['UID'] = $UID;
        $couponInfo['UserName'] = $_COOKIE['UserName'];
        $couponInfo['CouponDetailUrl'] = '';
        $couponInfo['OfferUrl'] = TrackingFE::registerOfferLink($couponInfo, false);
        $favoriteTable->insertFavoriteCoupon($couponInfo);
        return array('status' => 'ok', 'msg' => '收藏成功');
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