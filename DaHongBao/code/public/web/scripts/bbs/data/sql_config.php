<?php
/**
* ���±�����������ķ�����˵�����޸�
*/

//require_once("../../etc/const.inc.php");
require_once(dirname(__FILE__)."/../../../etc/const.inc.php");

$_PARSEFE = parse_url(__DAHONGBAO);


$dbhost = $_PARSEFE['host'].":".$_PARSEFE['port'];	// ���ݿ������
$dbuser = $_PARSEFE['user'];	// ���ݿ��û���
$dbpw = $_PARSEFE['pass'];	// ���ݿ�����
$dbname = substr($_PARSEFE["path"],1);	// ���ݿ���
$database = 'mysql';	// ���ݿ�����
$PW = 'pw_';	//�����ַ�
$pconnect = 0;	//�Ƿ�־�����

/*
MYSQL��������
���������̳��������������Ҫ���ô������޸�
�벻Ҫ������Ĵ�����򽫿��ܵ�����̳������������
*/
$charset='latin1';

/**
* ��̳��ʼ��,ӵ����̳����Ȩ��
*/
$manager='admin';	//����Ա�û���
$manager_pwd='c14b843a1b8493859c8d8a984cccaed5';	//����Ա����

/**
* ����վ������
*/
$db_hostweb=1;			//�Ƿ�Ϊ��վ��

/*
* ����url��ַ����http:// ��ͷ�ľ��Ե�ַ  Ϊ��ʹ��Ĭ��
*/
$attach_url=array();

/*
* ͼƬ����Ŀ¼����
*/
$picpath='images';
$attachname='attachment';

/**
* �������
*/
$db_hackdb=array(	
	'bank'=>array('����','bank','1'),
	'colony'=>array('����Ȧ','colony','1'),
	'advert'=>array('������','advert','0'),
	'new'=>array('��ҳ���ù���','new','0'),
	'medal'=>array('ѫ������','medal','0'),
	'toolcenter'=>array('��������','toolcenter','0'),
	'blog'=>array('����','blog','0'),
	'invite'=>array('����ע��','invite','0'),
	'passport'=>array('ͨ��֤','passport','0'),
	'team'=>array('�Ŷӹ���������','team','0'),
	'nav'=>array('�Զ��嵼��','nav','0'),
	'search'=>array('��������','search','1'),
);
?>