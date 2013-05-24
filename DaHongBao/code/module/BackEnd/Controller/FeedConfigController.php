<?php
/*
* package_name : file_name
* ------------------
* typecomment
*
* PHP versions 5
* 
* @Author   : thomas fu(tfu@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com <http://www.mezimedia.com/> )
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: FeedConfigController.php,v 1.2 2013/04/19 02:09:57 thomas_fu Exp $
*/
namespace BackEnd\Controller;

use Custom\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Custom\Util\Utilities;
use Zend\Paginator\Paginator;
use Custom\Util\WordSplitter;

class FeedConfigController extends AbstractActionController 
{
    /**
     * 联盟feedConfig管理
     */
    public function indexAction() 
    {
        $affiliateTable = $this->_getTable('AffiliateTable');
        $affiliateFeedConfigTable = $this->_getTable('AffiliateFeedConfigTable');
        
        foreach ($affiliateTable->getAutoDownFeedList() as $affiliateInfo) {
            $feedConfigInfo = $affiliateFeedConfigTable->getInfoByID($affiliateInfo['ID']);
            $affiliateListConfig[$affiliateInfo['ID']] = array_merge($feedConfigInfo, $affiliateInfo);
        }
        $result['affliatelistConfig'] = $affiliateListConfig;
        return $result;
    }
    
    /**
     * 商家FeedConfig管理
     */
    public function merchantFeedConfigAction()
    {
        //参数设置
        $routeParams = array('controller' => 'feedConfig' , 'action' => 'merchantFeedConfig');
        $prefixUrl = $this->url()->fromRoute(null, $routeParams);
        
        //是否是同步商家配置
        $syncMerchantFeedConfig = $this->params()->fromQuery('syncMerchantFeedConfig', '');
        if (!empty($syncMerchantFeedConfig)) {
            $this->syncMerchantFeedConfig();
            return $this->redirect()->toUrl($prefixUrl);
        }

        $params['AffiliateID'] = $this->params()->fromQuery('AffiliateID', 0) * 1;
        
        $searchType = $this->params()->fromQuery('searchType');
        $params['searchType'] = $searchType == 'name' ? 'name' : 'id';
        
        $params['search'] = $this->params()->fromQuery('search', '');
        
        $orderPageParams = $params;
        
        //排序参数
        $params['orderField'] = $this->params()->fromQuery('orderField', 'ID');
        $params['orderType'] = $this->params()->fromQuery('orderType', 'DESC');
        
        $removePageParams = $params;
        
        $params['page'] = $this->params()->fromQuery('page', 1) * 1;
        
        //获取联盟列表
        $affiliateTable = $this->_getTable('AffiliateTable');
        foreach ($affiliateTable->getList() as $affiliateInfo) {
            $affiliateList[$affiliateInfo['ID']] = $affiliateInfo;
        }
        
        //获取商家列表
        $merList = array();
        $paginator = $this->_getMerchantFeedConfigPaginator($params);
        foreach($paginator->getCurrentItems() as $k => $merchantObject){
            $merInfo = (array)$merchantObject;
            $merInfo['AffiliateName'] = $merInfo['AffiliateID'] > 0 ? 
                                        $affiliateList[$merInfo['AffiliateID']]['Name'] : '';
            $merList[] = $merInfo;
        }
        
        //输出参数
        $result = array(
            'paginator' => $paginator,
            'merList' => $merList,
            'affliateList' => $affiliateList,
            'params' => $params,
            'merchantImportQuery' => http_build_query($params),
            'prefix' => $prefixUrl,
            'orderQuery' => http_build_query($orderPageParams),
            'query' => http_build_query($removePageParams)
        );
        
        return $result;
    }
    
    /**
     * 只支持同步国内商家爱非联盟配置
     */
    public function syncMerchantFeedConfig() 
    {
        $merchantTable = $this->_getTable('MerchantTable');
        $merchantFeedConfigTable = $this->_getTable('MerchantFeedConfigTable');
        $merList = $merchantTable->getMerListBySiteID(self::CN_SITE_ID);
        foreach ($merList as $merInfo) {
            $feedConfig = $merchantFeedConfigTable->getListByMerIDAffID($merInfo['MerchantID'], $merInfo['AffiliateID']);
            //下线商家删除此导入配置
            if ($feedConfig && $merInfo['IsActive'] == 'NO') {
                $merchantFeedConfigTable->delete(array('MerchantID' => $merInfo['MerchantID']));
            } else if (empty($feedConfig) && $merInfo['IsActive'] == 'YES') {
                //插入配置
                $insert['MerchantID'] = $merInfo['MerchantID'];
                $insert['AffiliateID'] = $merInfo['AffiliateID'];
                $merchantFeedConfigTable->insert($insert);
            }
        }
    }
    
