<?php

class IndexController extends JP_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
            	    	//$this->_helper->layout->disableLayout();
        /** TODO Jāpievieno agregators */
        $jaunumiModel=new Model_Jaunumi();
        $this->view->jaunumi=$jaunumiModel->getAllJaunumi();
        
    }

}

