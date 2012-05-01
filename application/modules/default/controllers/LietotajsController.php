<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Janis
 * Date: 4/17/12
 * Time: 12:32 AM
 * To change this template use File | Settings | File Templates.
 */
class LietotajsController extends JP_Controller_Action
{
    public function profilsAction()
    {
        $userModel=new Model_Users();
        $maksajumiModel=new Model_Maksajumi();
        $id = $this->_getParam("id", null);

        if ($id == null) {
            $apmeklejumsModel=new Model_Apmekletiba();
           $user=Zend_Auth::getInstance()->getIdentity();
            $userId=$user->userId;
            $user=$userModel->getUser($userId);
            $this->view->maksajumi=$maksajumiModel->getMaksajumsByUser($userId);
            $this->view->apmeklejums=$apmeklejumsModel->getApmeklejumsByUser($userId);

        }
        else if(!is_null($row=$userModel->getUser($id)) && $row->isApproved==1) {
       // var_dump($row);

        }
        else{
            //TODO Invalid User screen
            $this->renderScript("index/index_guest.phtml");
        }
    }


}