    /**
     * 下载联盟数据
     * @throws \Exception
     */
    public function affiliateDownloadAction() 
    {
        $id = $this->params()->fromQuery('id', 0) * 1;
        if ($id <= 0) {
            throw new \Exception('AffiliateID IS empty');
        }
        
        $affiliateTable = $this->_getTable('AffiliateTable');
        $affiliateInfo = $affiliateTable->getInfoByID($id);
        if (empty($affiliateInfo)) {
            throw new \Exception('can not found the affiliteID = ' . $id);
        }
        
        $scriptPath = APPLICATION_MODULE_PATH . 'Scripts/Tools';
        $cmd = "php {$scriptPath}/downloadFeed.php \"affiliateID={$id}\"";
        $outFileLog = $this->getStdoutFile($id, 'Affiliate', 'downloadFeedLog');
        
        //加锁
        $processRuntimeTable = $this->_getTable('ProcessRunTimeTable');
        $processRuntimeTable->lock('DownLoadFeed', $affiliateInfo['FeedName'], '', $cmd, $outFileLog);
        Utilities::sysCall($cmd, true, $outFileLog);
        //等待2秒， 更新数据
        echo "Success";
        exit;
        return $this->redirect()->toUrl('/feedConfig');
    }
    
    /**
     * 导入联盟数据
     * @throws \Exception
     */
    public function affiliateImportAction() 
    {
        $id = $this->params()->fromQuery('id', 0) * 1;
        if ($id <= 0) {
            throw new \Exception('AffiliateID IS empty');
        }
        $affiliateTable = $this->_getTable('AffiliateTable');
        $affiliateInfo = $affiliateTable->getInfoByID($id);
        if (empty($id)) {
            throw new \Exception('can not found the affiliteID = ' . $id);
        }
        
        $scriptPath = APPLICATION_MODULE_PATH . 'Scripts/Tools';
        $cmd = "php {$scriptPath}/autoImport.php \"affiliateID={$id}\"";
        $outFileLog = $this->getStdoutFile($id, 'Affiliate', 'importFeedLog');
        
        //加锁
        $processRuntimeTable = $this->_getTable('ProcessRunTimeTable');
        $processRuntimeTable->lock('ImportFeed', 'Affiliate_' . $id, '', $cmd, $outFileLog);
        Utilities::sysCall($cmd, true, $outFileLog);
        //等待2秒， 更新数据
        sleep(2);
        echo "Success";
        exit;
//         return $this->redirect()->toUrl('/feedConfig');
    }
    
    /**
     * 商家数据导入
     */
    public function merchantImportAction()
    {
        $id = $this->params()->fromQuery('id', 0) * 1;
        $page = $this->params()->fromQuery('page', 1) * 1;
        if ($id <= 0) {
            throw new \Exception('MerchantID IS empty');
        }
        $merchantTable = $this->_getTable('MerchantTable');
//         $merchantInfo = $merchantTable->getInfoById($id);
//         if ($merchantInfo['AffiliateID'] > 0) {
//             throw new \Exception('affiliate merchant not importFeed.');
//         }
        
        $scriptPath = APPLICATION_MODULE_PATH . 'Scripts/Tools';
        $cmd = "php {$scriptPath}/autoImport.php \"merid={$id}\"";
        $outFileLog = $this->getStdoutFile($id, 'Merchant', 'importFeedLog');
        
        //加锁
        $processRuntimeTable = $this->_getTable('ProcessRunTimeTable');
        $processRuntimeTable->lock('ImportFeed', 'Merchant_' . $id, '', $cmd, $outFileLog);
        Utilities::sysCall($cmd, true, $outFileLog);
        //等待2秒， 更新数据
        echo "Success";
        exit;
//         return $this->redirect()->toUrl('/feedConfig/merchantFeedConfig?page=' . $page);
    }
    
    /**
     * 商家feed文件导入分页
     * @param array $params
     */
    private function _getMerchantFeedConfigPaginator($params)
    {
        $page = isset($params['page']) ? $params['page'] : 1;
        $order = array();
        if ($params['orderField']) {
            $order = array($params['orderField'] => $params['orderType']);
        }
        $merchantFeedConfigTable = $this->_getTable('MerchantFeedConfigTable');
//         $merchantTable = $this->_getTable('MerchantTable');
        $paginator = new Paginator($merchantFeedConfigTable->formatWhere($params)->getFeedConfigListToPaginator($order));
        $paginator->setCurrentPageNumber($page)->setItemCountPerPage(self::LIMIT);
        return $paginator;
    }
} 
?>