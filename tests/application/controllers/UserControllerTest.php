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

class UserControllerTest extends ControllerTestCase
{
    public function setUp()
    {
        require_once APPLICATION_PATH . '/scripts/load.sqlite.php';
    }
    
    public function testIndexAction()
    {
        $this->dispatch('/user');
        $this->assertController('user');
        $this->assertAction('index');
    }
    
    public function testLoginAction()
    {
        $this->dispatch('/user/login');
        $this->assertController('user');
        $this->assertAction('login');
    }

    public function testRegisterAction()
    {
        $this->dispatch('/user/register');
        $this->assertController('user');
        $this->assertAction('register');
    }

    public function testLogoutAction()
    {
        $this->dispatch('/user/logout');
        $this->assertController('user');
        $this->assertAction('logout');
    }

    public function loginUser($user, $password)
    {
        $this->request->setMethod('POST')
            ->setPost(array(
            'username' => $user,
            'password' => $password,
        ));
        $this->dispatch('/user/login');
        $this->assertRedirectTo('/user');
        $this->resetRequest()
            ->resetResponse();

        $this->request->setPost(array());
    }

    public function testInvalidCredentials()
    {
        $request = $this->getRequest();
        $request->setMethod('POST')
            ->setPost(array(
            'username' => '',
            'password' => '',
        ));
        $this->dispatch('/user/login');
        $this->assertNotRedirect();
        $this->assertQuery('form');
    }

    public function testValidCredentials()
    {
        $this->loginUser('testuser', 'testpassword');
    }
}