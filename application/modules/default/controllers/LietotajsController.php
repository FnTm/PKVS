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
        $id = $this->_getParam("id", null);
        if ($id == null) {

        }
        else if(!is_null($row=$userModel->getUser($id)) && $row->isApproved==1) {


        }
    }


}

