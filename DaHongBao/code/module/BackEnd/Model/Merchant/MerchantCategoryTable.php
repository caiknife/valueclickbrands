<?php
/*
 * package_name : MerchantCategoryTable.php ------------------ typecomment PHP
 * versions 5 @Author : thomas(thomas_fu@mezimedia.com) @Copyright: Copyright
 * (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com) @license :
 * http://www.mezimedia.com/license/ @Version : CVS: $Id:
 * MerchantCategoryTable.php,v 1.6 2013/04/04 07:34:14 thomas_fu Exp $
 */
namespace BackEnd\Model\Merchant;
use Custom\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Expression;
use Custom\Util\Utilities;
class MerchantCategoryTable extends TableGateway
{
    protected $table = 'MerchantCategory';
    
    /**
     * 更新商家coupon 和 discount数量
     * 
     * @param int $merid
     *            商家ID
     * @param int $catid
     *            分类ID
     * @param int $couponCnt
     *            优惠券数量
     * @param int $discountCnt
     *            折扣信息数量
     * @return int 插入条数
     */
    public function replace($merid, $catid, $couponCnt = 0, $discountCnt = 0, $isNeedInsert = false)
    {
        if(empty($merid) || empty($catid)){
            throw new \Exception('merid or catid is empty');
        }
        $where['MerchantID'] = $merid * 1;
        $where['CategoryID'] = $catid * 1;
        $update['r_OnlineCouponCount'] = $couponCnt * 1;
        $update['r_OnlineDiscountCount'] = $discountCnt * 1;
        $merCateInfo = $this->getInfo($where);
        if ($merCateInfo) {
            return parent::update($update , $where);
        } else if ($isNeedInsert === true) {
            $insert = $where + $update;
            return parent::insert($insert);
        }
    }
    
    /**
     * 根据Mid和Cid获取
     * @param int $mid
     * @param int $cid
     * @return array|NULL
     */
    function getInfoByMidAndCid($mid , $cid){
        return parent::getInfo(array('MerchantID' => (int)$mid , 'CategoryID' => (int)$cid));
    }
    
    /**
     * 得到商家的分类列表
     * 
     * @param int $merid            
     * @return array
     */
    public function getMerCateListByMerID($merid)
    {
        if(empty($merid)){
            return false;
        }
        $where['MerchantID'] = $merid * 1;
        return $this->getList($where);
    }
    public function getCategoriesByMid($mid)
    {
        $mid = (int)$mid;
        $result = $this->select(array(
            'MerchantID' => $mid
        ));
        
        $re = array();
        foreach($result as $v){
            $re[] = $v->CategoryID;
        }
        
        return $re;
    }
    public function getCountByCid($cid)
    {
        return $this->getListCount(array(
            'CategoryID' => $cid
        ));
    }
    
    /**
     * 插入
     * 
     * @param int $merid 商家ID
     * @param array $cates 商家所属分类
     * @return boolean
     */
    public function save($merid, array $cates)
    {
        
//         $this->clear($merid);
        foreach($cates as $v){
            $this->insert(array(
                'MerchantID' => $merid,
                'CategoryID' => (int)$v,
                'r_OnlineCouponCount' => 0,
                'r_OnlineDiscountCount' => 0
            ));
        }
        return true;
    }
    
    /**
     * 清除
     * 
     * @param array|int $id            
     * @return number
     */
    public function clear($id)
    {
        if(is_array($id)){
            $where = $this->_getSelect()->where;
            $where->in('MerchantID' , $id);
            
            return $this->delete($where);
        }
        $id = (int)$id;
        if(! $id){
            throw new \Exception('incomplete $id');
        }
        return $this->delete(array(
            'MerchantID' => $id
        ));
    }
    
    /**
     * 删除商家分类
     * @param int $merid
     * @param array|int $catid
     * @return boolean
     */
    public function deleteMerCategory($merid, $categorids = array()) 
    {
        
        $where = $this->_getSelect()->where;
        if (is_array($categorids)) {
            $where->in('CategoryID' , $categorids);
        } else if (is_int($categorids)) {
            $where->equalTo('CategoryID' , $categorids);
        }
        $where->equalTo('MerchantID', (int)$merid);
        return $this->delete($where);
    }
}