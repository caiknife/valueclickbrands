<?php
/**
 * Mezimedia Tracking Service
 *
 * @category   Tracking
 * @package    Tracking_Service
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2009 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Message.php,v 1.1 2013/04/15 10:58:19 rock Exp $
 */

class Tracking_Service_Message
{
    const TYPE_TRAFFIC              = 2;
    const TYPE_GENERAL              = 3;
    const TYPE_IMPRESSION           = 4;
    const TYPE_SPONSOR              = 5;

    const TRAFFIC_INCOMING          = 'i';
    const TRAFFIC_OUTGOING          = 'o';

    /**
     * @var Tracking_Request_Incoming
     */
    protected $_request;

    /**
     * @var Tracking_Session
     */
    protected $_session;

    /**
     * contructor
     */
    public function __construct ()
    {
        $this->_request = new Tracking_Request_Incoming();
        $this->_session = Tracking_Session::getInstance();
    }

    /**
     * send traffic info
     *
     * @param array $traffic
     */
    protected function _sendTraffic($traffic)
    {
        $message = array(
            'source'            => $this->_session->getSource(),
            'serverIp'          => $this->_request->getServer('SERVER_ADDR'),
            'sessionId'         => $this->_session->getSessionId(),
            'clientIp'          => $this->_request->getClientIp(),
            'visitTime'         => date('Y/m/d H:i:s O'),
            'userAgnet'         => $this->_request->getUserAgent(),
            'traffictype'       => isset($traffic['type']) ? $traffic['type'] : '',
        );

        if(($error = sendlog(implode("\t", $message), self::TYPE_TRAFFIC))<0) {
            throw new Tracking_Service_Exception("sendlog traffic error: {$error}");
        };
    }

    /**
     * send general info
     */
    protected function _sendGeneral()
    {
        $message = array(
            'serverIp'          => $this->_request->getServer('SERVER_ADDR'),
            'sessionId'         => $this->_session->getSessionId(),
            'clientIp'          => $this->_request->getClientIp(),
            'visitTime'         => date('Y/m/d H:i:s O'),
        );

        if(($error = sendlog(implode("\t", $message), self::TYPE_GENERAL))<0) {
            throw new Tracking_Service_Exception("sendlog general error: {$error}");
        };
    }

    /**
     * send impression info
     *
     * @param array $impression
     */
    protected function _sendImpression($impression)
    {
        $message = array(
            'keyword'           => isset($impression['keyword'])      ? $impression['keyword'] : '',
            'sessionId'         => $this->_session->getSessionId(),
            'visitTime'         => date('Y/m/d H:i:s O'),
            'sponsorCount'      => isset($impression['sponsorCount']) ? (integer)$impression['sponsorCount'] : 0,
            'productCount'      => isset($impression['productCount']) ? (integer)$impression['productCount'] : 0,
            'backfillCount'     => isset($impression['backfillCount'])? (integer)$impression['backfillCount']: 0,
        );

        if(($error = sendlog(implode("\t", $message), self::TYPE_IMPRESSION))<0) {
            throw new Tracking_Service_Exception("sendlog impression error: {$error}");
        };
    }

    /**
     * send sponsor query info
     *
     * @param array $sponsor
     */
    protected function _sendSponsor($sponsor)
    {
        $message = array(
            'keyword'           => isset($sponsor['keyword']) ? $sponsor['keyword'] : '',
            'sessionId'         => $this->_session->getSessionId(),
            'visitTime'         => date('Y/m/d H:i:s O'),
            'count'             => isset($sponsor['count']) ? (integer)$sponsor['count'] : 0,
        );

        if(($error = sendlog(implode("\t", $message), self::TYPE_SPONSOR))<0) {
            throw new Tracking_Service_Exception("sendlog impression error: {$error}");
        };
    }

    /**
     * send the message
     *
     * @param integer $type
     * @param array $message
     */
    public function send($type, $message=null)
    {
        try {
            if (!function_exists('sendlog')) {
                throw new Tracking_Service_Exception("sendlog not install");
            }

            switch ($type) {
                case self::TYPE_TRAFFIC:
                    $this->_sendTraffic($message);
                    break;

                case self::TYPE_GENERAL:
                    $this->_sendGeneral();
                    break;

                case self::TYPE_IMPRESSION:
                    $this->_sendImpression($message);
                    break;

                case self::TYPE_SPONSOR:
                    $this->_sendSponsor($message);
                    break;

                default:
                    ;
                    break;
            }
        } catch (Tracking_Service_Exception $exception) {
            Tracking_Logger::getInstance()->Error(array(
                'remark'        => $exception->getMessage(),
                'requesturi'    => $this->_request->getRequestUri(),
                'referer'       => $this->_request->getHttpReferer(),
            ));
        }
    }
}