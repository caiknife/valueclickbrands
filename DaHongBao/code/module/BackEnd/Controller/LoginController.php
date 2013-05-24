<?php
namespace BackEnd\Controller;

use Zend\Session\Container;

use Zend\Db\TableGateway\TableGateway;

use Zend\Db\ResultSet\ResultSet;

use BackEnd\Form\LoginForm;

use Custom\Mvc\Controller\AbstractActionController;
use BackEnd\Model\Users\User;
use BackEnd\Model\Users\UserTable;

use Custom\Form\View\Helper\FormInput;

class LoginController extends AbstractActionController
{
    function indexAction(){
        $url = $this->params()->fromQuery('url' , '');
        $form = new LoginForm();
        $this->layout('layout/basic');
        
        $return = array('url' => $url , 'form' => $form);
        if($this->flashMessenger()->hasMessages()){
            $return['msg'] = $this->flashMessenger()->getMessages();
        }
        
        return $return;
    }

    function submitAction(){
        $request = $this->request;
        if($this->request->isPost()){
            $username = $this->params()->fromPost('username');
            $pwd = $this->params()->fromPost('password');
            $data = $request->getPost();
            $form = new LoginForm();
            $form->setData($data);
            if($username && $pwd){
                $sm = $this->getServiceLocator();
                $auth = $sm->get('Auth');
                $apapter = $auth->getAdapter();
                $apapter->setUsername($username);
                $apapter->setPassword($pwd);
                $auth->authenticate($apapter);
                if($email = $auth->getIdentity()){
                    //跳转url
                    $userTable = $this->getServiceLocator()->get('UserTable');
                    $user = $userTable->getOneForEmail($email);
                    if($user){
                        $container = $this->_getSession();
                        $container->UserID = $user->UserID;
                        $container->Name = $user->Name;
                        $container->LastChangeDate = $user->LastChangeDate;
                        $container->Mail = $user->Mail;
                        $container->Role = $user->DahongbaoRole;
                        if($url = $this->params()->fromQuery('url')){
                            return $this->redirect()->toUrl($url);
                        }else{
                            return $this->redirect()->toRoute('home');
                        }
                    }else{
                        $this->flashMessenger()->addMessage('没有这个用户:' . $email);
                        return $this->redirect()->toUrl('/login');
                    }
                }else{
                    $this->flashMessenger()->addMessage('用户名或密码错误');
                    return $this->redirect()->toUrl('/login');
                }
            }else{
                throw new \Exception('error');
                $this->flashMessenger()->addMessage('用户名或密码不能为空');
                return $this->redirect()->toUrl('/login');
            }
        }else{
            $this->flashMessenger()->addMessage('用户名或密码错误');
            return $this->redirect()->toUrl('/login');
        }
        return $this->redirect()->toUrl('/login');
    }
    
    /**
     * 登出 
     */
    public function logoutAction(){
        $container = new Container('user');
        $container->getManager()->destroy();
        return $this->redirect()->toRoute('backend' , array('controller' => 'login' , 'action' => 'index'));
    }
}