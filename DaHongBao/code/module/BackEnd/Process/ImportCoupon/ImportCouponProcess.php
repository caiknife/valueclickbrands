<?php
/*
 * package_name : ImportCouponProcess.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: ImportCouponProcess.php,v 1.3 2013/04/27 07:30:26 thomas_fu Exp $
 */
namespace BackEnd\Process\ImportCoupon;

use BackEnd\Process\Process;
use Custom\Util\Utilities;
use BackEnd\Model;
use BackEnd\Process\Exception\ProcessException;

class ImportCouponProcess extends Process 
{
    /**
     * 获取dataFeed最大的条数
     */
    const FETCH_MAX_ROWS = 100;
    
    /**
     * 主要字段
     * @var array
     */
    protected $couponNeedFieldValue = array(
            'MerchantID'  => true,
            'CouponName'  => true,
            'CouponUrl'   => true,
            'CouponStartDate' => true,
            'CouponEndDate' => true,
    );
    
    /**
     * 统计信息
     * @var array
     */
    protected $statInfo = array(
            'DataCheckErrorCnt' => 0,
            'NewCouponCount' => 0,
            'NewDiscountCount' => 0,
            'ExistCouponCount' => 0,
            'ExistDiscountCount' => 0,
            'ExistCouponCodeCount' => 0,
            'CouponHasDiscountCount' => 0,
            'UserDataRemainCount' => 0,
            'ImportCouponUseTime' => '',
            'Merchant' => array(),
    );
    
    
    /**
     * 初始化数据
     * @param serviceManager $sm
     * @return null
     */
    public function init($sm)
    {
        $adapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
        $this->couponTable = new Model\Coupon\CouponTable($adapter);
        $this->userDataFeedTable = new Model\FeedConfig\UserDataFeedTable($adapter);
        $this->affiliateTable = new Model\FeedConfig\AffiliateTable($adapter);
        $this->categoryTable = new Model\Category\CategoryTable($adapter);
        $this->couponCodeTable = new Model\Coupon\CouponCodeTable($adapter);
        $this->couponSearchTable = new Model\Coupon\CouponSearchTable($adapter);
        $this->merchantTable = new Model\Merchant\MerchantTable($adapter);
        $this->merchantCategoryTable = new Model\Merchant\MerchantCategoryTable($adapter);
        $this->couponCategoryTable = new Model\Coupon\CouponCategoryTable($adapter);
        $this->couponOperateDetailTable = new Model\Coupon\CouponOperateDetailTable($adapter);
        $this->couponExtraTable = new Model\Coupon\CouponExtraTable($adapter);
    }
    
    /**
     * 得到couponCode列表
     * @param string $couponCodeString
     * @return array
     */
    public function getCouponCodeList($couponCodeString = '')
    {
        $result = array();
        if (empty($couponCodeString)) {
            return $result;
        }
    
        $couponCodeList = explode($this->userDataFeedTable->couponsSeparator, $couponCodeString);
        foreach ($couponCodeList as $couponCodeLine) {
            list($couponCode, $couponPass) = explode($this->userDataFeedTable->couponPassSeparator, $couponCodeLine);
            if (!empty($couponCode)) {
                $result[$couponCode] = $couponPass;
            }
        }
        return $result;
    }
    
    /**
     * 插入couponCategory
     * @param int $couponID
     * @param int $merid
     * @param string $categoryName
     */
    public function insertCouponCategeory($couponID, $merid, $categoryName = null) 
    {
        $cateIds = array();
        //根据CategoryName得到categoryID
        if (!empty($categoryName)) {
            $cateIds = $this->getCateIdsByName($categoryName);
            foreach ($cateIds as $cateName => $catID) {
                $this->couponCategoryTable->insertCategory($couponID, $catID);
            }
        }
        //根据FeedCategoryName 没有找到CatID, 默认使用商家分类
        if (empty($cateIds)) {
            if (!isset($this->merCateList[$merid])) {
                $this->merCateList[$merid] = $this->merchantCategoryTable->getMerCateListByMerID($merid);
            }
            if (!empty($this->merCateList[$merid])) {
                foreach ($this->merCateList[$merid] as $categoryInfo) {
                    $this->couponCategoryTable->insertCategory($couponID, $categoryInfo['CategoryID']);
                }
            }
        }
    }
    /**
     * 根据userDataFeed中CategoryName得到categoryID 列表
     * @param string $dataFeedCategoryEnName
     * @return array categoryIDList
     */
    public function getCateIdsByName($dataFeedCategoryName) 
    {
        $categoryIds = array();
        $this->getCateUsNameKeyList();
        $categoryNameList = explode(',', $dataFeedCategoryName);
        foreach ($categoryNameList as $categoryName) {
            $catKey = md5($categoryName);
            if (isset($this->categoryNameKeyList[$catKey])) {
                $categoryIds[$categoryName] = $this->categoryNameKeyList[$catKey];
            }
        }
        return $categoryIds;
    }
    
    /**
     * 得到UScategory分类以enName为KEY的列表
     * @return array
     */
    public function getCateUsNameKeyList() 
    {
        if (isset($this->categoryNameKeyList)) {
            return $this->categoryNameKeyList;
        }
        
        $this->categoryNameKeyList = array();
        foreach ($this->categoryTable->getUsCategoryList() as $categoryInfo) {
            $this->categoryNameKeyList[md5($categoryInfo['CategoryEnName'])] = $categoryInfo['CategoryID'];
            $this->categoryNameKeyList[md5($categoryInfo['CategoryName'])] = $categoryInfo['CategoryID'];
        }
    }
    
    /**
     * 在title 和 desc 中是否包含有 CMUS的couponCode
     * @param array $dataFeedRow
     * @return true|false
     */
    public function isExistDiscountFromCmusCoupon($dataFeedRow) 
    {
        $couponCodeList = array();
        $cmusAffiliateID = $this->getCmusAffiliateID();
        $couponCodeList = $this->couponTable->getCouponCodeListByAffID($cmusAffiliateID);
        if (empty($couponCodeList)) {
            return false;
        }
        foreach ($couponCodeList as $couponCode) {
            if (strpos($dataFeedRow['CouponName'], $couponCode) !== false 
                || strpos($dataFeedRow['CouponDescription'], $couponCode) !== false) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * 统计这个商家每个分类的优惠卷
     * @todo: 现在是直接获取所有商家，等以后后台自动更新脚本去掉此步骤
     */
    public function syncMerCategory($merid) 
    {
        $isNeedInsert = false;
        $couponCate = $this->couponTable->getCouponCateByMerID($merid);
        if ($this->isCmusAffiliate($this->affiliateID) === true) {
            $isNeedInsert = true;
        }
        foreach ($couponCate as $catid => $couponCateInfo) {
            $this->merchantCategoryTable->replace(
                $merid, 
                $catid, 
                $couponCateInfo['CouponCnt'],
                $couponCateInfo['DiscountCnt'],
                $isNeedInsert
            );
        }
    }
    
    /**
     * 判断rowData中主字段值
     * @param unknown_type $rowData
     */
    public function checkData($rowData) 
    {
        foreach ($this->couponNeedFieldValue as $fieldName => $value) {
            if (empty($rowData[$fieldName])) {
                return false;
            }
        }
        if ($rowData['CouponEndDate'] < $rowData['CouponStartDate']) {
            return false;
        }
        return true;
    } 
    
    /**
     * 获取统计信息
     */
    public function getStatInfo() 
    {
        return $this->statInfo;
    }
}
?>