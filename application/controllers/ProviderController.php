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

class ProviderController extends Zend_Controller_Action
{
    var $_logger;

    public function init()
    {
        /* Initialize action controller here */
        $this->_logger = Zend_Registry::get('logger');
    }

    public function indexAction()
    {
        $server = $this->getServer();
        $sreg = new Zend_OpenId_Extension_Sreg(array(
            'nickname' =>'test',
            'email' => 'test@test.com'
        ));
        $ret = $server->handle(null, $sreg);

        if (is_string($ret))
        {
            echo $ret;
        }
        else if ($ret !== true)
        {
            header('HTTP/1.0 403 Forbidden');
            echo 'Forbidden';
        }
    }

    public function loginAction()
    {
        $this->view->title = "OpenID login";

        $server = $this->getServer();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['openid_action']) &&
            $_POST['openid_action'] === 'login' &&
            isset($_POST['openid_identifier']) &&
            isset($_POST['openid_password']))
        {
            if($server->login($_POST['openid_identifier'], $_POST['openid_password']))
            {
                Zend_OpenId::redirect("/provider", $_GET);
            }
            else
            {
                $this->view->failedAuthentication = true;
            }
        }
    }

    public function trustAction()
    {
        $this->view->title = "Do you trust this website?";

        $server = $this->getServer();

        $this->view->site = $server->getSiteRoot($_GET);
        $this->view->user = $server->getLoggedInUser();

        $user = new Default_Model_User();
        //$sreg = new Zend_OpenId_Extension_Sreg($user->getProfile($server->getLoggedInUser())->toArray());
        $sreg = new Zend_OpenId_Extension_Sreg(array
            (
            'email' => 'test@test.com',
            'fullname' => 'test'
        ));

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['openid_action']) &&
            $_POST['openid_action'] === 'trust')
        {
            if (isset($_POST['allow']))
            {
                if (isset($_POST['forever']))
                {
                    $server->allowSite($server->getSiteRoot($_GET), $sreg);
                }

                $server->respondToConsumer($_GET, $sreg);
            }
            else if (isset($_POST['deny']))
            {
                if (isset($_POST['forever']))
                {
                    $server->denySite($server->getSiteRoot($_GET));
                }

                Zend_OpenId::redirect(urldecode($_GET['openid_return_to']), array('openid.mode'=>'cancel'));
            }
        }
    }

    public function getServer()
    {
        $storage = new OpenId_OpenId_Provider_Storage_Db(new Default_Model_DbTable_User(), new Default_Model_DbTable_Site(), new Default_Model_DbTable_Association());
        $server = new Zend_OpenId_Provider("/provider/login", "/provider/trust", null, $storage);

        return $server;
    }
}