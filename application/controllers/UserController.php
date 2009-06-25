<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    // action body
    }

    public function registerAction()
    {
        $this->view->title = "Register";
        $this->view->headTitle($this->view->title, 'PREPEND');
        
        $request = $this->getRequest();
        $form    = new Default_Form_UserRegister();

        if ($this->getRequest()->isPost())
        {
            if ($form->isValid($request->getPost()))
            {
                $model = new Default_Model_User($form->getValues());
                $model->save();
                return $this->_helper->redirector('index');
            }
        }

        $this->view->form = $form;
    }

    public function loginAction()
    {
        $this->view->title = "Login";
        $this->view->headTitle($this->view->title, 'PREPEND');

        $request = $this->getRequest();
        $form = new Default_Form_UserLogin();

        if ($this->getRequest()->isPost())
        {
            if ($form->isValid($request->getPost()))
            {
                
            }
        }
        
        $this->view->form = $form;
    }


}

