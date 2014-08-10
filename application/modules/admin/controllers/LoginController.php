<?php

use System\Controller\Action;

class Admin_LoginController extends Action
{
    public function init()
    {
        if ($this->getAuthUser()) {
            $this->redirect($this->url(array('module' => 'admin'), 'default', true));
        }
    }

    public function indexAction()
    {
        $form = new Admin_Form_Login();
        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {

        }

        $this->view->form = $form;
    }
}

