<?php
/*
 *
 * $Id$
 *
 * Software License Agreement (BSD License)
 *
 * Copyright (c) 2009, University of Portsmouth
 * All rights reserved.
 *
 * Redistribution and use of this software in source and binary forms, with or without modification, are
 * permitted provided that the following conditions are met:
 *
 *   Redistributions of source code must retain the above
 *   copyright notice, this list of conditions and the
 *   following disclaimer.
 *
 *   Redistributions in binary form must reproduce the above
 *   copyright notice, this list of conditions and the
 *   following disclaimer in the documentation and/or other
 *   materials provided with the distribution.
 *
 *   Neither the name of University of Portsmouth nor the names of its
 *   contributors may be used to endorse or promote products
 *   derived from this software without specific prior
 *   written permission of University of Portsmouth
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED
 * WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A
 * PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR
 * TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
 * ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->view->headTitle("User Profile", 'PREPEND');

        $request = $this->getRequest();
        $form    = new Default_Form_UserIndex();

        if ($this->getRequest()->isPost())
        {
            if ($form->isValid($request->getPost()))
            {
                $model = new Default_Model_User($form->getValues());
                $model->save();
            }
        }

        // TODO: populate form
        //$form->populate($model->find());
        $this->view->form = $form;
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

                $detailModel = new Default_Model_UserDetail();
                $detailModel->setKey("nickname");
                $detailModel->setValue($form->getValue("nickname"));
                $detailModel->setId($model->getId());
                $detailModel->save();

                $detailModel = new Default_Model_UserDetail();
                $detailModel->setKey("name");
                $detailModel->setValue($form->getValue("name"));
                $detailModel->setId($model->getId());
                $detailModel->save();

                return $this->_helper->redirector('login');
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
                $adapter = $this->getAuthAdapter($form->getValues());
                $auth    = Zend_Auth::getInstance();
                $result  = $auth->authenticate($adapter);

                if (!$result->isValid())
                {
                    $form->setDescription('Sorry, the username and password you entered were invalid.');
                }
                else
                {
                    // store user data in session without password
                    Zend_Auth::getInstance()->getStorage()->write($adapter->getResultRowObject(null, 'password'));
                    $this->_helper->redirector('index', 'user');
                }
            }
        }

        $this->view->form = $form;
    }

    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('login', 'user');
    }

    public function getAuthAdapter(array $params)
    {
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();

        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);

        $params['password'] = md5($params['username'].$params['password']);

        $authAdapter
            ->setTableName('users')
            ->setIdentityColumn('username')
            ->setCredentialColumn('password')
            ->setIdentity($params['username'])
            ->setCredential($params['password']);

        return $authAdapter;
    }
}