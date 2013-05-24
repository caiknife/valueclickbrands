<?php
/*
 * package_name : CouponOperateDetailTable.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: CouponOperateDetailTable.php,v 1.1 2013/04/15 10:57:07 rock Exp $
 */
namespace BackEnd\Model\Coupon;

use Custom\Db\TableGateway\TableGateway;
use Custom\Util\Utilities;

class CouponOperateDetailTable extends TableGateway 
{
    protected $table = 'CouponOperateDetail';
    
    /**
     * 插入信息
     * @param int 优惠卷ID
     * @param string $editor 
     * @param sting $operator 操作方式
     * @param string $operatorDetail
     */
    public function insert($couponID, $editor = 'Auto', $operator = 'UPDATE', $operatorDetail = null) 
    {
        if (empty($couponID)) {
            return false;
        }
        $insertDateTime = Utilities::getDateTime('Y-m-d H:i:s');
        $insert['CouponID'] = $couponID;
        $insert['Editor'] = $editor;
        $insert['InsertDateTime'] = $insertDateTime;
        $insert['Operator'] = $operator;
        $insert['OperatorDetail'] = $operatorDetail;
        return parent::insert($insert);
    }
}