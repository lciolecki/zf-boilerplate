<?php

class Admin_LoginController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $form = new Admin_Form_Login();


        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {

        }

        $this->view->form = $form;
    }
}

